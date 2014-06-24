<?php

use Pheanstalk\Pheanstalk;

class MainTask extends \Phalcon\CLI\Task {

    public function mainAction() {
        $queue = new Pheanstalk('localhost');
        $jobId = $queue->put(json_encode(['link' => 'http://steamcommunity.com/market/listings/570/2014%20Player%20Card%20Pack']));
        echo $jobId;
        $jobId = $queue->put(json_encode(['link' => 'http://steamcommunity.com/market/listings/570/Jade%20Talon']));
        echo $jobId;
    }
}