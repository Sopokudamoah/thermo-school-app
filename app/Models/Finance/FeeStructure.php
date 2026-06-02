<?php

namespace App\Models\Finance;

use App\Models\SchoolClass;
use App\Models\SchoolSession;
use App\Models\Section;
use App\Models\Semester;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeStructure extends Model
{
    use HasFactory;

    protected $table = 'finance_fee_structures';

    protected $fillable = [
        'name',
        'session_id',
        'semester_id',
        'class_id',
        'section_id',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(FeeStructureItem::class, 'fee_structure_id');
    }

    public function session()
    {
        return $this->belongsTo(SchoolSession::class, 'session_id');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }

    public function school_class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
}
