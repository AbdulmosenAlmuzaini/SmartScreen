<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', __('Admin Dashboard')) - SmartScreen</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Tajawal:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Tailwind Config for Theme Extension -->
    <script>
        const appLocale = "{{ app()->getLocale() }}";
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: [appLocale === 'ar' ? 'Tajawal' : 'Outfit', 'sans-serif'],
                    },
                    colors: {
                        darkBg: '#090d16',
                        darkCard: 'rgba(17, 24, 39, 0.65)',
                        glowPurple: '#a855f7',
                        glowBlue: '#3b82f6',
                    }
                }
            }
        }
    </script>
    
    <!-- Custom Premium Styles -->
    <style>
        body {
            background-color: #060913;
            background-image: 
                radial-gradient(at 10% 20%, rgba(168, 85, 247, 0.08) 0px, transparent 50%),
                radial-gradient(at 90% 80%, rgba(59, 130, 246, 0.08) 0px, transparent 50%);
            background-attachment: fixed;
            color: #f3f4f6;
            overflow-x: hidden;
        }

        .glass-panel {
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .glass-card {
            background: rgba(20, 30, 55, 0.35);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.04);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card:hover {
            border-color: rgba(168, 85, 247, 0.25);
            box-shadow: 0 0 25px rgba(168, 85, 247, 0.1);
            transform: translateY(-2px);
        }

        .glow-text-purple {
            background: linear-gradient(135deg, #f3e8ff, #d8b4fe, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 0 30px rgba(168, 85, 247, 0.2);
        }

        .glow-text-blue {
            background: linear-gradient(135deg, #e0f2fe, #7dd3fc, #38bdf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 0 30px rgba(56, 189, 248, 0.2);
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.1);
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(168, 85, 247, 0.25);
            border-radius: 9999px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(168, 85, 247, 0.4);
        }
    </style>
    @yield('styles')
</head>
<body class="min-h-screen flex flex-col font-sans custom-scrollbar">

    <!-- Glowing Background Orbs -->
    <div class="fixed top-[-10%] left-[-10%] w-[50vw] h-[50vw] rounded-full bg-purple-600/10 blur-[120px] pointer-events-none z-[-1]"></div>
    <div class="fixed bottom-[-10%] right-[-10%] w-[50vw] h-[50vw] rounded-full bg-blue-600/10 blur-[120px] pointer-events-none z-[-1]"></div>

    <!-- Header Navigation -->
    <header class="glass-panel sticky top-0 z-50 px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-purple-600 to-blue-500 flex items-center justify-center shadow-lg shadow-purple-500/20">
                <i data-lucide="monitor-play" class="w-5 h-5 text-white"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold tracking-tight bg-gradient-to-r from-purple-400 to-blue-400 bg-clip-text text-transparent">SmartScreen</h1>
                <p class="text-xs text-gray-500">{{ __('No-JS Digital Signage Platform') }}</p>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <!-- Language Switcher -->
            <div>
                @if(app()->getLocale() == 'ar')
                    <a href="{{ route('lang.switch', 'en') }}" class="glass-card hover:bg-purple-500/10 px-3.5 py-2 rounded-xl text-xs font-semibold text-gray-300 hover:text-purple-400 flex items-center gap-1.5 transition duration-200">
                        <i data-lucide="languages" class="w-3.5 h-3.5"></i>
                        <span>EN</span>
                    </a>
                @else
                    <a href="{{ route('lang.switch', 'ar') }}" class="glass-card hover:bg-purple-500/10 px-3.5 py-2 rounded-xl text-xs font-semibold text-gray-300 hover:text-purple-400 flex items-center gap-1.5 transition duration-200">
                        <i data-lucide="languages" class="w-3.5 h-3.5"></i>
                        <span>عربي</span>
                    </a>
                @endif
            </div>

            @auth
            <div class="text-right rtl:text-left hidden sm:block">
                <p class="text-sm font-medium text-gray-300">{{ Auth::user()->name }}</p>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-2xs font-medium bg-purple-500/10 text-purple-400 border border-purple-500/20">
                    <span class="w-1.5 h-1.5 mr-1 rtl:mr-0 rtl:ml-1 rounded-full bg-purple-400 animate-pulse"></span>
                    {{ __('Administrator') }}
                </span>
            </div>

            <a href="{{ route('profile.edit') }}" class="glass-card hover:bg-purple-500/10 px-4 py-2 rounded-xl text-sm font-medium text-gray-300 hover:text-purple-400 flex items-center gap-2 transition duration-200">
                <i data-lucide="user-cog" class="w-4 h-4"></i>
                <span class="hidden sm:inline">{{ __('Profile Settings') }}</span>
            </a>
            
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="glass-card hover:bg-red-500/10 hover:border-red-500/25 px-4 py-2 rounded-xl text-sm font-medium text-gray-300 hover:text-red-400 flex items-center gap-2 transition duration-200">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                    <span>{{ __('Log Out') }}</span>
                </button>
            </form>
            @endauth
        </div>
    </header>

    <!-- Main Workspace -->
    <div class="flex-grow flex">
        @auth
        <!-- Sidebar -->
        <aside class="w-64 border-r rtl:border-r-0 rtl:border-l border-white/5 bg-slate-950/20 backdrop-blur-md hidden md:flex flex-col justify-between p-6 shrink-0">
            <div class="space-y-6">
                <p class="text-xs font-semibold text-gray-500 tracking-wider uppercase">{{ __('Navigation') }}</p>
                <nav class="space-y-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 {{ Route::is('dashboard') ? 'bg-gradient-to-r from-purple-500/15 to-blue-500/5 text-purple-400 border border-purple-500/10' : 'text-gray-400 hover:text-gray-200 hover:bg-white/5' }}">
                        <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                        <span class="font-medium">{{ __('Overview') }}</span>
                    </a>
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 {{ Route::is('profile.edit') ? 'bg-gradient-to-r from-purple-500/15 to-blue-500/5 text-purple-400 border border-purple-500/10' : 'text-gray-400 hover:text-gray-200 hover:bg-white/5' }}">
                        <i data-lucide="user-cog" class="w-5 h-5"></i>
                        <span class="font-medium">{{ __('Profile Settings') }}</span>
                    </a>
                </nav>
            </div>
            
            <!-- Local Time Indicator -->
            <div class="glass-panel p-4 rounded-2xl border border-white/5 bg-slate-900/40">
                <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                    <i data-lucide="clock" class="w-3.5 h-3.5 text-purple-400 rtl:mr-0 rtl:ml-1"></i>
                    <span>{{ __('Local Time') }}</span>
                </div>
                <div id="local-clock" class="text-lg font-bold font-mono tracking-wider text-purple-200">
                    --:--:--
                </div>
                <div id="local-date" class="text-[10px] text-gray-500 mt-1">
                    -----
                </div>
            </div>
        </aside>
        @endauth

        <!-- Content Area -->
        <main class="flex-grow p-6 sm:p-8 overflow-y-auto">
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400 text-sm flex items-center gap-3 animate-fadeIn">
                    <i data-lucide="check-circle" class="w-5 h-5 shrink-0"></i>
                    <span>{{ __(session('success')) }}</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Custom Confirmation Modal -->
    <div id="custom-confirm-modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeConfirmModal()"></div>
        
        <!-- Card -->
        <div class="glass-panel w-full max-w-md rounded-3xl p-6 relative z-10 shadow-2xl border border-red-500/10 transform scale-95 opacity-0 transition-all duration-300 ease-out" id="confirm-modal-card">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-2xl bg-red-500/10 border border-red-500/25 flex items-center justify-center text-red-500 shrink-0">
                    <i data-lucide="alert-triangle" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-100" id="confirm-modal-title">{{ __('Confirm Action') }}</h3>
                    <p class="text-xs text-gray-400 mt-0.5">{{ __('This action cannot be undone.') }}</p>
                </div>
            </div>
            
            <p class="text-sm text-gray-300 leading-relaxed font-light mb-6" id="confirm-modal-message">
                {{ __('Are you sure you want to proceed?') }}
            </p>
            
            <div class="flex items-center justify-end gap-3">
                <button type="button" onclick="closeConfirmModal()" class="glass-card hover:bg-white/5 px-4.5 py-2.5 rounded-xl text-sm font-semibold text-gray-400 hover:text-gray-200 transition">
                    {{ __('Cancel') }}
                </button>
                <button type="button" id="confirm-modal-btn" class="bg-gradient-to-r from-red-600 to-rose-500 hover:from-red-500 hover:to-rose-400 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-red-500/10 hover:shadow-red-500/20 transition">
                    {{ __('Delete') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Script to Initialize Lucide Icons & Update Server Clock -->
    <script>
        lucide.createIcons();

        // Live clock sync with local browser clock
        const clockEl = document.getElementById('local-clock');
        const dateEl = document.getElementById('local-date');
        if (clockEl) {
            const updateClock = () => {
                const now = new Date();
                const hrs = String(now.getHours()).padStart(2, '0');
                const mins = String(now.getMinutes()).padStart(2, '0');
                const secs = String(now.getSeconds()).padStart(2, '0');
                clockEl.textContent = `${hrs}:${mins}:${secs}`;
                
                const year = now.getFullYear();
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const day = String(now.getDate()).padStart(2, '0');
                if (dateEl) {
                    dateEl.textContent = `${year}-${month}-${day}`;
                }
            };
            updateClock();
            setInterval(updateClock, 1000);
        }

        // Custom Confirm Modal Helpers
        let activeConfirmForm = null;

        window.triggerConfirm = function(title, message, formElement) {
            activeConfirmForm = formElement;
            
            const modal = document.getElementById('custom-confirm-modal');
            const card = document.getElementById('confirm-modal-card');
            
            document.getElementById('confirm-modal-title').textContent = title;
            document.getElementById('confirm-modal-message').textContent = message;
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            setTimeout(() => {
                card.classList.remove('scale-95', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            }, 10);
            
            document.getElementById('confirm-modal-btn').onclick = function() {
                if (activeConfirmForm) {
                    activeConfirmForm.submit();
                }
            };
        };

        window.closeConfirmModal = function() {
            const modal = document.getElementById('custom-confirm-modal');
            const card = document.getElementById('confirm-modal-card');
            
            card.classList.remove('scale-100', 'opacity-100');
            card.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
                activeConfirmForm = null;
            }, 200);
        };
    </script>
    @yield('scripts')
</body>
</html>
