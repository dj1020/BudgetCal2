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

        if ( ! $this->isValidPeriod()) {
            throw new Exception('invalid period');
        }
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

    /**
     * @param Period $otherPeriod
     * @return integer
     */
    public function overlapDays(Period $otherPeriod)
    {
        $overlapStart = $this->start() > $otherPeriod->start()
            ? $this->start()->copy()
            : $otherPeriod->start()->copy();

        $overlapEnd = $this->end() < $otherPeriod->end()
            ? $this->end()->copy()
            : $otherPeriod->end()->copy();

        return ($overlapEnd >= $overlapStart)
            ? $overlapEnd->diffInDays($overlapStart) + 1
            : 0;
    }

    private function isValidPeriod()
    {
        return $this->end() >= $this->start();
    }
}