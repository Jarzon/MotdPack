<?php
namespace MotdPack\Model;

use Prim\Model;

class PlaytimeModel extends Model
{
    public function getUser(string $ip)
    {
        $query = $this->db->prepare("SELECT steamid FROM playtime WHERE ip = ? ORDER BY time LIMIT 0, 1");
        $query->execute([$ip]);

        $result = $query->fetchObject();

        if($result) {
            return $result->steamid;
        }

        return false;
    }

    public function getPlaytimeStats()
    {
        $query = $this->db->prepare("SELECT SUM(time) AS playTime, COUNT(*) AS playerNumber FROM playtime");
        $query->execute();

        $result = $query->fetch(\PDO::FETCH_ASSOC);

        if($result) {
            return $result;
        }

        return false;
    }

    public function getPageTimings(int $page) : int
    {
        $query = $this->db->prepare("SELECT min_time
          FROM playtime_page
          WHERE id = ?
          LIMIT 0, 1");
        $query->execute([$page]);

        $result = $query->fetchObject();

        if($result) {
            return $result->min_time;
        }

        return 0;
    }

    public function getPage(int $page, int $first, int $playerPerPage, int $minTime = 0)
    {
        $query = $this->prepare("SELECT PT.steamid, IFNULL(PT.name, steamid) as name, PT.time
          FROM playtime PT
          WHERE PT.time >= ?
          ORDER BY PT.time DESC
          LIMIT ?, ?");
        $query->execute([$minTime, $first, $playerPerPage]);

        return $query->fetchAll(\PDO::FETCH_OBJ);
    }

    public function setPlaytimePageTime(int $page, int $minTime, int $lastTime)
    {
        if($minTime > 0 && $minTime > $lastTime) {
            $this->prepare('UPDATE playtime_page SET min_time = '.$lastTime.' WHERE id = '.$page);
        }
        else if($minTime < 0) {
            $this->prepare('INSERT INTO playtime_page(id, min_time) VALUES('.$page.', '.$lastTime.')');
        }
    }
}