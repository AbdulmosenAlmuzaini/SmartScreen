<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Screen;
use App\Models\Slide;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Administrator User
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@smartscreen.local',
            'password' => Hash::make('password'),
        ]);

        // 2. Create Default Signage Screen
        $screen = Screen::create([
            'name' => 'Lobby Entrance Screen',
            'slug' => 'lobby',
            'description' => 'Vertical display screen located at the main reception lobby entrance.',
        ]);

        // 3. Create Default Slide 1 (Welcome Screen)
        Slide::create([
            'screen_id' => $screen->id,
            'image_path' => 'slides/welcome.png',
            'caption' => 'Welcome to our Corporate Headquarters',
            'duration' => 8,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // 4. Create Default Slide 2 (Directory Screen)
        Slide::create([
            'screen_id' => $screen->id,
            'image_path' => 'slides/directory.png',
            'caption' => 'Office Directory & Wayfinding Map',
            'duration' => 8,
            'sort_order' => 2,
            'is_active' => true,
        ]);
    }
}
