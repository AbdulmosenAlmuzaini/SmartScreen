<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Sign In') }} - SmartScreen</title>
    
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

        .glow-input:focus {
            border-color: rgba(168, 85, 247, 0.4);
            box-shadow: 0 0 15px rgba(168, 85, 247, 0.15);
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
            <h1 class="text-3xl font-extrabold tracking-tight bg-gradient-to-r from-purple-400 to-blue-400 bg-clip-text text-transparent">SmartScreen</h1>
            <p class="text-sm text-gray-400 mt-1 font-light">{{ __('Signage Slideshow Control Room') }}</p>
        </div>

        <!-- Login Card -->
        <div class="glass-panel p-8 rounded-3xl shadow-2xl">
            <h2 class="text-xl font-semibold mb-2">{{ __('Welcome Back') }}</h2>
            <p class="text-xs text-gray-400 mb-6">{{ __('Enter your administrator credentials to manage screens.') }}</p>

            @if ($errors->any())
                <div class="mb-4 p-3.5 rounded-xl bg-red-500/10 border border-red-500/25 text-red-400 text-xs flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">{{ __('Email Address') }}</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5 flex items-center text-gray-500">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </span>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus dir="ltr"
                            class="glow-input w-full pl-10 pr-4 rtl:pl-4 rtl:pr-10 py-3 rounded-xl bg-slate-900/60 border border-white/5 text-sm text-gray-200 placeholder-gray-500 focus:outline-none focus:bg-slate-900 transition duration-200"
                            placeholder="admin@smartscreen.local">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">{{ __('Password') }}</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5 flex items-center text-gray-500">
                            <i data-lucide="lock" class="w-4 h-4"></i>
                        </span>
                        <input type="password" name="password" id="password" required dir="ltr"
                            class="glow-input w-full pl-10 pr-4 rtl:pl-4 rtl:pr-10 py-3 rounded-xl bg-slate-900/60 border border-white/5 text-sm text-gray-200 placeholder-gray-500 focus:outline-none focus:bg-slate-900 transition duration-200"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 text-sm text-gray-400 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="rounded border-white/5 bg-slate-900/60 text-purple-600 focus:ring-0 focus:ring-offset-0">
                        <span>{{ __('Remember me') }}</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-purple-600 to-blue-500 hover:from-purple-500 hover:to-blue-400 font-semibold text-sm tracking-wide text-white shadow-lg shadow-purple-500/10 hover:shadow-purple-500/20 hover:scale-[1.01] transition duration-200 mt-2 flex items-center justify-center gap-2">
                    <span>{{ __('Sign In') }}</span>
                    <i data-lucide="arrow-right" class="w-4 h-4 rtl:rotate-180"></i>
                </button>
            </form>

            <div class="mt-6 text-center text-xs text-gray-400">
                {{ __("Don't have an account?") }}
                <a href="{{ route('register') }}" class="text-purple-400 hover:text-purple-300 font-medium underline underline-offset-4 ml-1">{{ __('Register Here') }}</a>
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
