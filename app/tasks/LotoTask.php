<?php

use Phalcon\Cli\Task;

class LotoTask extends Task
{
    public function lotoAction($args)
    {
        echo "##### START #####" .PHP_EOL;

        $batchType = $args[0];
        $lotoType  = $args[1];


        switch ($batchType) {
            case 1:

                $lotoTypeInfo = $this->config->loto_infos->config->$lotoType;

                $lotteryResult = new LotteryResultBatchService($lotoTypeInfo);
                //$lotteryResult->lotteryResultRegistration($lotoType, $lotoTypeInfo);
                break;
            case 2:

                $lotoTypeInfo = $this->config->past_loto_infos->$lotoType;

                $lotteryResult = new LotteryResultBatchService($lotoTypeInfo);
                $lotteryResult->pastInfoRegistration($lotoType);
                break;
            default:
                break;
        }

        echo "##### END #####" .PHP_EOL;
    }
}
