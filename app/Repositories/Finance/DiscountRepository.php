<?php

namespace App\Repositories\Finance;

use App\Interfaces\Finance\DiscountInterface;
use App\Models\Finance\Discount;
use App\Models\Finance\InvoiceDiscount;

class DiscountRepository implements DiscountInterface
{
    public function getAll()
    {
        return Discount::orderBy('name')->get();
    }

    public function getActive()
    {
        return Discount::where('active', true)->orderBy('name')->get();
    }

    public function findById($id)
    {
        return Discount::findOrFail($id);
    }

    public function create(array $data)
    {
        return Discount::create($data);
    }

    public function update($id, array $data)
    {
        $discount = Discount::findOrFail($id);
        $discount->update($data);
        return $discount;
    }

    public function applyToInvoice($invoice_id, $discount_id)
    {
        $discount = Discount::findOrFail($discount_id);
        $invoice = \App\Models\Finance\Invoice::with('items')->findOrFail($invoice_id);

        $subtotalMoney = $invoice->items->reduce(function ($carry, $item) {
            return $carry->add($item->amount);
        }, \App\Helpers\MoneyHelper::zero());

        if ($discount->type === Discount::TYPE_PERCENTAGE) {
            $amount_applied = $subtotalMoney->multiply($discount->value / 100);
        } else {
            $amount_applied = new \Money\Money(
                (string)round($discount->value * 100),
                \App\Helpers\MoneyHelper::getCurrency()
            );
        }

        return InvoiceDiscount::create([
            'invoice_id' => $invoice_id,
            'discount_id' => $discount_id,
            'amount_applied' => $amount_applied,
        ]);
    }

    public function removeFromInvoice($invoice_id, $discount_id)
    {
        return InvoiceDiscount::where('invoice_id', $invoice_id)
            ->where('discount_id', $discount_id)
            ->delete();
    }
}
