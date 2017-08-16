<?php

use Phinx\Seed\AbstractSeed;

class FakeSongs extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $data = [
            [
                'name'    => 'fake song 1',
                'file' => 'faksesong.mp3'
            ],
            [
                'name'    => 'fake song 2',
                'file' => 'faksesong.mp3'
            ],
            [
                'name'    => 'fake song 3',
                'file' => 'faksesong.mp3'
            ],
        ];

        $posts = $this->table('smusic_song');
        $posts->insert($data)
            ->save();
    }
}
