<?php
namespace Jarzon\MotdPack\Controller;

use Prim\Controller;

use PrimUtilities\Paginator;
use xPaw\SourceQuery\SourceQuery;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class Home extends Controller
{
    function getNumbers($db) {
// Get the player playtime
        $noTime = false;

        if($db->connect_error) {
            $noTime = true;
        }

        $playerPosition = 0;
        if(!$noTime) {
            if ($query = $db->query('SELECT name, time FROM playtime WHERE ip = "'.$_SERVER['REMOTE_ADDR'].'" ORDER BY time LIMIT 0, 1')) {
                if($query->num_rows > 0) {
                    $res = $query->fetch_object();

                    $query = $db->query('SELECT id, min_time FROM playtime_page WHERE min_time <= '.$res->time.' ORDER BY min_time DESC LIMIT 0, 1');
                    if($query->num_rows > 0) {
                        $query = $query->fetch_object();
                        $playerPage = $query->number;

                        $query = $db->query('SELECT COUNT(*) AS number FROM playtime WHERE time >= ' . $query->min_time . ' AND time < ' . $res->time . ' ORDER BY time DESC');
                        if($query->num_rows > 0) {
                            $query = $query->fetch_object();

                            $playerPosition = ($playerPage * 15) - $query->number;
                        }
                    }
                } else {
                    $noTime = true;
                }
            }
        }

        $query = new SourceQuery();

        $infos = [];

        try {
            $query->Connect( SQ_SERVER_ADDR, SQ_SERVER_PORT, SQ_TIMEOUT, SQ_ENGINE );

            $infos = $query->GetInfo();
        }
        catch( Exception $e ) {

        }
        finally {
            $query->Disconnect();
        }

        $this->addVar('connectedRatio', 'Offline');
        if($infos != null) {
            $this->addVar('connectedRatio', $infos['Players'].'/'.$infos['MaxPlayers']);
        }

        $playtime = '';
        if(!$noTime) {
            $mins = floor($res->time / 60);
            $hours = floor(($res->time / 60) / 60);

            if($hours == 0) {
                $playtime .= $mins.'m';
            } else if($hours < 10) {
                $mins = $mins - ($hours*60);
                $playtime .= $hours.'h '.$mins.'m';
            } else {
                $playtime .= $hours.'h';
            }
            if($playerPosition > 0) $playtime .= ' ['.$playerPosition.']';
        }

        $this->addVar('playtime', $playtime);
    }

    public function login()
    {
        if(isset($_POST['password'])) {
            if(strcmp($_POST['password'], 'Ijustlovekillingbotty') === 0) {
                $_SESSION['auth'] = true;

                $this->redirect('/botty/');
            }
        }

        $this->design('home/login');
    }

    public function index()
    {
        // Fuck you past me
        $db = new \mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $db->set_charset(DB_CHARSET);

        $this->getNumbers($db);

        $adminsListFile = '../app/cache/adminsListCache';
        $nodeValues = '';
        $getLastMod = time();

        if(file_exists($adminsListFile)) {
            $nodeValues = unserialize(file_get_contents($adminsListFile));

            $getLastMod = filemtime($adminsListFile);
        }

// If steam is down, if there is a cached admin list show it or else dont show anything
        try {
            $client = new Client();
            if(ENV === 'dev') {
                $client->setClient(new \GuzzleHttp\Client([
                    // DISABLE SSL CERTIFICATE CHECK
                    'verify' => false,
                ]));
            }
            $crawler = $client->request('GET', 'http://steamcommunity.com/groups/'.STEAM_GROUP_NAME.'/members?content_only=true');
        }
        catch( Exception $e ) {
            if($nodeValues) $getLastMod = time();
            else $getLastMod = 0;
        }

// 1h cache
        if((!$nodeValues || (time() - $getLastMod) > 60 * 60) && $getLastMod > 0) {
            $corrupted = false;

            $nodeValues = $crawler->filter('div.member_block')->each(function (Crawler $node, $i) {

                // Get only the group admin/mods
                if($node->filter('div.rank_icon')->count() > 0) {
                    // Add _full to the img name to get full size avatar
                    $avatar = $node->filter('div.playerAvatar img')->attr('src');
                    $avatar = explode('.', $avatar);
                    $avatar[count($avatar)-2] .= '_full';
                    $avatar = implode('.', $avatar);

                    $name = htmlentities($node->filter('div.member_block_content > div > a')->text());

                    $link = $node->filter('div.playerAvatar a')->attr('href');

                    // Get the status offline, online or in-game
                    $status = $node->filter('div.playerAvatar')->attr('class');
                    $status = explode(' ', $status);
                    $status = $status[1];

                    if($status == 'in-game') $status = 'online';

                    $result = [
                        'avatar' => $avatar,
                        'name' => $name,
                        'link' => $link,
                        'status' => $status
                    ];

                    if($name == null) {
                        $_GLOBAL['corrupted'] = true;
                        $result = [];
                    }

                    return $result;
                }
            });

            // Clean the array from null entries that have been created by the filter because of simple members
            $nodeValues = array_filter($nodeValues);

            if(!$corrupted) file_put_contents($adminsListFile, serialize($nodeValues));

        }

        // load views
        $this->design('home/index', 'MotdPack', [
            'admins' => $getLastMod
        ]);
        $db->close();
    }

    public function motdPreview()
    {
        $this->setTemplate('backend');
        $this->design('home/motdPreview');
    }

    public function message($message)
    {
        $this->design('home/message', 'MotdPack', [
            'message' => $message
        ]);
    }

    public function server($port = 27015)
    {
        $this->redirect('steam://connect/'.SQ_SERVER_ADDR.':'.SQ_SERVER_PORT);
    }

    public function scoreboard($page = 1)
    {
        // Fuck you past me
        $db = new \mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $db->set_charset(DB_CHARSET);

        $this->getNumbers($db);

        $currentSteamid = '';
        if ($query = $db->query('SELECT steamid FROM playtime WHERE ip = "'.$_SERVER['REMOTE_ADDR'].'" ORDER BY time LIMIT 0, 1')) {
            if($query->num_rows > 0) {
                $res = $query->fetch_object();
                $currentSteamid = $res->steamid;
            }
        }

        if (apcu_exists('playerTime') && apcu_exists('playerNumber')) {
            $playerTime = apcu_fetch('playerTime');
            $playerNumber = apcu_fetch('playerNumber');
        } else {
            $query = $db->query('SELECT SUM(time) AS playTime, COUNT(*) AS playerNumber FROM playtime WHERE name != ""');
            $res = $query->fetch_object();
            $playerTime = $res->playTime;
            $playerNumber = $res->playerNumber;

            // Keep the values for 1 hour
            apcu_add('playerTime', $playerTime, 360);
            apcu_add('playerNumber', $playerNumber, 360);
        }

        // Pagination
        $playerPerPage = 15;

        $paginator = new Paginator($page, $playerNumber, $playerPerPage, 5);
        $page = $paginator->getPage();
        $first = $paginator->getFirstPageElement();

        $query = $db->query('SELECT min_time
          FROM playtime_page
          WHERE id = '.$page.'
          LIMIT 0, 1');

        $minTime = -1;

        if($query) {
            $res = $query->fetch_object();
            if($res) $minTime = $res->min_time;
        }

        $query = "SELECT steamid, name, time
          FROM playtime
          WHERE name != ''
          " . ($minTime > 0?" AND time >= $minTime":'') . "
          ORDER BY time DESC
          LIMIT $first, $playerPerPage";

        $query = $db->query($query);

        $this->addVar('playerNumber', $playerNumber);
        $this->addVar('playerTime', ceil((($playerTime / 60) / 60)));
        $this->addVar('pagination', '');

        $list = [];

        if($query) {
            $lastTime = 0;

            $pos = ($first+1);

            while($res = $query->fetch_object()) {
                $hours = round(($res->time / 60) / 60);
                $class = '';

                if($res->steamid == $currentSteamid) $class = 'me';

                $list[] = [
                    'class' => $class,
                    'pos' => $pos,
                    'steamid' => $res->steamid,
                    'name' => $res->name,
                    'hours' => $hours,
                ];

                $lastTime = $res->time;

                $pos++;
            }

            // If there is a full page
            if(($pos - ($first + 1)) == $playerPerPage) {
                if($minTime > 0 && $minTime > $lastTime) {
                    $db->query('UPDATE playtime_page SET min_time = '.$lastTime.' WHERE id = '.$page);
                }
                else if($minTime < 0) {
                    $db->query('INSERT INTO playtime_page(id, min_time) VALUES('.$page.', '.$lastTime.')');
                }
            }

            $this->addVar('pagination', $paginator->showPages());

        } else echo 'Error';

        $this->design('home/scoreboard', 'MotdPack', [

        ]);
        $db->close();
    }
}