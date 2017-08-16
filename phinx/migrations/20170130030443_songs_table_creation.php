<?php

use Phinx\Migration\AbstractMigration;

class SongsTableCreation extends AbstractMigration
{

    public function change()
    {
        $table = $this->table('smusic_song', ['id' => 'song_id']);
        $table->addColumn('name', 'string', ['limit' => 25])
            ->addColumn('file', 'string', ['limit' => 50])
            ->create();
    }
}
