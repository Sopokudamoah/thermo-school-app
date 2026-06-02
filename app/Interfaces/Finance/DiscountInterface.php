<?php

namespace App\Interfaces\Finance;

interface DiscountInterface
{
    public function getAll();

    public function getActive();

    public function findById($id);

    public function create(array $data);

    public function update($id, array $data);

    public function applyToInvoice($invoice_id, $discount_id);

    public function removeFromInvoice($invoice_id, $discount_id);
}
