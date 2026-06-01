<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_code',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'gender',
        'nationality',
        'address',
        'address2',
        'city',
        'zip',
        'photo',
        'birthday',
        'religion',
        'blood_type',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($student) {
            if (!$student->reference_code) {
                $student->reference_code = static::generateUniqueReferenceCode();
            }
        });
    }

    /**
     * Generate a unique numeric reference code.
     *
     * @return string
     */
    public static function generateUniqueReferenceCode()
    {
        do {
            $code = mt_rand(10000000, 99999999);
        } while (static::where('reference_code', $code)->exists());

        return (string)$code;
    }

    public function parent_info()
    {
        return $this->hasOne(StudentParentInfo::class, 'student_id');
    }

    public function academic_info()
    {
        return $this->hasOne(StudentAcademicInfo::class, 'student_id');
    }

    public function marks()
    {
        return $this->hasMany(Mark::class, 'student_id');
    }

    public function final_marks()
    {
        return $this->hasMany(FinalMark::class, 'student_id');
    }

    public function promotions()
    {
        return $this->hasMany(Promotion::class, 'student_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }
}
