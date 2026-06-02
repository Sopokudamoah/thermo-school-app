<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeType extends Model
{
    use HasFactory;

    protected $table = 'finance_fee_types';

    protected $fillable = [
        'name',
        'code',
        'description',
        'recurring',
        'active',
    ];

    protected $casts = [
        'recurring' => 'boolean',
        'active' => 'boolean',
    ];

    public function fee_structure_items()
    {
        return $this->hasMany(FeeStructureItem::class, 'fee_type_id');
    }

    public function invoice_items()
    {
        return $this->hasMany(InvoiceItem::class, 'fee_type_id');
    }
}
