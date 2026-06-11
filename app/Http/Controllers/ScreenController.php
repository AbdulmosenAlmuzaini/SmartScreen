<?php

namespace App\Http\Controllers;

use App\Models\Screen;
use App\Models\Slide;
use Illuminate\Http\Request;

class ScreenController extends Controller
{
    /**
     * Display the signage screen slide slideshow.
     */
    public function show(Request $request, string $slug)
    {
        // Find the screen by its unique slug
        $screen = Screen::where('slug', $slug)->firstOrFail();

        // Fetch active and scheduled slides sorted by sort_order then id
        $slides = $screen->slides()
            ->activeAndScheduled()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        // Handle offline / empty state
        if ($slides->isEmpty()) {
            return response()->view('screens.offline', [
                'screen' => $screen,
                'refreshUrl' => route('screen.show', $screen->slug),
                'refreshSeconds' => 10,
            ]);
        }

        // Determine current slide
        $currentSlideId = $request->query('slide');
        $currentSlide = null;

        if ($currentSlideId) {
            $currentSlide = $slides->firstWhere('id', $currentSlideId);
        }

        // Fallback to the first slide if slide ID is invalid or not in active list
        if (!$currentSlide) {
            $currentSlide = $slides->first();
        }

        // Find index of current slide to determine the next one
        $currentIndex = $slides->search(fn ($slide) => $slide->id === $currentSlide->id);
        $nextIndex = ($currentIndex + 1) % $slides->count();
        $nextSlide = $slides->get($nextIndex);

        // Build the next URL for refresh
        $nextUrl = route('screen.show', [
            'slug' => $screen->slug,
            'slide' => $nextSlide->id,
        ]);

        return response()->view('screens.show', [
            'screen' => $screen,
            'currentSlide' => $currentSlide,
            'nextSlide' => $nextSlide,
            'nextUrl' => $nextUrl,
        ]);
    }
}
