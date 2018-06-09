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

        $monthList = $this->getMonthList($start, $end);
        $sum = 0;
        foreach ($monthList as $month) {
            $periodStart = $month->isSameMonth($start)
                ? $start
                : $month->copy()->firstOfMonth();

            $periodEnd = $month->isSameMonth($end)
                ? $end
                : $month->copy()->lastOfMonth();

            $sum += $this->getPartialBudget($periodStart, $periodEnd, $monthBudgets);
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

    private function getPartialBudget(Carbon $start, Carbon $end, array $budgets)
    {
        return $budgets[$start->format('Ym')] *
            ($end->diffInDays($start) + 1) / $start->daysInMonth;
    }
}