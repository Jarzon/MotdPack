<?php

use Phinx\Migration\AbstractMigration;

class PlaytimeCreate extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('playtime', ['id' => false, 'primary_key' => ['steamid']]);
        $table->addColumn('steamid', 'string', ['limit' => 17])
            ->addColumn('ip', 'string', ['limit' => 45])
            ->addColumn('name', 'string', ['limit' => 32, 'default' => 'No name'])
            ->addColumn('time', 'integer', ['signed' => false])
            ->addColumn('relation', 'integer', ['signed' => false])
            ->create();
    }
}
