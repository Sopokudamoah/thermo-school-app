<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeStructureItem extends Model
{
    use HasFactory;

    protected $table = 'finance_fee_structure_items';

    protected $fillable = [
        'fee_structure_id',
        'fee_type_id',
        'amount',
    ];

    protected $casts = [
        'amount' => \App\Casts\MoneyCast::class,
    ];

    public function fee_structure()
    {
        return $this->belongsTo(FeeStructure::class, 'fee_structure_id');
    }

    public function fee_type()
    {
        return $this->belongsTo(FeeType::class, 'fee_type_id');
    }
}
