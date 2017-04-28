<?php

class EventNumberRepository
{
    private $entity;

    function __construct(EventNumberEntity $enEntity)
    {
        $this->entity = $enEntity;
    }

    public function saveEventNumber(LotteryResultDto $lotteryDto)
    {
        //$entity = new EventNumber();
        return $this->entity->save(
            [
                "loto_type_id" => $lotteryDto->getLotoType(),
                "event_number" => $lotteryDto->getEventNumber(),
                "loto_date"    => $lotteryDto->getLotoDate(),
            ]
        );
    }

    public function findByEventNumber($lotoType, $eventNumber)
    {
        return EventNumberEntity::findFirst(
            [
                "loto_type_id = :lotoType: AND event_number = :eventNumber:",
                "bind" => [
                    "lotoType"    => $lotoType,
                    "eventNumber" => $eventNumber,
                ],
            ]
        );
    }

}
