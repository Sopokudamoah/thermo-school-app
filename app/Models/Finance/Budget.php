<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

    protected $table = 'finance_budgets';

    protected $fillable = [
        'name',
        'year',
        'total_allocated',
        'notes',
        'active',
    ];

    protected $casts = [
        'year' => 'integer',
        'total_allocated' => 'decimal:2',
        'active' => 'boolean',
    ];

    public function departments()
    {
        return $this->hasMany(BudgetDepartment::class, 'budget_id');
    }
}
