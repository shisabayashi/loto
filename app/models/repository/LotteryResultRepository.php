<?php

class LotteryResultRepository
{
    private $entity;

    function __construct(LotteryResultEntity $enEntity)
    {
        $this->entity = $enEntity;
    }

    public function saveEventNumber(LotteryResultDto $lotteryDto)
    {
        return $this->entity->save(
            [
                "loto_type_id" => $lotteryDto->getLotoType(),
                "event_number" => $lotteryDto->getEventNumber(),
                "loto_date"    => $lotteryDto->getLotoDate(),
                "loto_numbers"    => $lotteryDto->getLotoNumbers(),
                "bonus_numbers"   => $lotteryDto->getBonusNumbers(),
            ]
        );
    }

    public function findMonthlyLotoNumber($lotoType, $date){

        return LotteryResultEntity::find(
            [
                "loto_type_id = :loto_type_id: AND DATE_FORMAT(loto_date, '%Y%m') = :loto_date:",
                "bind" => [
                    "loto_type_id" => $lotoType,
                    "loto_date"    => $date,
                ],
            ]
        );

    }
}
