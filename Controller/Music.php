<?php
namespace MotdPack\Controller;

use MotdPack\Model\MusicModel;
use MotdPack\Model\PlaytimeModel;
use Prim\Controller;

use MotdPack\Service\Uploader;
use Jarzon\Pagination;

function file_upload_max_size() {
    static $max_size = -1;

    if ($max_size < 0) {
        // Start with post_max_size.
        $max_size = parse_size(ini_get('post_max_size'));
        $post = ini_get('post_max_size');

        // If upload_max_size is less, then reduce. Except if upload_max_size is
        // zero, which indicates no limit.
        $upload = ini_get('upload_max_filesize');
        $upload_max = parse_size($upload);
        if ($upload_max > 0 && $upload_max < $max_size) {
            $max_size = $upload;
        } else {
            $max_size = $post;
        }
    }
    return $max_size;
}

function parse_size($size) {
    $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
    $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
    if ($unit) {
        // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
        return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
    }
    else {
        return round($size);
    }
}

class Music extends Controller
{
    public $db;

    private function mysqliConnection()
    {
        $db = new \mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $db->set_charset(DB_CHARSET);
        $this->db = $db;

        return $db;
    }

    private function sanatize($input) {
        return $this->db->real_escape_string(htmlspecialchars($input));
    }

    public function login()
    {
        if(isset($_POST['password'])) {
            if(strcmp($_POST['password'], ADMIN_PASSWORD) === 0) {
                $_SESSION['auth'] = true;

                $this->redirect('/music/');
            }
        }

        $this->design('music/login');
    }

    public function loginVerification()
    {
        if(!$_SESSION['auth']) {
            $this->redirect('/login');
        }
    }

    public function index($page = 1)
    {
        $db = $this->mysqliConnection();

        $playerPerPage = 15;

        if($query = $db->query('SELECT COUNT(*) AS number FROM smusic_song')) {
            $res = $query->fetch_object();
        } else {
            $res = new class{
                public $number = 0;
                public $min_time = 0;
            };
        }

        $paginator = new Pagination($page, $res->number, $playerPerPage, 5);
        $first = $paginator->getFirstPageElement();

        $songList = [];

        if ($query = $db->query('SELECT MS.id, MS.name, MS.playCount
          FROM smusic_song MS
          ORDER BY MS.id')) {
            $songList = $query->fetch_all(MYSQLI_ASSOC);
        }

        $this->design('music/index', 'MotdPack', [
            'admin' => $_SESSION['auth'] ?? false,
            'songList' => $songList,
            'pagination' => $paginator->showPages(),
        ]);
        $db->close();
    }

    public function play($song = '', $volume = 4)
    {
        $db = $this->mysqliConnection();

        if ($query = $db->query('SELECT SS.id, SS.name, SS.file FROM smusic_song SS WHERE SS.name LIKE "%'.urldecode($song).'%" LIMIT 0, 1 ')) {
            if($query->num_rows == 1) {
                $song = $query->fetch_assoc();

                $db->query('UPDATE smusic_song SS SET SS.playCount = SS.playCount + 1 WHERE SS.id = '.$song['id'].' LIMIT 1');
            } else {
                $this->redirect('/music/');
            }
        }

        if($volume > 10 || $volume < 0) $volume = 4;

        // load views
        $this->design('music/play', 'MotdPack', [
            'song' => $song,
            'volume' => $volume
        ]);
        $db->close();
    }

    public function uploader()
    {
        $this->loginVerification();

        // Fuck you past me
        $db = $this->mysqliConnection();

        if(isset($_POST['submit'])) {
            $numberFiles = count($_FILES['upload']['tmp_name']);

            $inserts = [];

            for($n = 0; $n <= ($numberFiles - 1); $n++) {
                $uploader = new Uploader(ROOT . 'public/songs', 'upload', $n);

                if($uploader->saveFile()) {

                    $inserts[] = '("'.$uploader->getFileName().'", "'.$uploader->getFileNameHash().'.'.$uploader->getFileExt().'")';
                }
            }

            $db->query('INSERT INTO smusic_song (name, file) VALUES ' . implode(', ', $inserts));

            echo '<p>'.$db->affected_rows.' on '.$numberFiles.' new songs have been added.</p>';
        }

        $this->addVar('maxUpload', file_upload_max_size());

        // load views
        $this->design('music/uploader');
        $db->close();
    }

    public function edit($song)
    {
        $this->loginVerification();

        $db = $this->mysqliConnection();

        $song = intval($song);
        if ($song <= 0) {
            $this->redirect('/music/');
        }

        $query = $db->query('SELECT id, name, file, playCount FROM smusic_song WHERE id = '.$song.' LIMIT 0, 1');
        if(!$query) {
            $this->redirect('/music/');
        }
        $song = $query->fetch_assoc();


        if(isset($_POST['submit'])) {
            $name = $this->sanatize($_POST['name']);
            $db->query('UPDATE smusic_song SET name = "'.$name.'" WHERE id = '.$song['id']);
            $song['name'] = $name;
        }

        $this->design('music/edit', 'MotdPack', [
            'song' => $song
        ]);
        $db->close();
    }

    public function delete($song)
    {
        $this->loginVerification();

        $db = $this->mysqliConnection();

        $song = intval($song);
        if ($song <= 0) {
            $this->redirect('/music/');
        }

        $db->query("DELETE FROM smusic_song WHERE id = $song LIMIT 1");

        $this->redirect('/music/');
    }

    public function settings()
    {
        /**
         * @var MusicModel $musicModel
         * @var PlaytimeModel $playtimeModel
         */
        $musicModel = $this->getModel('MusicModel', 'MotdPack');
        $playtimeModel = $this->getModel('PlaytimeModel', 'MotdPack');

        if(!$steamid = $playtimeModel->getUser($_SERVER['REMOTE_ADDR'])) {
            $this->redirect('/');
        }

        $settings = $musicModel->getSettings($steamid);

        if(isset($_POST['submit'])) {
            $musicModel->saveSettings($_POST['volume'], $steamid);

            $settings->volume = $_POST['volume'];

            $this->addVar('message', ['ok', 'The settings have been updated.']);
        }

        $this->design('music/settings', 'MotdPack', [
            'settings' => $settings
        ]);
    }
}