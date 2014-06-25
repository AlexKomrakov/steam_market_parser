<?php

use Pheanstalk\Pheanstalk;

class IndexController extends ControllerBase
{

    public $count = 5;

    public function indexAction()
    {
        $this->view->disable();
        echo "<pre>";
        $queue = new Pheanstalk('localhost');
        print_r($queue->statsTube('default'));
        echo "</pre>";

        $items = Items::find();
        foreach ($items as $item) {
            echo "<pre>";
            print_r($item->toArray());
            echo "</pre>";
        }
    }

}

