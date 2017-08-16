<?php
$this->addGroup('/basepack', function($r) {
    $r->get('/', 'MotdPack\Home', 'index');
});