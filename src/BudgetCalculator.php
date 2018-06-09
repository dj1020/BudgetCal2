<?php

use Carbon\Carbon;

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

        return $monthBudgets[$start->format('Ym')];
    }
}