<?php

class NumberRankingRepository
{

    private $entity;

    private $monthColumns = [
        '01' => 'jan01',
        '02' => 'feb02',
        '03' => 'mar03',
        '04' => 'apr04',
        '05' => 'may05',
        '06' => 'jun06',
        '07' => 'jul07',
        '08' => 'aug08',
        '09' => 'sep09',
        '10' => 'oct10',
        '11' => 'nov11',
        '12' => 'dec12',
    ];

    function __construct(NumberRankingEntity $entity)
    {
        $this->entity = $entity;
    }

    public function findNumberRankingByMonth($lotoType, $date){

        $mon = $this->monthColumns[$date];

        return NumberRankingEntity::find(
            [
                "loto_type_id = :loto_type_id:",
                "bind" => [
                    "loto_type_id" => $lotoType,
                ],
                'columns' => "id, loto_number, $mon as month",
            ]
        );

    }

    public function updateNumberRanking($id, $lotoNum, $data)
    {
        $mon = $this->monthColumns[$data];
        $numRank = NumberRankingEntity::findFirst($id);

        $numRank->save(
            [
                $mon => $lotoNum,
                'update_date' => date("Y-m-d H:i:s"),
            ],
            [
                $mon,
                'update_date'
            ]
        );
    }

}