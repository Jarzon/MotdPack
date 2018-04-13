<?php
namespace MotdPack\Model;

use Prim\Model;

class PlaytimeModel extends Model
{
    public function getAllRows()
    {
        $query = $this->db->prepare("SELECT * FROM base_table");
        $query->execute();

        return $query->fetchAll();
    }
}