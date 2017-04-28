<?php

use Phalcon\Cli\Task;

class LotoTask extends Task
{
    public function lotoAction($args)
    {
        echo "##### START #####" .PHP_EOL;

        $lotoType = $args[0];
        $lotoTypeInfo = $this->config->loto_infos->$lotoType;
        $scrapingInfo = $this->config->scraping_info;

        $lotteryResult = new LotteryResultBatchService($scrapingInfo);
        $lotteryResult->lotteryResultRegistration($lotoType, $lotoTypeInfo);

        echo "##### END #####" .PHP_EOL;
    }
}
