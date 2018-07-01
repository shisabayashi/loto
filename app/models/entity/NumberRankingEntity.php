<?php

use Phalcon\Mvc\Model;

class NumberRankingEntity extends Model
{

    protected $id;

    protected $loto_type_id;

    protected $loto_number;

    protected $jan01;

    protected $feb02;

    protected $mar03;

    protected $apr04;

    protected $may05;

    protected $jun06;

    protected $jul07;

    protected $aug08;

    protected $sep09;

    protected $oct10;

    protected $nov11;

    protected $dec12;

    protected $update_date;

    protected $insert_date;

    protected $active_flag;

    public function initialize()
    {
        $this->setSource("T_NUMBER_RANKING");
        $this->useDynamicUpdate(true);
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
    public function getLotoNumber()
    {
        return $this->loto_number;
    }

    /**
     * @param mixed $loto_number
     */
    public function setLotoNumber($loto_number)
    {
        $this->loto_number = $loto_number;
    }

    /**
     * @return mixed
     */
    public function getJan01()
    {
        return $this->jan01;
    }

    /**
     * @param mixed $jan01
     */
    public function setJan01($jan01)
    {
        $this->jan01 = $jan01;
    }

    /**
     * @return mixed
     */
    public function getFeb02()
    {
        return $this->feb02;
    }

    /**
     * @param mixed $feb02
     */
    public function setFeb02($feb02)
    {
        $this->feb02 = $feb02;
    }

    /**
     * @return mixed
     */
    public function getMar03()
    {
        return $this->mar03;
    }

    /**
     * @param mixed $mar03
     */
    public function setMar03($mar03)
    {
        $this->mar03 = $mar03;
    }

    /**
     * @return mixed
     */
    public function getApr04()
    {
        return $this->apr04;
    }

    /**
     * @param mixed $apr04
     */
    public function setApr04($apr04)
    {
        $this->apr04 = $apr04;
    }

    /**
     * @return mixed
     */
    public function getMay05()
    {
        return $this->may05;
    }

    /**
     * @param mixed $may05
     */
    public function setMay05($may05)
    {
        $this->may05 = $may05;
    }

    /**
     * @return mixed
     */
    public function getJun06()
    {
        return $this->jun06;
    }

    /**
     * @param mixed $jun06
     */
    public function setJun06($jun06)
    {
        $this->jun06 = $jun06;
    }

    /**
     * @return mixed
     */
    public function getJul07()
    {
        return $this->jul07;
    }

    /**
     * @param mixed $jul07
     */
    public function setJul07($jul07)
    {
        $this->jul07 = $jul07;
    }

    /**
     * @return mixed
     */
    public function getAug08()
    {
        return $this->aug08;
    }

    /**
     * @param mixed $aug08
     */
    public function setAug08($aug08)
    {
        $this->aug08 = $aug08;
    }

    /**
     * @return mixed
     */
    public function getSep09()
    {
        return $this->sep09;
    }

    /**
     * @param mixed $sep09
     */
    public function setSep09($sep09)
    {
        $this->sep09 = $sep09;
    }

    /**
     * @return mixed
     */
    public function getOct10()
    {
        return $this->oct10;
    }

    /**
     * @param mixed $oct10
     */
    public function setOct10($oct10)
    {
        $this->oct10 = $oct10;
    }

    /**
     * @return mixed
     */
    public function getNov11()
    {
        return $this->nov11;
    }

    /**
     * @param mixed $nov11
     */
    public function setNov11($nov11)
    {
        $this->nov11 = $nov11;
    }

    /**
     * @return mixed
     */
    public function getDec12()
    {
        return $this->dec12;
    }

    /**
     * @param mixed $dec12
     */
    public function setDec12($dec12)
    {
        $this->dec12 = $dec12;
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

}
