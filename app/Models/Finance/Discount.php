<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $table = 'finance_discounts';

    protected $fillable = [
        'name',
        'code',
        'description',
        'type',
        'value',
        'active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'active' => 'boolean',
    ];

    public const TYPE_FIXED = 'fixed';
    public const TYPE_PERCENTAGE = 'percentage';

    public function invoice_discounts()
    {
        return $this->hasMany(InvoiceDiscount::class, 'discount_id');
    }
}
