<?php

use Pheanstalk\Pheanstalk;

class ParselinksTask extends \Phalcon\CLI\Task {

    public $count = 5;

    public function mainAction() {

        $queue = new Pheanstalk('localhost');
        $queue->watch('default');

        while($job = $queue->reserve(60)) {
            $statsJob = $queue->statsJob($job);
            if ($statsJob["releases"] > 100)
                $queue->bury($job);
            $body = $job->getData();
            $body = json_decode($body, true);

            $url = $body['link'];
            $response = Grabber::grab($url);

            $pattern = '/var line1=(.*);/';

            preg_match($pattern, $response, $matches);
            if (!isset($matches[1])) {
                echo "Error\n";
                $queue->release($job);
                continue;
            }

            $result = json_decode($matches[1]);
            $lastId = count($result);
            $count = $this->count;
            $count = ($lastId >= $count) ? $count : 1;
            $aggregate = [];
            $last = [];
            for ($i = 1; $i<=$count; $i++) {
                $time = strtotime($result[$lastId-$i][0]); //Convert to Unix time
                $price = round($result[$lastId-$i][1], 2); //Round to *.**
                $selled = (int) preg_replace('/\D/', '', $result[$lastId-$i][2]); //Parse count of selled items
                $aggregate[] = [
                    'time' => $time,
                    'price' => $price,
                    'selled' => $selled
                ];

                if ($i == 1) {
                    $last = $aggregate[0];
                }
            }
            $item = new Items;
            $item->link = $url;
            $item->name = $this->getNameFromLink($url);
            $item->summary = $aggregate;
            $item->last = $last;
            $item->save();
            var_dump($job->getId());
            //echo "$job- complete";
            $queue->delete($job);

        }

    }

    private function getNameFromLink($url)
    {
        $link = urldecode($url);
        preg_match("/\/([^\/]*)$/", $link, $matches);
        return $matches[1];
    }

}