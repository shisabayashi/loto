<?php

class LotteryResultDto
{

    protected $lotoType;

    protected $lotoDate;

    protected $eventNumber;

    protected $lotoNumbers;

    protected $bonusNumbers;

    /**
     * @return mixed
     */
    public function getLotoType()
    {
        return $this->lotoType;
    }

    /**
     * @param mixed $lotoType
     */
    public function setLotoType($lotoType)
    {
        $this->lotoType = $lotoType;
    }

    /**
     * @return mixed
     */
    public function getLotoDate()
    {
        return $this->lotoDate;
    }

    /**
     * @param mixed $lotoDate
     */
    public function setLotoDate($lotoDate)
    {
        $this->lotoDate = $lotoDate;
    }

    /**
     * @return mixed
     */
    public function getEventNumber()
    {
        return $this->eventNumber;
    }

    /**
     * @param mixed $eventNumber
     */
    public function setEventNumber($eventNumber)
    {
        $this->eventNumber = $eventNumber;
    }

    /**
     * @return mixed
     */
    public function getLotoNumbers()
    {
        return $this->lotoNumbers;
    }

    /**
     * @param mixed $lotoNumbers
     */
    public function setLotoNumbers($lotoNumbers)
    {
        $this->lotoNumbers = $lotoNumbers;
    }

    /**
     * @return mixed
     */
    public function getBonusNumbers()
    {
        return $this->bonusNumbers;
    }

    /**
     * @param mixed $bonusNumbers
     */
    public function setBonusNumbers($bonusNumbers)
    {
        $this->bonusNumbers = $bonusNumbers;
    }


}
