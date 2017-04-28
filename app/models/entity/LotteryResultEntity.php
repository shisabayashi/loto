<?php

use Phalcon\Mvc\Model;

class LotteryResultEntity extends Model
{

    protected $id;

    protected $event_number_id;

    protected $loto_numbers;

    protected $bonus_numbers;

    protected $update_date;

    protected $insert_date;

    protected $active_flag;

    public function initialize()
    {
        $this->setSource("T_LOTTERY_RESULT");
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getEventNumberId()
    {
        return $this->event_number_id;
    }

    /**
     * @param mixed $event_number_id
     */
    public function setEventNumberId($event_number_id)
    {
        $this->event_number_id = $event_number_id;
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

    /**
     * @return mixed
     */
    public function getBonusNumbers()
    {
        return $this->bonus_numbers;
    }

    /**
     * @param mixed $bonus_numbers
     */
    public function setBonusNumbers($bonus_numbers)
    {
        $this->bonus_numbers = $bonus_numbers;
    }

    /**
     * @return mixed
     */
    public function getUpdateDate()
    {
        return $this->update_date;
    }

    /**
     * @param mixed $update_date
     */
    public function setUpdateDate($update_date)
    {
        $this->update_date = $update_date;
    }

    /**
     * @return mixed
     */
    public function getInsertDate()
    {
        return $this->insert_date;
    }

    /**
     * @param mixed $insert_date
     */
    public function setInsertDate($insert_date)
    {
        $this->insert_date = $insert_date;
    }

    /**
     * @return mixed
     */
    public function getActiveFlag()
    {
        return $this->active_flag;
    }

    /**
     * @param mixed $active_flag
     */
    public function setActiveFlag($active_flag)
    {
        $this->active_flag = $active_flag;
    }

    public function beforeValidationOnCreate()
    {
        $this->update_date = date('Y-m-d H:i:s');
        $this->insert_date = date('Y-m-d H:i:s');
    }
    public function beforeValidationOnUpdate()
    {
        $this->update_date = date('Y-m-d H:i:s');
    }

}
