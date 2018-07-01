<?php

use Phalcon\Mvc\Model;

class LotteryResultBatchService2 extends Model
{

    public function updateLotoNumberRanking($lotoType){

        echo 'updateLotoNumberRanking !!' .PHP_EOL;

        $date = date("Ym", strtotime("-1 month"));
        $monthStr = substr($date, -2);

        $rankingArray = [];
        $numberRankingRepository = new NumberRankingRepository(new NumberRankingEntity());
        $result = $numberRankingRepository->findNumberRankingByMonth($lotoType, $monthStr);

        foreach ($result as $val) {
            $rankingArray[$val->loto_number]['id'] = $val->id;
            $rankingArray[$val->loto_number]['num'] = (int)$val->month;
        }

        $numberList = [];
        $lotteryResultRepository = new LotteryResultRepository(new LotteryResultEntity());
        $result = $lotteryResultRepository->findMonthlyLotoNumber($lotoType, $date);
        foreach ($result as $loto) {

            $numberArray = array_map('intval', str_split($loto->loto_numbers, 2));
            $numberList = array_merge($numberList, $numberArray);
        }

        foreach ($numberList as $number) {
            $rankingArray[$number]['num']++;
            $numberRankingRepository->updateNumberRanking($rankingArray[$number]['id'], $rankingArray[$number]['num'], $monthStr);
        }
    }


    public function updateOldLotoNumberRanking($lotoType){

       $yearArray = [
            '1999',
            '2000',
            '2001',
            '2002',
            '2003',
            '2004',
            '2005',
            '2006',
            '2007',
            '2008',
            '2009',
            '2010',
            '2011',
            '2012',
            '2013',
            '2014',
            '2015',
            '2016',
            '2017',
            '2018',
        ];

        $monthArray = [
            '01',
            '02',
            '03',
            '04',
            '05',
            '06',
            '07',
            '08',
            '09',
            '10',
            '11',
            '12',
        ];

        foreach ($monthArray as $monthStr) {

            $rankingArray = [];
            $numberRankingRepository = new NumberRankingRepository(new NumberRankingEntity());
            $result = $numberRankingRepository->findNumberRankingByMonth($lotoType, $monthStr);

            foreach ($result as $val) {
                $rankingArray[$val->loto_number]['id'] = $val->id;
                $rankingArray[$val->loto_number]['num'] = (int)$val->month;
            }

            $numberList = [];
            $lotteryResultRepository = new LotteryResultRepository(new LotteryResultEntity());
            foreach ($yearArray as $yearStr) {

                $result = $lotteryResultRepository->findMonthlyLotoNumber($lotoType, $yearStr .$monthStr);
                foreach ($result as $loto) {
//                    echo "loto_number:" .$loto->loto_numbers .PHP_EOL;
                    $numberArray = array_map('intval', str_split($loto->loto_numbers, 2));
                    $numberList = array_merge($numberList, $numberArray);
                }
            }

            foreach ($numberList as $number) {
                $rankingArray[$number]['num']++;
                $numberRankingRepository->updateNumberRanking($rankingArray[$number]['id'], $rankingArray[$number]['num'], $monthStr);
            }

        }
    }
}
