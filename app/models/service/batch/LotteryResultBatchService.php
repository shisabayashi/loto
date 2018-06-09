<?php

use Phalcon\Mvc\Model;

class LotteryResultBatchService extends Model
{
    private $lotoTypeInfo;
    private $lotoType;
    private $tableEle;
    private $eventNumEle;
    private $eventDateEle;
    private $lotoNumEle;
    private $bonusNumEle;


    public function onConstruct($lotoTypeInfo)
    {
        $this->lotoTypeInfo = $lotoTypeInfo;
    }


//    public function lotteryResultRegistration($lotoType, $lotoTypeInfo)
//    {
//
//        echo 'タイプ: ' .$lotoType .PHP_EOL;
//
//        $lotoNumCount  = $lotoTypeInfo['loto_num'];
//        $bonusNumCount = $lotoTypeInfo['bonus_num'];
//        $getDataCount  = $lotoTypeInfo['get_data_count'];
//        $url           = $lotoTypeInfo['url'];
//
//        //$HTMLData = file_get_contents($lotoTypeInfo['url']);
//        $HTMLData = shell_exec('casperjs /home/apu/loto/app/js/loto.js "' .$url .'"');
//        $phpQueryObj = phpQuery::newDocument($HTMLData);
//
////echo "phpQueryObj" .PHP_EOL;
////echo $phpQueryObj;
//
//        for ($tableCount = 0; $tableCount < $getDataCount; $tableCount++) {
//
//            $strTableEle = $this->elementReplace($tableCount, $this->tableEle);
//            $tableObj = $phpQueryObj[$strTableEle];
//
////echo $tableObj;
//
//            // 回�
//            $eventNumber = $tableObj->find($this->eventNumEle)->text();
//            $eventNumber = preg_replace('/[^0-9]/', '', $eventNumber);
//            echo '回数: ' .$eventNumber .PHP_EOL;
//
//            // 日付
//            $rowDate = $tableObj->find($this->eventDateEle)->text();
//            $format = 'Y年m月d日';
//            $lotoDate = DateTime::createFromFormat($format, $rowDate)->format('Y-m-d') ;
//            echo '日付: ' .$lotoDate .PHP_EOL;
//
//            echo "抽せん数字: ";
//            $lotoNumberArray = array();
//            for ($i = 0; $i < $lotoNumCount; $i++) {
//                $strLotoNumEle = $this->elementReplace($i, $this->lotoNumEle);
//                $lotoNumber    = $tableObj->find($strLotoNumEle)->text();
//                $lotoNumberArray[] = $lotoNumber;
//                echo $lotoNumber .' ';
//            }
//            echo PHP_EOL;
//
//            echo "ボーナス数字: ";
//            $bonusNumberArray = array();
//            for ($i = 0; $i < $bonusNumCount; $i++) {
//                $strBonusNumEle = $this->elementReplace($i, $this->bonusNumEle);
//                $bonus = $tableObj->find($strBonusNumEle)->text();
//                $bonusNumberArray[] = preg_replace('/[^0-9]/', '', $bonus);
//                echo $bonus .' ';
//            }
//            echo PHP_EOL;
//
//            $lotteryDto = new LotteryResultDto();
//            $lotteryDto->setEventNumber($eventNumber);
//            $lotteryDto->setLotoDate($lotoDate);
//            $lotteryDto->setLotoNumbers(implode(",", $lotoNumberArray));
//            $lotteryDto->setBonusNumbers(implode(",", $bonusNumberArray));
//            $lotteryDto->setLotoType($lotoType);
//
//            $db = $this->getDI()->get('db');
//            try {
//
//                $db->begin();
//
//                $eventNumberRepository = new EventNumberRepository(new EventNumberEntity());
//                $eventNumberRepository->saveEventNumber($lotteryDto);
//
//                $eventNumberResult =$eventNumberRepository->findByEventNumber(
//                    $lotteryDto->getLotoType(), $lotteryDto->getEventNumber());
//
//                if (empty($eventNumberResult)) {
//                    throw new Exception('ERROR: ## Event Number ID 取得失敗 ##');
//                }
//
//                $lotteryResultrepository = new LotteryResultRepository(new LotteryResultEntity());
//                $lotteryResultrepository->saveEventNumber($lotteryDto, $eventNumberResult->id);
//
//                $db->commit();
//            } catch (PDOException $e1) {
//                $db->rollback();
//                echo $e1->getMessage() .PHP_EOL;
//            } catch (Exception $e2) {
//                $db->rollback();
//                echo $e2->getMessage() .PHP_EOL;
//            }
//
//        }
//    }

    public function pastInfoRegistration($lotoType)
    {

        $this->lotoType = $lotoType;
        echo 'タイプ: ' .$this->lotoType .PHP_EOL;

        $lotoConfig  = $this->lotoTypeInfo['config'];
        $configCnt = count($lotoConfig);
        echo 'config cnt:' .$configCnt .PHP_EOL;

        for ($i=0;$i<$configCnt;$i++) {

            $type = $lotoConfig[$i]['type'];
            $scrapingInfo = $lotoConfig[$i]['scraping_info'];
            $this->tableEle     = $scrapingInfo['table_element'];
            $this->eventNumEle  = $scrapingInfo['event_number_element'];
            $this->eventDateEle = $scrapingInfo['event_date_element'];
            $this->lotoNumEle   = $scrapingInfo['loto_number_element'];
            $this->bonusNumEle  = $scrapingInfo['bonus_number_element'];

            if ($type == '1') {
                $this->type1($lotoConfig[$i]);
            } elseif ($type == '2'){
                $this->type2($lotoConfig[$i]);
            } else {
                $this->type3($lotoConfig[$i]);
            }

        }

    }

    private function type1($lotoConfig){

        $urlBase  = $lotoConfig['url'];
        $startNum = $lotoConfig['start_num'];
        $endNum   = $lotoConfig['end_num'];
        $addCnt   = $lotoConfig['add_cnt'];

        for ($j=$startNum; $j<=$endNum; $j=$j+$addCnt) {

            $event_num = sprintf('%03d', $j);
            $url = $this->elementReplace($event_num, $urlBase);
            echo 'url:' .$url .PHP_EOL;
             $this->crawling1($url, $addCnt);
        }
    }

    private function type2($lotoConfig){

        $urlBase  = $lotoConfig['url'];
        $startNum = $lotoConfig['start_num'];
        $endNum   = $lotoConfig['end_num'];
        $addCnt   = $lotoConfig['add_cnt'];

        for ($j=$startNum; $j<=$endNum; $j=$j+$addCnt) {

            $event_num1 = sprintf('%03d', $j);
            if ($j === 1161) {
                $event_num2 = sprintf('%03d', $endNum);
                $addCnt = 18;
            } else {
                $event_num2 = sprintf('%03d', ($j + $addCnt) - 1) ;
            }

            $url = $this->elementReplace2($event_num1, $event_num2, $urlBase);
            echo $url .PHP_EOL;
            $this->crawling1($url, $addCnt);
        }
    }

    private function type3($lotoConfig){

        $urlBase  = $lotoConfig['url'];
        $year = $lotoConfig['start_date']['year'];
        $month = $lotoConfig['start_date']['month'];
        $endDate   = $lotoConfig['end_date']['year'] .$lotoConfig['end_date']['month'];
        //$addCnt   = $lotoConfig['add_cnt'];

        while ($year .$month !== $endDate) {
            $url = $this->elementReplace2($year, $month, $urlBase);
            echo 'url : ' .$url .PHP_EOL;

            $this->crawling2($url);

            if ($month == '12') {
                $year = $year + 1;
                $month = '1';
            } else {
                $month = $month + 1;
            }
        }
    }

    private function crawling1($url, $addCnt){

        $lotoNumCount  = $this->lotoTypeInfo['loto_num'];
        $bonusNumCount = $this->lotoTypeInfo['bonus_num'];
        $getDataCount  = $this->lotoTypeInfo['get_data_count'];

        $HTMLData = shell_exec('casperjs /home/apu/loto/app/js/loto.js "' .$url .'"');
        $phpQueryObj = phpQuery::newDocument($HTMLData);

//echo $phpQueryObj;

        for ($tableCount = 0; $tableCount < $getDataCount; $tableCount++) {

            $strTableEle = $this->elementReplace($tableCount, $this->tableEle);
            $tableObj = $phpQueryObj[$strTableEle];

//echo $tableObj;

            for ($i = 0; $i < $addCnt; $i++) {

                // 回数
                $eventNumberEle = $this->elementReplace($i, $this->eventNumEle);
                $eventNumber = $tableObj->find($eventNumberEle)->text();
                $eventNumber = preg_replace('/[^0-9]/', '', $eventNumber);
                echo '回数: ' .$eventNumber .PHP_EOL;

                // 日付
                $eventDateEle = $this->elementReplace($i, $this->eventDateEle);
                $rowDate = $tableObj->find($eventDateEle)->text();
                $format = 'Y年m月d日';
                $lotoDate = DateTime::createFromFormat($format, $rowDate)->format('Y-m-d') ;
                echo '日付: ' .$lotoDate .PHP_EOL;

                echo "抽せん数字: ";
                $lotoNumberArray = array();
                for ($j = 1; $j < $lotoNumCount + 1; $j++) {
                    $strLotoNumEle = $this->elementReplace2($i, $j, $this->lotoNumEle);
                    $lotoNumber    = $tableObj->find($strLotoNumEle)->text();
                    $lotoNumberArray[] = $lotoNumber;
                    echo $lotoNumber .' ';
                }
                echo PHP_EOL;

                echo "ボーナス数字: ";
                $strBonusNumEle = $this->elementReplace($i, $this->bonusNumEle);
                $bonus = $tableObj->find($strBonusNumEle)->text();
                $bonus = preg_replace('/[^0-9]/', '', $bonus);
                echo $bonus .' ';
//                $bonusNumberArray = array();
//                for ($j = 0; $j < $bonusNumCount; $j++) {
//                    $strBonusNumEle = $this->elementReplace($i, $this->bonusNumEle);
//                    $bonus = $tableObj->find($strBonusNumEle)->text();
//                    $bonusNumberArray[] = preg_replace('/[^0-9]/', '', $bonus);
//                    echo $bonus .' ';
//                }
                echo PHP_EOL;

                $lotteryDto = new LotteryResultDto();
                $lotteryDto->setEventNumber($eventNumber);
                $lotteryDto->setLotoDate($lotoDate);
                $lotteryDto->setLotoNumbers(implode("", $lotoNumberArray));
//                $lotteryDto->setBonusNumbers(implode(",", $bonusNumberArray));
                $lotteryDto->setBonusNumbers($bonus);
                $lotteryDto->setLotoType($this->lotoType);

                $this->insertData($lotteryDto);

            }

        }

    }


    private function crawling2($url){

        $lotoNumCount  = $this->lotoTypeInfo['loto_num'];
        $bonusNumCount = $this->lotoTypeInfo['bonus_num'];
        $getDataCount  = $this->lotoTypeInfo['get_data_count'];

        $HTMLData = shell_exec('casperjs /home/apu/loto/app/js/loto.js "' .$url .'"');
        $phpQueryObj = phpQuery::newDocument($HTMLData);

//echo "phpQueryObj" .PHP_EOL;

        foreach ($phpQueryObj[$this->tableEle] as $row) {

            $eventNumber = pq($row)->find($this->eventNumEle)->text();
            $eventNumber = preg_replace('/[^0-9]/', '', $eventNumber);
            echo '回数: ' .$eventNumber .PHP_EOL;

            $rowDate = pq($row)->find($this->eventDateEle)->text();
            $format = 'Y年m月d日';
            $lotoDate = DateTime::createFromFormat($format, $rowDate)->format('Y-m-d') ;
            //echo '日付: ' .$rowDate .PHP_EOL;
            echo '日付: ' .$lotoDate .PHP_EOL;

            echo "抽せん数字: ";
            $lotoNumberArray = array();
            for ($j = 0; $j < $lotoNumCount + 1; $j++) {
                $strLotoNumEle = $this->elementReplace($j, $this->lotoNumEle);
                $lotoNumber    = pq($row)->find($strLotoNumEle)->text();
                $lotoNumberArray[] = $lotoNumber;
                echo $lotoNumber .' ';
            }
            echo PHP_EOL;

            echo "ボーナス数字: ";
            $bonus = pq($row)->find($this->bonusNumEle)->text();
            $bonus = preg_replace('/[^0-9]/', '', $bonus);
            echo $bonus .' ' .PHP_EOL;

            $lotteryDto = new LotteryResultDto();
            $lotteryDto->setEventNumber($eventNumber);
            $lotteryDto->setLotoDate($lotoDate);
            $lotteryDto->setLotoNumbers(implode("", $lotoNumberArray));
//            $lotteryDto->setBonusNumbers(implode(",", $bonusNumberArray));
            $lotteryDto->setBonusNumbers($bonus);
            $lotteryDto->setLotoType($this->lotoType);

            $this->insertData($lotteryDto);

        }

    }

    private function insertData($lotteryDto){

        $db = $this->getDI()->get('db');
        try {

            $db->begin();

            $lotteryResultRepository = new LotteryResultRepository(new LotteryResultEntity());
            $lotteryResultRepository->saveEventNumber($lotteryDto);

            $db->commit();
        } catch (PDOException $e1) {
            $db->rollback();
            echo $e1->getMessage() .PHP_EOL;
        } catch (Exception $e2) {
            $db->rollback();
            echo $e2->getMessage() .PHP_EOL;
        }
    }

    private function elementReplace($num, $element)
    {
        return str_replace("%num%", $num, $element);
    }

    private function elementReplace2($num1,$num2, $element)
    {
        $ele = str_replace("%num1%", $num1, $element);
        return str_replace("%num2%", $num2, $ele);
    }
}
