<?php

namespace App\Http\Controllers;

use App\Models\Screen;
use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    /**
     * Show general dashboard overview.
     */
    public function index()
    {
        $screensCount = Screen::count();
        $slidesCount = Slide::count();
        $activeSlidesCount = Slide::where('is_active', true)->count();
        
        $screens = Screen::withCount('slides')->get();

        return view('dashboard.index', compact(
            'screensCount',
            'slidesCount',
            'activeSlidesCount',
            'screens'
        ));
    }

    /**
     * Store a new Screen.
     */
    public function screenStore(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:screens,slug'],
            'description' => ['nullable', 'string'],
        ]);

        $slug = $validated['slug'] ?? Str::slug($validated['name']);
        
        // Ensure slug is unique, append timestamp if duplicate
        if (Screen::where('slug', $slug)->exists()) {
            $slug = $slug . '-' . time();
        }

        Screen::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'],
        ]);

        return redirect()->route('dashboard')->with('success', __('Screen created successfully.'));
    }

    /**
     * Delete a Screen.
     */
    public function screenDestroy(Screen $screen)
    {
        // Delete all slide images from disk
        foreach ($screen->slides as $slide) {
            Storage::disk('public')->delete($slide->image_path);
        }

        $screen->delete();

        return redirect()->route('dashboard')->with('success', __('Screen and all its slides deleted successfully.'));
    }

    /**
     * Show slides for a specific screen.
     */
    public function slidesIndex(Screen $screen)
    {
        $slides = $screen->slides()->orderBy('sort_order')->orderBy('id')->get();
        return view('dashboard.slides', compact('screen', 'slides'));
    }

    /**
     * Store a new slide for a screen.
     */
    public function slideStore(Request $request, Screen $screen)
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:10240'], // 10MB max
            'caption' => ['nullable', 'string', 'max:255'],
            'duration' => ['required', 'integer', 'min:1'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i'],
        ]);

        // Store file in public storage
        $path = $request->file('image')->store('slides', 'public');

        // Determine next sort order
        $maxOrder = $screen->slides()->max('sort_order') ?? 0;

        Slide::create([
            'screen_id' => $screen->id,
            'image_path' => $path,
            'caption' => $request->caption,
            'duration' => $request->duration,
            'sort_order' => $maxOrder + 1,
            'is_active' => true,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return redirect()->route('screens.slides', $screen->id)
            ->with('success', __('Slide uploaded successfully.'));
    }

    /**
     * Update slide configuration.
     */
    public function slideUpdate(Request $request, Slide $slide)
    {
        $request->validate([
            'caption' => ['nullable', 'string', 'max:255'],
            'duration' => ['required', 'integer', 'min:1'],
            'sort_order' => ['required', 'integer'],
            'start_time' => ['nullable'], // Validate in controller to allow empty
            'end_time' => ['nullable'],
        ]);

        $startTime = $request->start_time ?: null;
        $endTime = $request->end_time ?: null;

        // Ensure time format is H:i or H:i:s
        if ($startTime && strlen($startTime) == 5) {
            $startTime .= ':00';
        }
        if ($endTime && strlen($endTime) == 5) {
            $endTime .= ':00';
        }

        $slide->update([
            'caption' => $request->caption,
            'duration' => $request->duration,
            'sort_order' => $request->sort_order,
            'is_active' => $request->has('is_active'),
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);

        return redirect()->route('screens.slides', $slide->screen_id)
            ->with('success', __('Slide updated successfully.'));
    }

    /**
     * Delete a slide.
     */
    public function slideDestroy(Slide $slide)
    {
        $screenId = $slide->screen_id;
        
        // Delete image from disk
        Storage::disk('public')->delete($slide->image_path);
        
        // Delete from database
        $slide->delete();

        return redirect()->route('screens.slides', $screenId)
            ->with('success', __('Slide deleted successfully.'));
    }

    /**
     * Update slide orders via AJAX.
     */
    public function slideReorder(Request $request, Screen $screen)
    {
        $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer', 'exists:slides,id'],
        ]);

        foreach ($request->order as $index => $slideId) {
            Slide::where('id', $slideId)
                ->where('screen_id', $screen->id)
                ->update(['sort_order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}
