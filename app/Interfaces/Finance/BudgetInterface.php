<?php

namespace App\Interfaces\Finance;

interface BudgetInterface
{
    public function getAll();

    public function findById($id);

    public function create(array $data);

    public function update($id, array $data);

    public function getVarianceReport($budget_id);
}
