<?php
namespace MotdPack\MotdPack\Controller;

use Prim\Controller;

/**
 * Class Home
 *
 */
class Home extends Controller
{
    public function build() {
        // $this->setTemplate('design');
    }

    /**
     * PAGE: index
     */
    public function index()
    {
        $model = $this->getModel('BaseModel', 'MotdPack');

        $this->design('home/index', ['name' => 'anonymous']);
    }
}
