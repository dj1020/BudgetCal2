<?php

class BudgetModel
{

    public function query($startDate, $endDate)
    {
        // data from DB
        return [
             '201801' => 3100,
             '201802' => 5600, // 28*200
             '201804' => 3000,
             '201805' => 3100,
             '202001' => 310,  // 31*10
             '202002' => 8700, // 29*300
        ];
    }
}