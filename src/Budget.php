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


}