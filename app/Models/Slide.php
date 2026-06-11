<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Slide extends Model
{
    protected $fillable = [
        'screen_id',
        'image_path',
        'caption',
        'duration',
        'sort_order',
        'is_active',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the screen that owns the slide.
     */
    public function screen(): BelongsTo
    {
        return $this->belongsTo(Screen::class);
    }

    /**
     * Scope a query to only include active and currently scheduled slides.
     */
    public function scopeActiveAndScheduled(Builder $query): Builder
    {
        $now = now()->format('H:i:s');

        return $query->where('is_active', true)
            ->where(function (Builder $q) use ($now) {
                // Display if no schedule is set
                $q->whereNull('start_time')
                  ->whereNull('end_time')
                  // Or if schedule matches current time
                  ->orWhere(function (Builder $q2) use ($now) {
                      $q2->whereNotNull('start_time')
                         ->whereNotNull('end_time')
                         ->where(function (Builder $q3) use ($now) {
                             $q3->whereRaw('
                                 CASE 
                                     WHEN start_time <= end_time THEN ? BETWEEN start_time AND end_time
                                     ELSE ? >= start_time OR ? <= end_time
                                 END
                             ', [$now, $now, $now]);
                         });
                  });
            });
    }
}
