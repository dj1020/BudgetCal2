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
        /** @var Budget[] $monthBudgets */
        $monthBudgets = $this->transformBudgets($this->model->query($startDate, $endDate));
        $period = new Period($startDate, $endDate);

        $total = 0;
        foreach ($monthBudgets as $budget) {
            $total += $budget->effectiveAmount($period);
        }
        return $total;
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