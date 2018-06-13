<?php

use Phalcon\Mvc\Model;

class LatestEventEntity extends Model
{

    protected $loto_type_id;

    protected $event_number;

    protected $loto_numbers;


    public function initialize()
    {
        $this->setSource("V_LATEST_EVENT");
    }

    /**
     * @return mixed
     */
    public function getLotoTypeId()
    {
        return $this->loto_type_id;
    }

    /**
     * @param mixed $loto_type_id
     */
    public function setLotoTypeId($loto_type_id)
    {
        $this->loto_type_id = $loto_type_id;
    }

    /**
     * @return mixed
     */
    public function getEventNumber()
    {
        return $this->event_number;
    }

    /**
     * @param mixed $event_number
     */
    public function setEventNumber($event_number)
    {
        $this->event_number = $event_number;
    }

    /**
     * @return mixed
     */
    public function getLotoNumbers()
    {
        return $this->loto_numbers;
    }

    /**
     * @param mixed $loto_numbers
     */
    public function setLotoNumbers($loto_numbers)
    {
        $this->loto_numbers = $loto_numbers;
    }

}
