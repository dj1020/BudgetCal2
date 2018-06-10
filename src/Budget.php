<?php

use Carbon\Carbon;

class Budget
{
    private $yearMonth;
    private $amount;

    /**
     * Budget constructor.
     * @param $yearMonth // input format:'YYYYMM'
     * @param int $amount
     */
    public function __construct($yearMonth, $amount)
    {
        $this->yearMonth = Carbon::createFromFormat('Ymd', $yearMonth . '01');
        $this->amount = $amount;
    }

    public function __toString()
    {
        return  (string)$this->yearMonth . ': ' . $this->amount;
    }

    public function amount()
    {
        return $this->amount;
    }

    public function yearMonth()
    {
        return $this->yearMonth;
    }

    /**
     * @return Carbon
     */
    public function firstDay()
    {
        return $this->yearMonth->copy()->firstOfMonth();
    }

    /**
     * @return Carbon
     */
    public function lastDay()
    {
        return $this->yearMonth->copy()->lastOfMonth();
    }

    public function daysInMonth()
    {
        return $this->yearMonth->daysInMonth;
    }

    public function dailyAmount()
    {
        return $this->amount() / $this->daysInMonth();
    }

    public function effectiveAmount(Period $period)
    {
        return $period->overlap($this->period())->days() * $this->dailyAmount();
    }

    private function period()
    {
        return new Period($this->firstDay(), $this->lastDay());
    }
}