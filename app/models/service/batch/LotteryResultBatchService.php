<?php

use Phalcon\Mvc\Model;

class LotteryResultBatchService extends Model
{
    private $tableEle;
    private $eventNumEle;
    private $eventDateEle;
    private $lotoNumEle;
    private $bonusNumEle;

    public function onConstruct($scrapingInfo)
    {
        $this->tableEle     = $scrapingInfo['table_element'];
        $this->eventNumEle  = $scrapingInfo['event_number_element'];
        $this->eventDateEle = $scrapingInfo['event_date_element'];
        $this->lotoNumEle   = $scrapingInfo['loto_number_element'];
        $this->bonusNumEle  = $scrapingInfo['bonus_number_element'];
    }


    public function lotteryResultRegistration($lotoType, $lotoTypeInfo)
    {

        echo 'タイプ: ' .$lotoType .PHP_EOL;

        $lotoNumCount  = $lotoTypeInfo['loto_num'];
        $bonusNumCount = $lotoTypeInfo['bonus_num'];
        $getDataCount  = $lotoTypeInfo['get_data_count'];

        $HTMLData = file_get_contents($lotoTypeInfo['url']);
        $phpQueryObj = phpQuery::newDocument($HTMLData);

        for ($tableCount = 0; $tableCount < $getDataCount; $tableCount++) {

            $strTableEle = $this->elementReplace($tableCount, $this->tableEle);
            $tableObj = $phpQueryObj[$strTableEle];

            // 回数
            $eventNumber = $tableObj->find($this->eventNumEle)->text();
            $eventNumber = preg_replace('/[^0-9]/', '', $eventNumber);
            echo '回数: ' .$eventNumber .PHP_EOL;

            // 日付
            $rowDate = $tableObj->find($this->eventDateEle)->text();
            $format = 'Y年m月d日';
            $lotoDate = DateTime::createFromFormat($format, $rowDate)->format('Y-m-d') ;
            echo '日付: ' .$lotoDate .PHP_EOL;

            echo "抽せん数字: ";
            $lotoNumberArray = array();
            for ($i = 0; $i < $lotoNumCount; $i++) {
                $strLotoNumEle = $this->elementReplace($i, $this->lotoNumEle);
                $lotoNumber    = $tableObj->find($strLotoNumEle)->text();
                $lotoNumberArray[] = $lotoNumber;
                echo $lotoNumber .' ';
            }
            echo PHP_EOL;

            echo "ボーナス数字: ";
            $bonusNumberArray = array();
            for ($i = 0; $i < $bonusNumCount; $i++) {
                $strBonusNumEle = $this->elementReplace($i, $this->bonusNumEle);
                $bonus = $tableObj->find($strBonusNumEle)->text();
                $bonusNumberArray[] = preg_replace('/[^0-9]/', '', $bonus);
                echo $bonus .' ';
            }
            echo PHP_EOL;

            $lotteryDto = new LotteryResultDto();
            $lotteryDto->setEventNumber($eventNumber);
            $lotteryDto->setLotoDate($lotoDate);
            $lotteryDto->setLotoNumbers(implode(",", $lotoNumberArray));
            $lotteryDto->setBonusNumbers(implode(",", $bonusNumberArray));
            $lotteryDto->setLotoType($lotoType);

            $db = $this->getDI()->get('db');
            try {

                $db->begin();

                $eventNumberRepository = new EventNumberRepository(new EventNumberEntity());
                $eventNumberRepository->saveEventNumber($lotteryDto);

                $eventNumberResult =$eventNumberRepository->findByEventNumber(
                    $lotteryDto->getLotoType(), $lotteryDto->getEventNumber());

                if (empty($eventNumberResult)) {
                    throw new Exception('ERROR: ## Event Number ID 取得失敗 ##');
                }

                $lotteryResultrepository = new LotteryResultRepository(new LotteryResultEntity());
                $lotteryResultrepository->saveEventNumber($lotteryDto, $eventNumberResult->id);

                $db->commit();
            } catch (PDOException $e1) {
                $db->rollback();
                echo $e1->getMessage() .PHP_EOL;
            } catch (Exception $e2) {
                $db->rollback();
                echo $e2->getMessage() .PHP_EOL;
            }
        }
    }

    private function elementReplace($num, $element)
    {
        return str_replace("%num%", $num, $element);
    }

}
