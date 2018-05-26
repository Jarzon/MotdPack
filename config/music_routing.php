<?php
/** @var $this \Prim\Router */
$this->addGroup('/music', function($r) {
    $r->get('/[{page:\d+}]', 'MotdPack\Music', 'index');
    $r->get('/play/{song}[/{volume:\d+}]', 'MotdPack\Music', 'play');
    $r->both('/settings/', 'MotdPack\Music', 'settings');

    $r->both('/uploader', 'MotdPack\Music', 'uploader');
    $r->both('/edit/{song:\d+}', 'MotdPack\Music', 'edit');
    $r->get('/delete/{song:\d+}', 'MotdPack\Music', 'delete');
});

$this->both('/login', 'MotdPack\Music', 'login');