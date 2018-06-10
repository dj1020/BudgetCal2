<?php

use Carbon\Carbon;
use Carbon\CarbonPeriod;

require __DIR__ . '/BudgetModel.php';
require __DIR__ . '/Budget.php';
require __DIR__ . '/Period.php';

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
        if ( ! $this->isValidDates($startDate, $endDate)) {
            throw new Exception('invalid dates');
        }

        $monthBudgets = $this->transformBudgets($this->model->query($startDate, $endDate));
        $period = new Period($startDate, $endDate);

        $sum = 0;
        foreach ($period->monthList() as $month) {
            /** @var Budget $budget */
            $budget = $monthBudgets[$month->format('Ym')] ?? null;

            if ( ! is_null($budget)) {
                $overlapStart = $period->start()->isSameMonth($budget->yearMonth(), true)
                    ? $period->start()
                    : $budget->firstDay();

                $overlapEnd = $period->end()->isSameMonth($budget->yearMonth(), true)
                    ? $period->end()
                    : $budget->lastDay();

                $effectiveAmount = $this->effectiveAmount(new Period($overlapStart, $overlapEnd), $budget);

                $sum += $effectiveAmount;
            }
        }

        return $sum;
    }

    private function effectiveAmount(Period $period, Budget $budget)
    {
        return $budget->amount() * $period->days() / $budget->daysInMonth();
    }

    private function isValidDates($startDate, $endDate)
    {
        return (new Carbon($endDate)) >= (new Carbon($startDate));
    }

    private function transformBudgets(array $monthBudgets)
    {
        $budgets = [];
        foreach ($monthBudgets as $yearMonth => $amount) {
            $budgets[$yearMonth] = new Budget($yearMonth, $amount);
        }

        return $budgets;
    }
}