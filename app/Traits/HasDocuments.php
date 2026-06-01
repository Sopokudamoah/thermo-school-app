<?php

namespace App\Traits;

use App\Models\Document;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasDocuments
{
    /**
     * Get all of the model's documents.
     */
    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }
}
