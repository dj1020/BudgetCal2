<?php

use Carbon\Carbon;
use Carbon\CarbonPeriod;

require __DIR__ . '/BudgetModel.php';

class BudgetCalculator
{
    private $model;

    /**
     * BudgetCalculator constructor.
     * @param $model
     */
    public function __construct(BudgetModel $model = null)
    {
        $this->model = $model ?: new BudgetModel();
    }

    public function calculate($startDate, $endDate)
    {
        $monthBudgets = $this->model->query($startDate, $endDate);

        $start = new Carbon($startDate);
        $end = new Carbon($endDate);

        if ($start->isSameMonth($end)) {
            return $monthBudgets[$start->format('Ym')] *
                ($end->diffInDays($start) + 1) / $start->daysInMonth;
        }

        $monthList = $this->getMonthList($start, $end);
        $sum = 0;
        foreach ($monthList as $month) {
            if ($month->isSameMonth($start)) {
                $sum += $monthBudgets[$month->format('Ym')] *
                    ($month->copy()->lastOfMonth()->diffInDays($start) + 1) / $month->daysInMonth;
            } else {
                $sum += $monthBudgets[$month->format('Ym')];
            }
        }

        return $sum;
    }

    /**
     * @param $start
     * @param $end
     * @return Carbon[]
     */
    private function getMonthList($start, $end)
    {
        $period = new CarbonPeriod($start, '1 month', $end);
        $list = [];
        foreach ($period as $month) {
            $list[] = $month;
        }

        return $list;
    }
}