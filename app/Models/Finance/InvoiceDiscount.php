<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDiscount extends Model
{
    use HasFactory;

    protected $table = 'finance_invoice_discounts';

    protected $fillable = [
        'invoice_id',
        'discount_id',
        'amount_applied',
    ];

    protected $casts = [
        'amount_applied' => \App\Casts\MoneyCast::class,
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class, 'discount_id');
    }
}
