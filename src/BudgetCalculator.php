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

    /**
     * @param $startDate
     * @param $endDate
     * @return float|int
     * @throws \Exception
     */
    public function calculate($startDate, $endDate)
    {
        if (! $this->isValidDates($startDate, $endDate)) {
            throw new Exception('invalid dates');
        }

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

            $sum += $this->getPartialBudget($periodStart, $periodEnd, $monthBudgets);
        }

        return $sum;
    }

    /**
     * @param $start
     * @param $end
     * @return CarbonPeriod
     */
    private function getMonthList(Carbon $start, Carbon $end)
    {
        return new CarbonPeriod(
            $start->copy()->firstOfMonth(),
            '1 month',
            $end->copy()->endOfMonth()
        );
    }

    private function getPartialBudget(Carbon $start, Carbon $end, array $budgets)
    {
        if (! isset($budgets[$start->format('Ym')])) {
            return 0;
        }

        return $budgets[$start->format('Ym')] *
            ($end->diffInDays($start) + 1) / $start->daysInMonth;
    }

    private function isValidDates($startDate, $endDate)
    {
        return (new Carbon($endDate)) >= (new Carbon($startDate));
    }
}