<?php

use Phinx\Migration\AbstractMigration;

class PlaytimePageCreate extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('playtime_page');
        $table->addColumn('min_time', 'integer', ['signed' => false])
            ->create();
    }
}
