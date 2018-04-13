<?php
/** @var $this \Prim\Router */
$this->get('/', 'MotdPack\Home', 'index');
$this->get('/message/{message:.+}', 'MotdPack\Home', 'message');
$this->get('/motdpreview', 'MotdPack\Home', 'motdPreview');
$this->get('/scoreboard/[{page:\d+}]', 'MotdPack\Home', 'scoreboard');
$this->get('/server[/{port:\d+}]', 'MotdPack\Home', 'server');

$this->addRoute(['GET', 'POST'], '/login', 'MotdPack\Home', 'login');

