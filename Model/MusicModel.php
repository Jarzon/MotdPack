<?php
namespace MotdPack\Model;

use Prim\Model;

class MusicModel extends Model
{
    public function getSettings(string $steamid)
    {
        $query = $this->prepare("SELECT volume FROM playtime WHERE steamid = ?");
        $query->execute([$steamid]);

        return $query->fetch();
    }
    public function saveSettings(int $volume, string $steamid)
    {
        $query = $this->prepare("UPDATE playtime SET volume = ? WHERE steamid = ?");
        $query->execute([$volume, $steamid]);
    }
}