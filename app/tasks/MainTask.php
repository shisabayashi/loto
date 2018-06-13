<?php

use Phalcon\Cli\Task;


class MainTask extends Task
{
    public function mainAction()
    {
        $token = hash('sha256', '');
        echo $token .PHP_EOL;

    }
}
