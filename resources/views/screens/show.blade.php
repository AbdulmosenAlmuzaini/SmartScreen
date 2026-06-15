<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    @php
        $ext = pathinfo($currentSlide->image_path, PATHINFO_EXTENSION);
        $isVideo = in_array(strtolower($ext), ['mp4', 'webm', 'ogg', 'avi', 'mov']);

        $nextExt = pathinfo($nextSlide->image_path, PATHINFO_EXTENSION);
        $nextIsVideo = in_array(strtolower($nextExt), ['mp4', 'webm', 'ogg', 'avi', 'mov']);
    @endphp

    @if(!$isVideo)
        <!-- Meta refresh to trigger full page redirect after current slide duration -->
        <meta http-equiv="refresh" content="{{ $currentSlide->duration }}; url={{ $nextUrl }}">
    @endif
    
    <title>{{ $screen->name }} - SmartScreen</title>
    
    <!-- Preload next slide image to cache it in browser for instant display on reload -->
    @if(!$nextIsVideo)
        <link rel="preload" as="image" href="{{ asset('storage/' . $nextSlide->image_path) }}">
    @endif
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Tajawal:wght@400;700;900&display=swap" rel="stylesheet">
    
    <style>
        /* Reset and enforce canvas-like environment */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body, html {
            width: 100%;
            height: 100%;
            overflow: hidden;
            background-color: #000000;
            font-family: {{ app()->getLocale() == 'ar' ? "'Tajawal', sans-serif" : "'Outfit', sans-serif" }};
        }

        /* Portrait Signage viewport sizing */
        .viewport {
            width: 100vw;
            height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            background: #000000;
        }

        /* Slide image cover style */
        .slide-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            animation: slideFadeIn 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
        }

        /* Elegant fade-in animation on page refresh */
        @keyframes slideFadeIn {
            from {
                opacity: 0;
                transform: scale(1.02);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Semi-transparent text overlay */
        .caption-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 100px 40px 60px 40px; /* Tall padding to create nice gradient fade */
            background: linear-gradient(to top, 
                rgba(0, 0, 0, 0.9) 0%, 
                rgba(0, 0, 0, 0.6) 60%, 
                rgba(0, 0, 0, 0.2) 90%, 
                transparent 100%);
            color: #ffffff;
            display: flex;
            flex-direction: column;
            gap: 12px;
            pointer-events: none; /* Make overlay non-interactive */
            transform: translateY(20px);
            animation: textSlideUp 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94) 0.1s forwards;
        }

        @keyframes textSlideUp {
            to {
                transform: translateY(0);
            }
        }

        .caption-title {
            font-size: 2.75rem;
            font-weight: 700;
            line-height: 1.15;
            letter-spacing: -0.02em;
            text-shadow: 0 4px 12px rgba(0,0,0,0.5);
            max-width: 90%;
        }

        /* Status & Screen stamp */
        .screen-badge {
            position: absolute;
            top: 30px;
            right: 30px;
            padding: 8px 16px;
            border-radius: 9999px;
            background: rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            pointer-events: none;
        }
    </style>
</head>
<body>

    <div class="viewport">
        <!-- Display current slide (Image or Video) -->
        @if($isVideo)
            <video src="{{ asset('storage/' . $currentSlide->image_path) }}" class="slide-img" autoplay muted playsinline></video>
        @else
            <img src="{{ asset('storage/' . $currentSlide->image_path) }}" alt="{{ $currentSlide->caption }}" class="slide-img">
        @endif

        <!-- Display Screen Badge -->
        <div class="screen-badge">
            {{ $screen->name }}
        </div>

        <!-- Render text overlay if caption exists -->
        @if($currentSlide->caption)
            <div class="caption-overlay">
                <h2 class="caption-title">{{ $currentSlide->caption }}</h2>
            </div>
        @endif
    </div>

    @if($isVideo)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const video = document.querySelector('video.slide-img');
            if (video) {
                // When video ends, redirect to next slide
                video.addEventListener('ended', function() {
                    window.location.href = "{{ $nextUrl }}";
                });
                
                // Fallback: redirect after max of 5 minutes or configured duration if video freezes
                const fallbackDuration = {{ max($currentSlide->duration, 300) }} * 1000;
                setTimeout(function() {
                    window.location.href = "{{ $nextUrl }}";
                }, fallbackDuration);
            }
        });
    </script>
    @endif

</body>
</html>
