<?php

namespace App\Interfaces\Finance;

interface ExpenseInterface
{
    public function getAll(array $filters = []);

    public function findById($id);

    public function create(array $data);

    public function update($id, array $data);

    public function approve($id, $approved_by);

    public function reject($id, $reason);

    public function getByCategory(array $filters = []);

    public function getSummary(array $filters = []);
}
