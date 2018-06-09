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
}
