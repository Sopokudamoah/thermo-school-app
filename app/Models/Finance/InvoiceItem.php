<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $table = 'finance_invoice_items';

    protected $fillable = [
        'invoice_id',
        'fee_type_id',
        'description',
        'amount',
    ];

    protected $casts = [
        'amount' => \App\Casts\MoneyCast::class,
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function fee_type()
    {
        return $this->belongsTo(FeeType::class, 'fee_type_id');
    }
}
