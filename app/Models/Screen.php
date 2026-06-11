<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Screen extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * Get the slides for the screen.
     */
    public function slides(): HasMany
    {
        return $this->hasMany(Slide::class);
    }
}
