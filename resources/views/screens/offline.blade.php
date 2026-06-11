<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <!-- Meta refresh to retry fetching active slides every 10 seconds -->
    <meta http-equiv="refresh" content="{{ $refreshSeconds }}; url={{ $refreshUrl }}">
    
    <title>{{ __('Screen Offline - SmartScreen') }}</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            width: 100%;
            height: 100%;
            overflow: hidden;
            background-color: #060913;
            font-family: {{ app()->getLocale() == 'ar' ? "'Tajawal', sans-serif" : "'Outfit', sans-serif" }};
            color: #ffffff;
        }

        .viewport {
            width: 100vw;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px;
            text-align: center;
            position: relative;
        }

        /* Ambient Glow Background */
        .glow-orb {
            position: absolute;
            width: 60vw;
            height: 60vw;
            border-radius: 9999px;
            background: radial-gradient(circle, rgba(168, 85, 247, 0.08) 0%, transparent 70%);
            z-index: 1;
            pointer-events: none;
        }

        .card {
            z-index: 2;
            max-width: 400px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        /* Pulsing TV icon */
        .icon-container {
            width: 80px;
            height: 80px;
            border-radius: 24px;
            background: rgba(168, 85, 247, 0.1);
            border: 1px solid rgba(168, 85, 247, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #a855f7;
            animation: pulseIcon 2s infinite ease-in-out;
        }

        @keyframes pulseIcon {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 0 20px rgba(168, 85, 247, 0.1);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 0 30px rgba(168, 85, 247, 0.25);
                border-color: rgba(168, 85, 247, 0.4);
            }
        }

        .offline-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 9999px;
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #ef4444;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #f3f4f6;
            margin-top: 8px;
        }

        .description {
            font-size: 0.85rem;
            color: #9ca3af;
            line-height: 1.5;
            font-weight: 300;
        }

        .loader-dots {
            display: flex;
            gap: 6px;
            margin-top: 10px;
        }

        .dot {
            width: 6px;
            height: 6px;
            background-color: #a855f7;
            border-radius: 50%;
            opacity: 0.3;
            animation: bounceDot 1.4s infinite ease-in-out both;
        }

        .dot:nth-child(1) { animation-delay: -0.32s; }
        .dot:nth-child(2) { animation-delay: -0.16s; }

        @keyframes bounceDot {
            0%, 80%, 100% { transform: scale(0); opacity: 0.3; }
            40% { transform: scale(1); opacity: 1; }
        }

        .footer {
            position: absolute;
            bottom: 40px;
            font-size: 0.75rem;
            color: #4b5563;
            letter-spacing: 0.02em;
        }
    </style>
</head>
<body>

    <div class="viewport">
        <div class="glow-orb"></div>
        
        <div class="card">
            <div class="icon-container">
                <!-- SVG TV Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="7" width="20" height="15" rx="2" ry="2"></rect>
                    <polyline points="17 2 12 7 7 2"></polyline>
                </svg>
            </div>
            
            <div class="offline-badge">
                {{ __('No active slides') }}
            </div>

            <h1 class="title">{{ __('Screen Idle') }}</h1>
            
            <p class="description">
                {{ __('There are no scheduled or active slides assigned to') }} <strong>{{ $screen->name }}</strong> {{ __('at this time. The screen will automatically refresh and start playing as soon as content is configured in the admin dashboard.') }}
            </p>

            <div class="loader-dots">
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
            </div>
        </div>

        <div class="footer" dir="ltr">
            SmartScreen Signage &bull; {{ __('Screen') }}: /screen/{{ $screen->slug }}
        </div>
    </div>

</body>
</html>
