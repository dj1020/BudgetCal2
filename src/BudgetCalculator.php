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

        list($start, $end) = [new Carbon($startDate), new Carbon($endDate)];

        $monthList = $this->getMonthList($start, $end);
        $sum = 0;
        foreach ($monthList as $month) {
            $periodStart = $month->isSameMonth($start, true)
                ? $start
                : $month->copy()->firstOfMonth();

            $periodEnd = $month->isSameMonth($end, true)
                ? $end
                : $month->copy()->lastOfMonth();

            $partial = $this->getPartialBudget($periodStart, $periodEnd, $monthBudgets);
//            printf('%s:%s  %d' . PHP_EOL,
//                (string)$periodStart->format('Y-m-d'),
//                (string)$periodEnd->format('Y-m-d'),
//                $partial
//            );
            $sum += $partial;
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
        if (! isset($budgets[$start->format('Ym')])) {
            return 0;
        }

        return $budgets[$start->format('Ym')] *
            ($end->diffInDays($start) + 1) / $start->daysInMonth;
    }
}