<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentAllocation extends Model
{
    use HasFactory;

    protected $table = 'finance_payment_allocations';

    protected $fillable = [
        'payment_id',
        'invoice_id',
        'amount',
    ];

    protected $casts = [
        'amount' => \App\Casts\MoneyCast::class,
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
}
