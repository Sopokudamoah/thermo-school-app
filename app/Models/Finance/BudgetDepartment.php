<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetDepartment extends Model
{
    use HasFactory;

    protected $table = 'finance_budget_departments';

    protected $fillable = [
        'budget_id',
        'name',
        'allocated',
    ];

    protected $casts = [
        'allocated' => 'decimal:2',
    ];

    public function budget()
    {
        return $this->belongsTo(Budget::class, 'budget_id');
    }

    public function categories()
    {
        return $this->hasMany(BudgetCategory::class, 'budget_department_id');
    }
}
