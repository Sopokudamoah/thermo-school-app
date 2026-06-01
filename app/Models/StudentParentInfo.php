<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentParentInfo extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'student_id',
        'father_name',
        'father_phone',
        'mother_name',
        'mother_phone',
        'parent_address',
    ];

    /**
     * Get the sections for the blog post.
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
