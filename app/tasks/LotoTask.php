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
                $scrapingInfo = $this->config->loto_infos->scraping_info;

                $lotteryResult = new LotteryResultBatchService($lotoTypeInfo);
                $lotteryResult->lotteryResultRegistration($lotoType, $scrapingInfo);
                break;
            case 2:

                $lotoTypeInfo = $this->config->past_loto_infos->$lotoType;

                $lotteryResult = new LotteryResultBatchService($lotoTypeInfo);
                $lotteryResult->pastInfoRegistration($lotoType);
                break;
            case 3:

                echo 'Number Ranking!!' .PHP_EOL;

                //$lotoTypeInfo = $this->config->past_loto_infos->$lotoType;

                $lotteryResult = new LotteryResultBatchService2();
                $lotteryResult->updateLotoNumberRanking($lotoType);
//                $lotteryResult->updateOldLotoNumberRanking($lotoType);
                break;
            default:
                break;
        }

        echo "##### END #####" .PHP_EOL;
    }
}
