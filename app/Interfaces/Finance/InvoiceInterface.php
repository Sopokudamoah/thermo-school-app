<?php

namespace App\Interfaces\Finance;

interface InvoiceInterface
{
    public function getAll(array $filters = []);

    public function findById($id);

    public function findByNumber($invoice_number);

    public function getByStudent($student_id);

    public function create(array $data);

    public function update($id, array $data);

    public function updateStatus($id, $status);

    public function generateInvoiceNumber();

    public function recalculateTotals($id);

    public function getOutstandingByStudent($student_id);

    public function getAgingReport();
}
