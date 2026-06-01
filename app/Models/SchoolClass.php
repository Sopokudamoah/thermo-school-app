<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    use HasFactory;

    protected $fillable = ['class_name', 'session_id'];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('teacher_assigned_classes', function (Builder $builder) {
            if (auth()->check() && auth()->user()->isTeacher()) {
                $builder->whereIn('id', function ($query) {
                    $query->select('class_id')
                        ->from('assigned_teachers')
                        ->where('teacher_id', auth()->id());
                });
            }
        });
    }

    /**
     * Get the sections for the class.
     */
    public function sections()
    {
        return $this->hasMany(Section::class, 'class_id', 'id');
    }

    /**
     * Get the courses for the class.
     */
    public function courses()
    {
        return $this->hasMany(Course::class, 'class_id', 'id');
    }

    /**
     * Get the syllabi for the class.
     */
    public function syllabi()
    {
        return $this->hasMany(Syllabus::class, 'class_id', 'id');
    }
}
