<?php
/** @var $this \Prim\Router */
$this->addGroup('/music', function($r) {
    $r->get('/[{page:\d+}]', 'MotdPack\Music', 'index');
    $r->get('/play/{song}[/{volume:\d+}]', 'MotdPack\Music', 'play');

    $r->get('/uploader', 'MotdPack\Music', 'uploader');
    $r->post('/uploader', 'MotdPack\Music', 'uploader');

    $r->addRoute(['GET', 'POST'], '/edit/{song:\d+}', 'MotdPack\Music', 'edit');
    $r->get('/delete/{song:\d+}', 'MotdPack\Music', 'delete');
});