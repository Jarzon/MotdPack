<?php
$this->get('/', 'MotdPack\Home', 'index');
$this->get('/message/{message:.+}', 'MotdPack\Home', 'message');
$this->get('/motdpreview', 'MotdPack\Home', 'motdPreview');
$this->get('/scoreboard/[{page:\d+}]', 'MotdPack\Home', 'scoreboard');
$this->get('/server[/{port:\d+}]', 'MotdPack\Home', 'server');

$this->addRoute(['GET', 'POST'], '/login', 'MotdPack\Home', 'login');

$this->addGroup('/music', function($r) {
    $r->get('/[{page:\d+}]', 'MotdPack\Music', 'index');
    $r->get('/play/{song}[/{volume:\d+}]', 'MotdPack\Music', 'play');

    $r->get('/uploader', 'MotdPack\Music', 'uploader');
    $r->post('/uploader', 'MotdPack\Music', 'uploader');

    $r->addRoute(['GET', 'POST'], '/edit/{song:\d+}', 'MotdPack\Music', 'edit');
    $r->get('/delete/{song:\d+}', 'MotdPack\Music', 'delete');
});