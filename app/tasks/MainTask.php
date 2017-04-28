<?php

use Phalcon\Cli\Task;


class MainTask extends Task
{
    public function mainAction()
    {
        $token = hash('sha256', '8-aaa');
        echo $token .PHP_EOL;

    }
}
