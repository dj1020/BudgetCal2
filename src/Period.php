<?php

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class Period
{
    private $startDate;
    private $endDate;

    /**
     * Period constructor.
     * @param $startDate
     * @param $endDate
     */
    public function __construct($startDate, $endDate)
    {
        $this->startDate = new Carbon($startDate);
        $this->endDate = new Carbon($endDate);
    }

    public function monthList()
    {
        return new CarbonPeriod(
            $this->startDate->copy()->firstOfMonth(),
            '1 month',
            $this->endDate->copy()->endOfMonth()
        );
    }

    public function start()
    {
        return $this->startDate;
    }

    public function end()
    {
        return $this->endDate;
    }

    public function days()
    {
        return ($this->endDate->diffInDays($this->startDate) + 1);
    }
}