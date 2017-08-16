<?php

use Phinx\Migration\AbstractMigration;

class SongPlayCount extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('smusic_song');
        $table
            ->addColumn('playCount', 'integer', ['signed' => false, 'default' => '0'])
            ->update();
    }
}
