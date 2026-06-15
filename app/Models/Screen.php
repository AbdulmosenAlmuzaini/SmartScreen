<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Screen extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
    ];

    /**
     * Get the user that owns the screen.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the slides for the screen.
     */
    public function slides(): HasMany
    {
        return $this->hasMany(Slide::class);
    }
}
