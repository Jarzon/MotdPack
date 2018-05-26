<?php

use Phinx\Migration\AbstractMigration;

class MusicVolume extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('playtime');
        $table
            ->addColumn('volume', 'integer', ['default' => '40'])
            ->update();
    }
}
