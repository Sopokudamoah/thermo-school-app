<?php

namespace App\Models\Finance;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scholarship extends Model
{
    use HasFactory;

    protected $table = 'finance_scholarships';

    protected $fillable = [
        'name',
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

    public function student_scholarships()
    {
        return $this->hasMany(StudentScholarship::class, 'scholarship_id');
    }

    public function students()
    {
        return $this->belongsToMany(
            \App\Models\Student::class,
            'finance_student_scholarships',
            'scholarship_id',
            'student_id'
        );
    }
}
