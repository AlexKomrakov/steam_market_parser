<?php

class TestTask extends \Phalcon\CLI\Task {

    public function mainAction() {
        echo 'TestTask::mainAction' . PHP_EOL;
    }

    public function calculateAction($a = 0, $b = 0) {
        $this->writeln('TestTask::calculateAction');
        sleep(1);
        $this->writeln('calculating...');
        sleep(2);
        $this->writeln("$a + $b = " . ($a + $b));
    }

    protected function writeln($text) {
        $f = fopen('php://stdout', 'w');
        fputs($f, $text . PHP_EOL);
        fclose($f);
    }
}