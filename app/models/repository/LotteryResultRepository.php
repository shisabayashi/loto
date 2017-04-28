<?php

class LotteryResultRepository
{
    private $entity;

    function __construct(LotteryResultEntity $enEntity)
    {
        $this->entity = $enEntity;
    }

    public function saveEventNumber(LotteryResultDto $lotteryDto, $eventNumberId)
    {
        return $this->entity->save(
            [
                "event_number_id" => $eventNumberId,
                "loto_numbers"    => $lotteryDto->getLotoNumbers(),
                "bonus_numbers"   => $lotteryDto->getBonusNumbers(),
            ]
        );
    }
}
