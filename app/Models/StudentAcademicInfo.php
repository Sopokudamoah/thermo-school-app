<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAcademicInfo extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'student_id',
        'board_reg_no',
    ];

    /**
     * Get the sections for the blog post.
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
