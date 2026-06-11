<?php

namespace Tests\Feature;

use App\Models\Screen;
use App\Models\Slide;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScreenDisplayTest extends TestCase
{
    use RefreshDatabase;

    public function test_screen_display_shows_offline_template_when_no_slides(): void
    {
        $screen = Screen::create([
            'name' => 'Test Screen',
            'slug' => 'test-screen',
        ]);

        $response = $this->get('/screen/test-screen');

        $response->assertStatus(200);
        $response->assertViewIs('screens.offline');
        $response->assertSee('Screen Idle');
    }

    public function test_screen_display_shows_first_slide_initially_with_meta_refresh_and_preload(): void
    {
        $screen = Screen::create([
            'name' => 'Test Screen',
            'slug' => 'test-screen',
        ]);

        $slide1 = Slide::create([
            'screen_id' => $screen->id,
            'image_path' => 'slides/slide1.png',
            'caption' => 'Slide One Caption',
            'duration' => 5,
            'sort_order' => 10,
            'is_active' => true,
        ]);

        $slide2 = Slide::create([
            'screen_id' => $screen->id,
            'image_path' => 'slides/slide2.png',
            'caption' => 'Slide Two Caption',
            'duration' => 12,
            'sort_order' => 20,
            'is_active' => true,
        ]);

        $response = $this->get('/screen/test-screen');

        $response->assertStatus(200);
        $response->assertViewIs('screens.show');
        $response->assertSee('Slide One Caption');
        
        // Assert meta refresh points to slide 2 with duration of slide 1 (5 seconds)
        $expectedRefresh = 'content="5; url=' . route('screen.show', ['slug' => 'test-screen', 'slide' => $slide2->id]) . '"';
        $response->assertSeeHtml($expectedRefresh);

        // Assert preloading of the next image
        $expectedPreload = 'href="' . asset('storage/slides/slide2.png') . '"';
        $response->assertSeeHtml($expectedPreload);
    }

    public function test_screen_display_loops_back_to_first_slide_when_on_last_slide(): void
    {
        $screen = Screen::create([
            'name' => 'Test Screen',
            'slug' => 'test-screen',
        ]);

        $slide1 = Slide::create([
            'screen_id' => $screen->id,
            'image_path' => 'slides/slide1.png',
            'caption' => 'Slide One',
            'duration' => 5,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $slide2 = Slide::create([
            'screen_id' => $screen->id,
            'image_path' => 'slides/slide2.png',
            'caption' => 'Slide Two',
            'duration' => 8,
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // Request display specifically with slide 2 (last slide)
        $response = $this->get('/screen/test-screen?slide=' . $slide2->id);

        $response->assertStatus(200);
        $response->assertSee('Slide Two');

        // Meta refresh should point back to slide 1 with duration of slide 2 (8 seconds)
        $expectedRefresh = 'content="8; url=' . route('screen.show', ['slug' => 'test-screen', 'slide' => $slide1->id]) . '"';
        $response->assertSeeHtml($expectedRefresh);
        
        // Next preloaded image should be slide 1
        $expectedPreload = 'href="' . asset('storage/slides/slide1.png') . '"';
        $response->assertSeeHtml($expectedPreload);
    }

    public function test_scheduling_filters_active_slides(): void
    {
        $screen = Screen::create([
            'name' => 'Test Screen',
            'slug' => 'test-screen',
        ]);

        // Slide 1 is active all day
        $slide1 = Slide::create([
            'screen_id' => $screen->id,
            'image_path' => 'slides/slide1.png',
            'caption' => 'All Day Slide',
            'is_active' => true,
        ]);

        // Slide 2 is active only from 08:00 to 18:00
        $slide2 = Slide::create([
            'screen_id' => $screen->id,
            'image_path' => 'slides/slide2.png',
            'caption' => 'Day Shift Slide',
            'is_active' => true,
            'start_time' => '08:00:00',
            'end_time' => '18:00:00',
        ]);

        // 1. Mock time inside the schedule: 12:00:00 (midday)
        $this->travelTo(now()->setTime(12, 0, 0));

        $response = $this->get('/screen/test-screen');
        $response->assertSee('All Day Slide');
        
        // Since we are at 12:00, Day Shift Slide is active. Next slide should be Slide 2.
        $expectedRefresh = 'url=' . route('screen.show', ['slug' => 'test-screen', 'slide' => $slide2->id]);
        $response->assertSee($expectedRefresh);

        // 2. Mock time outside the schedule: 22:00:00 (10 PM)
        $this->travelTo(now()->setTime(22, 0, 0));

        $response2 = $this->get('/screen/test-screen');
        $response2->assertSee('All Day Slide');

        // Since we are at 22:00, Day Shift Slide is inactive. There's only 1 active slide (Slide 1).
        // The refresh should loop back to itself (Slide 1).
        $expectedRefreshSelf = 'url=' . route('screen.show', ['slug' => 'test-screen', 'slide' => $slide1->id]);
        $response2->assertSee($expectedRefreshSelf);
    }
}
