<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $table = 'finance_vendors';

    protected $fillable = [
        'name',
        'contact_person',
        'email',
        'phone',
        'address',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'vendor_id');
    }
}
