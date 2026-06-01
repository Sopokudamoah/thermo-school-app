<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Document extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'file_path',
        'file_type',
        'file_size',
        'description',
        'documentable_id',
        'documentable_type',
    ];

    /**
     * Get the parent documentable model (user, student, etc.).
     */
    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }
}
