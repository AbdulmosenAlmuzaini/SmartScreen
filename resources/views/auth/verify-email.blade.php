<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Verify Email') }} - SmartScreen</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Tajawal:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <script>
        const appLocale = "{{ app()->getLocale() }}";
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: [appLocale === 'ar' ? 'Tajawal' : 'Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <style>
        body {
            background-color: #060913;
            background-image: 
                radial-gradient(at 10% 20%, rgba(168, 85, 247, 0.08) 0px, transparent 50%),
                radial-gradient(at 90% 80%, rgba(59, 130, 246, 0.08) 0px, transparent 50%);
            color: #f3f4f6;
        }

        .glass-panel {
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <!-- Glowing Background Orbs -->
    <div class="fixed top-[20%] left-[20%] w-[35vw] h-[35vw] rounded-full bg-purple-600/10 blur-[120px] pointer-events-none z-[-1]"></div>
    <div class="fixed bottom-[20%] right-[20%] w-[35vw] h-[35vw] rounded-full bg-blue-600/10 blur-[120px] pointer-events-none z-[-1]"></div>

    <div class="w-full max-w-md">
        
        <!-- Brand Logo -->
        <div class="flex flex-col items-center mb-8">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-purple-600 to-blue-500 flex items-center justify-center shadow-xl shadow-purple-500/20 mb-3">
                <i data-lucide="monitor-play" class="w-7 h-7 text-white"></i>
            </div>
            <a href="{{ url('/') }}" class="text-3xl font-extrabold tracking-tight bg-gradient-to-r from-purple-400 to-blue-400 bg-clip-text text-transparent">SmartScreen</a>
            <p class="text-sm text-gray-400 mt-1 font-light">{{ __('Signage Slideshow Control Room') }}</p>
        </div>

        <!-- Verification Notice Card -->
        <div class="glass-panel p-8 rounded-3xl shadow-2xl">
            <h2 class="text-xl font-semibold mb-3 flex items-center gap-2">
                <i data-lucide="mail-check" class="text-purple-400 w-6 h-6"></i>
                <span>{{ __('Verify Email Address') }}</span>
            </h2>
            
            <p class="text-sm text-gray-300 leading-relaxed mb-6 font-light">
                {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
            </p>

            @if (session('message') == 'verification-link-sent')
                <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/25 text-emerald-400 text-xs flex items-start gap-2.5">
                    <i data-lucide="check-circle" class="w-4 h-4 shrink-0 mt-0.5"></i>
                    <span>{{ __('A new verification link has been sent to the email address you provided during registration.') }}</span>
                </div>
            @endif

            <div class="flex flex-col gap-3">
                <!-- Resend Form -->
                <form action="{{ route('verification.send') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-purple-600 to-blue-500 hover:from-purple-500 hover:to-blue-400 font-semibold text-sm tracking-wide text-white shadow-lg shadow-purple-500/10 hover:shadow-purple-500/20 hover:scale-[1.01] transition duration-200 flex items-center justify-center gap-2">
                        <i data-lucide="send" class="w-4 h-4"></i>
                        <span>{{ __('Resend Verification Email') }}</span>
                    </button>
                </form>

                <!-- Logout Form -->
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-3 rounded-xl bg-slate-900/60 hover:bg-slate-900 border border-white/5 hover:border-white/10 font-semibold text-sm text-gray-400 hover:text-gray-200 transition duration-200 flex items-center justify-center gap-2">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        <span>{{ __('Log Out') }}</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Language Switcher below Card -->
        <div class="flex justify-center gap-4 mt-6 text-xs text-gray-400">
            @if(app()->getLocale() == 'ar')
                <a href="{{ route('lang.switch', 'en') }}" class="glass-panel hover:bg-purple-500/10 px-3.5 py-1.5 rounded-xl flex items-center gap-1.5 transition duration-200">
                    <i data-lucide="languages" class="w-3.5 h-3.5"></i>
                    <span>English</span>
                </a>
            @else
                <a href="{{ route('lang.switch', 'ar') }}" class="glass-panel hover:bg-purple-500/10 px-3.5 py-1.5 rounded-xl flex items-center gap-1.5 transition duration-200">
                    <i data-lucide="languages" class="w-3.5 h-3.5"></i>
                    <span>العربية (Arabic)</span>
                </a>
            @endif
        </div>

        <p class="text-center text-xs text-gray-500 mt-6">
            &copy; 2026 SmartScreen. {{ __('All rights reserved.') }}
        </p>

    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
