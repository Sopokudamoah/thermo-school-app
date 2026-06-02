<?php

namespace App\Interfaces\Finance;

interface PaymentInterface
{
    public function getAll(array $filters = []);

    public function findById($id);

    public function getByStudent($student_id);

    public function create(array $data, array $allocations);

    public function generateReceiptNumber();

    public function getCollectionSummary(array $filters = []);
}
