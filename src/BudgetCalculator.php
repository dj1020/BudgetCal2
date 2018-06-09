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
        $end = new Carbon($endDate);

        if ($start->isSameMonth($end)) {
            return $monthBudgets[$start->format('Ym')] *
                ($end->diffInDays($start) + 1) / $start->daysInMonth;
        }

        return $monthBudgets[$start->format('Ym')];
    }
}