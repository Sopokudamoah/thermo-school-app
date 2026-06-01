<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_type',
        'marks_submission_status',
        'active_semester_id',
        'active_session_id',
        'school_name',
        'school_address',
        'school_phone',
        'school_email'
    ];
}
