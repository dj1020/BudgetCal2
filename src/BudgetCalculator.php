<?php

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
        $monthBudgets = $this->transformBudgets($this->model->query($startDate, $endDate));
        $period = new Period($startDate, $endDate);

        $sum = 0;
        foreach ($period->monthList() as $month) {
            /** @var Budget $budget */
            $budget = $monthBudgets[$month->format('Ym')] ?? null;

            if ( ! is_null($budget)) {
                $sum += $budget->effectiveAmount($period);
            }
        }

        return $sum;
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