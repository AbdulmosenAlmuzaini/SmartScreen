<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SmartScreen - {{ __('Digital Signage Platform') }}</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Tajawal:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    
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
            background-color: #030712;
            background-image: 
                radial-gradient(at 0% 0%, rgba(168, 85, 247, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(59, 130, 246, 0.15) 0px, transparent 50%);
            background-attachment: fixed;
            color: #f3f4f6;
            overflow-x: hidden;
        }

        .glass-panel {
            background: rgba(17, 24, 39, 0.45);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .glass-card {
            background: rgba(17, 24, 39, 0.25);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.03);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card:hover {
            border-color: rgba(168, 85, 247, 0.25);
            box-shadow: 0 0 30px rgba(168, 85, 247, 0.08);
            transform: translateY(-2px);
        }

        .glow-text {
            background: linear-gradient(135deg, #f3e8ff, #c084fc, #60a5fa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-between font-sans antialiased selection:bg-purple-500/30 selection:text-white">

    <!-- Ambient Glowing Orbs -->
    <div class="fixed top-[-10%] left-[-10%] w-[60vw] h-[60vw] rounded-full bg-purple-600/10 blur-[130px] pointer-events-none z-[-1]"></div>
    <div class="fixed bottom-[-10%] right-[-10%] w-[60vw] h-[60vw] rounded-full bg-blue-600/10 blur-[130px] pointer-events-none z-[-1]"></div>

    <!-- Header / Navbar -->
    <nav class="glass-panel sticky top-0 z-50 px-6 py-4 mx-4 my-4 rounded-2xl flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-purple-600 to-blue-500 flex items-center justify-center shadow-lg shadow-purple-500/20">
                <i data-lucide="monitor-play" class="w-5 h-5 text-white"></i>
            </div>
            <div>
                <span class="text-xl font-bold tracking-tight bg-gradient-to-r from-purple-400 to-blue-400 bg-clip-text text-transparent">SmartScreen</span>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <!-- Language Switcher -->
            @if(app()->getLocale() == 'ar')
                <a href="{{ route('lang.switch', 'en') }}" class="glass-card hover:bg-purple-500/10 px-3.5 py-1.5 rounded-xl text-xs font-semibold text-gray-300 hover:text-purple-400 flex items-center gap-1.5 transition duration-200">
                    <i data-lucide="languages" class="w-3.5 h-3.5"></i>
                    <span>English</span>
                </a>
            @else
                <a href="{{ route('lang.switch', 'ar') }}" class="glass-card hover:bg-purple-500/10 px-3.5 py-1.5 rounded-xl text-xs font-semibold text-gray-300 hover:text-purple-400 flex items-center gap-1.5 transition duration-200">
                    <i data-lucide="languages" class="w-3.5 h-3.5"></i>
                    <span>العربية</span>
                </a>
            @endif

            <!-- Auth Actions -->
            @if (Route::has('login'))
                @auth
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-xl bg-gradient-to-r from-purple-600 to-blue-500 hover:from-purple-500 hover:to-blue-400 text-xs font-semibold text-white transition duration-200 flex items-center gap-1.5">
                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                        <span>{{ __('Admin Dashboard') }}</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="glass-card hover:bg-white/5 px-4.5 py-2 rounded-xl text-xs font-semibold text-gray-300 hover:text-white transition duration-200">
                        {{ __('Log In') }}
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-4.5 py-2 rounded-xl bg-gradient-to-r from-purple-600 to-blue-500 hover:from-purple-500 hover:to-blue-400 text-xs font-semibold text-white shadow-lg shadow-purple-500/10 hover:shadow-purple-500/20 transition duration-200">
                            {{ __('Register') }}
                        </a>
                    @endif
                @endauth
            @endif
        </div>
    </nav>

    <!-- Main Content -->
    <main class="w-full max-w-7xl mx-auto px-6 py-12 space-y-24">
        
        <!-- Hero Section -->
        <section class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-7 space-y-6">
                <!-- Platform badge -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-purple-500/10 border border-purple-500/25 text-purple-400 text-xs font-medium">
                    <span class="w-1.5 h-1.5 rounded-full bg-purple-400 animate-pulse"></span>
                    @if(app()->getLocale() == 'ar')
                        <span>الجيل الجديد من اللوحات الرقمية الذكية</span>
                    @else
                        <span>Next-Gen Digital Signage Solution</span>
                    @endif
                </div>

                <!-- Main Headline -->
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-[1.15] text-gray-100">
                    @if(app()->getLocale() == 'ar')
                        تحكم في شاشاتك الرقمية <br>
                        <span class="glow-text">بسهولة وبثوانٍ معدودة</span>
                    @else
                        Control your digital screens <br>
                        <span class="glow-text">effortlessly in seconds</span>
                    @endif
                </h1>

                <!-- Subheadline -->
                <p class="text-base sm:text-lg text-gray-400 leading-relaxed font-light">
                    @if(app()->getLocale() == 'ar')
                        منصة SmartScreen تتيح لك رفع وإدارة وترتيب شرائح وعروض اللوحات الإعلانية الرقمية الطولية وتحديثها فورياً على أي شاشة تلفزيون ذكية دون الحاجة لتثبيت برمجيات معقدة.
                    @else
                        SmartScreen allows you to upload, schedule, and organize portrait digital displays instantly. Rendered beautifully on any smart TV browser without complex apps.
                    @endif
                </p>

                <!-- Action Button -->
                <div class="flex flex-wrap gap-4 pt-2">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-7 py-4 rounded-xl bg-gradient-to-r from-purple-600 to-blue-500 hover:from-purple-500 hover:to-blue-400 font-bold text-sm text-white shadow-xl shadow-purple-500/10 hover:shadow-purple-500/20 hover:scale-[1.02] transition duration-200 flex items-center gap-2">
                            <span>{{ __('Admin Dashboard') }}</span>
                            <i data-lucide="arrow-right" class="w-4 h-4 rtl:rotate-180"></i>
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="px-7 py-4 rounded-xl bg-gradient-to-r from-purple-600 to-blue-500 hover:from-purple-500 hover:to-blue-400 font-bold text-sm text-white shadow-xl shadow-purple-500/10 hover:shadow-purple-500/20 hover:scale-[1.02] transition duration-200 flex items-center gap-2">
                            <span>{{ __('Register Here') }}</span>
                            <i data-lucide="user-plus" class="w-4 h-4"></i>
                        </a>
                        <a href="{{ route('login') }}" class="glass-card hover:bg-white/5 px-7 py-4 rounded-xl font-bold text-sm text-gray-300 hover:text-white transition duration-200 flex items-center gap-2">
                            <span>{{ __('Log In') }}</span>
                            <i data-lucide="log-in" class="w-4 h-4"></i>
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Visual Portrait TV Mockup -->
            <div class="lg:col-span-5 flex justify-center">
                <div class="relative w-72 h-[480px] bg-slate-950 rounded-[32px] p-3 shadow-2xl border-4 border-slate-800 shadow-purple-500/5 ring-1 ring-white/10 flex flex-col justify-between">
                    <!-- Screen Camera notch mockup -->
                    <div class="absolute top-2 left-1/2 -translate-x-1/2 w-16 h-3 bg-slate-800 rounded-full z-10 flex items-center justify-center">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                    </div>

                    <!-- Inner Signage Content Mockup (Vibrant display slide) -->
                    <div class="w-full h-full rounded-[20px] overflow-hidden bg-[#0a0f1d] border border-white/5 relative flex flex-col justify-between p-6">
                        <!-- Background glow effect in mobile screen -->
                        <div class="absolute inset-0 bg-gradient-to-b from-purple-500/10 to-blue-500/10"></div>
                        <div class="absolute top-[30%] left-[-20%] w-60 h-60 rounded-full bg-purple-500/20 blur-[60px] pointer-events-none"></div>

                        <!-- Top logo -->
                        <div class="relative z-10 flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-gradient-to-tr from-purple-600 to-blue-500 flex items-center justify-center">
                                <i data-lucide="monitor-play" class="w-4 h-4 text-white"></i>
                            </div>
                            <span class="text-xs font-bold text-gray-200">SmartScreen</span>
                        </div>

                        <!-- Middle banner slide -->
                        <div class="relative z-10 space-y-3 my-auto text-center">
                            <span class="px-2 py-0.5 rounded bg-emerald-500/15 border border-emerald-500/20 text-emerald-400 text-[9px] font-bold tracking-wider uppercase">Live rotation</span>
                            <h3 class="text-lg font-bold text-gray-100">
                                @if(app()->getLocale() == 'ar')
                                    عروض اليوم الطازجة
                                @else
                                    Fresh Daily Offers
                                @endif
                            </h3>
                            <p class="text-[10px] text-gray-400 font-light">
                                @if(app()->getLocale() == 'ar')
                                    تحديثات فورية مباشرة من لوحة التحكم بكل يسر.
                                @else
                                    Real-time slides automatically fetched and displayed.
                                @endif
                            </p>
                        </div>

                        <!-- Bottom slide info -->
                        <div class="relative z-10 flex justify-between items-center text-[9px] text-gray-500 border-t border-white/5 pt-3">
                            <span>Slide 1 of 3</span>
                            <span class="flex items-center gap-1 text-purple-400">
                                <span class="w-1.5 h-1.5 rounded-full bg-purple-400 animate-ping"></span>
                                Active
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Platform Vision / Summary Section -->
        <section class="glass-panel p-8 sm:p-12 rounded-3xl relative overflow-hidden">
            <div class="absolute top-[50%] left-[-20%] w-96 h-96 rounded-full bg-purple-600/5 blur-[120px] pointer-events-none"></div>
            
            <div class="max-w-3xl space-y-6">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-100 flex items-center gap-3">
                    <i data-lucide="lightbulb" class="text-purple-400 w-7 h-7"></i>
                    @if(app()->getLocale() == 'ar')
                        <span>رؤية المنصة وأهدافها</span>
                    @else
                        <span>Platform Vision & Goals</span>
                    @endif
                </h2>
                
                <div class="text-gray-300 leading-relaxed font-light space-y-4 text-sm sm:text-base">
                    @if(app()->getLocale() == 'ar')
                        <p>
                            تم إنشاء <strong>SmartScreen</strong> لحل مشكلة يواجهها مئات أصحاب الأعمال والمحلات التجارية والمؤسسات: صعوبة وتكلفة عرض الإعلانات وقوائم الخدمات (Menus) والتعليمات على الشاشات المعلقة.
                        </p>
                        <p>
                            نهدف إلى تقديم حل **للجميع** بدون تعقيد؛ حيث لا يحتاج المستخدم إلى شراء خوادم باهظة الثمن أو تنزيل برامج تشغيل خاصة بالهواتف والتلفزيونات. بدلاً من ذلك، تعتمد الشاشات على صفحة ويب خفيفة الوزن يتم معالجتها بالكامل من جهة الخادم (Server-Side Rendering)، مما يضمن تشغيل العروض واستمراريتها لـ 24 ساعة يومياً وبشكل مستقر تماماً.
                        </p>
                    @else
                        <p>
                            <strong>SmartScreen</strong> was built to solve a problem faced by hundreds of business owners, restaurants, and corporate environments: the high cost and complexity of displaying advertisements, menu boards, and directions on wall-mounted screens.
                        </p>
                        <p>
                            Our goal is to provide an accessible platform for **everyone**. Users don't need to buy expensive dedicated hardware or install specialized player apps. Instead, displays run on a lightweight web page fully rendered on the server (SSR), guaranteeing 24/7 stability, extreme performance, and instant updates.
                        </p>
                    @endif
                </div>
            </div>
        </section>

        <!-- Platform Features Grid -->
        <section class="space-y-12">
            <div class="text-center space-y-3">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-100">
                    @if(app()->getLocale() == 'ar')
                        مميزات المنصة
                    @else
                        Platform Features
                    @endif
                </h2>
                <p class="text-xs sm:text-sm text-gray-400 max-w-xl mx-auto font-light leading-relaxed">
                    @if(app()->getLocale() == 'ar')
                        كل الأدوات اللازمة لإدارة وعرض محتواك بأعلى كفاءة وأقل متطلبات.
                    @else
                        All the features you need to manage and display your content with zero friction.
                    @endif
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <!-- Feature 1: No-JS Display -->
                <div class="glass-card p-8 rounded-3xl space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400">
                        <i data-lucide="zap" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-200">
                        @if(app()->getLocale() == 'ar')
                            عرض بدون جافا سكريبت (No-JS)
                        @else
                            Zero JS Display
                        @endif
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-400 leading-relaxed font-light">
                        @if(app()->getLocale() == 'ar')
                            شاشات العرض تعمل بالكامل بدون جافا سكريبت على أجهزة التلفزيون. يتم إجراء التحركات وتغيير الصور بسلاسة فائقة باستخدام حركات انتقالية مدعومة بـ CSS فقط، مما يمنع تجمد الشاشة أو بطئها.
                        @else
                            The public signage view runs completely without Javascript execution on target TVs. Smooth transitions are handled purely by CSS, preventing crashes or memory leaks.
                        @endif
                    </p>
                </div>

                <!-- Feature 2: Precise Scheduling -->
                <div class="glass-card p-8 rounded-3xl space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400">
                        <i data-lucide="calendar" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-200">
                        @if(app()->getLocale() == 'ar')
                            جدولة ساعات النشاط
                        @else
                            Active Hours Scheduling
                        @endif
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-400 leading-relaxed font-light">
                        @if(app()->getLocale() == 'ar')
                            حدد أوقات تشغيل محددة لكل شريحة (ساعة البدء وساعة الانتهاء)، وسيتم عرض الإعلانات المحددة تلقائياً وإخفاؤها خلال الساعات الخارجة عن الوقت المعين لتبسيط إدارة العروض اليومية.
                        @else
                            Set precise start and end times for each slide. The platform will automatically calculate active slide hours and swap slides without manual intervention.
                        @endif
                    </p>
                </div>

                <!-- Feature 3: Visual Playlist ordering -->
                <div class="glass-card p-8 rounded-3xl space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400">
                        <i data-lucide="grip-vertical" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-200">
                        @if(app()->getLocale() == 'ar')
                            ترتيب مرئي بالسحب والإفلات
                        @else
                            Visual Drag & Drop
                        @endif
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-400 leading-relaxed font-light">
                        @if(app()->getLocale() == 'ar')
                            أعد ترتيب شرائحك وصورك بكل سهولة ومرونة باستخدام واجهة سحب وإسقاط بديهية ومطورة في لوحة التحكم، مما يمنحك سرعة مطلقة في إعادة توجيه تركيز عملائك.
                        @else
                            Reorder your signage slides sequence smoothly using our intuitive visual drag and drop builder. Changes reflect instantly on all linked live displays.
                        @endif
                    </p>
                </div>

                <!-- Feature 4: Easy Onboarding -->
                <div class="glass-card p-8 rounded-3xl space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-pink-500/10 border border-pink-500/20 flex items-center justify-center text-pink-400">
                        <i data-lucide="tv" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-200">
                        @if(app()->getLocale() == 'ar')
                            ربط الشاشات بخطوة واحدة
                        @else
                            One-Step Screen Link
                        @endif
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-400 leading-relaxed font-light">
                        @if(app()->getLocale() == 'ar')
                            كل شاشة تنشئها تمتلك رابطاً فريداً ومميزاً. كل ما عليك فعله هو إدخال هذا الرابط في متصفح أي تلفزيون ذكي لتبدأ الشاشة فوراً بعرض المحتوى المخصص لها تلقائياً.
                        @else
                            Each screen generates a clean public URL. Simply open this URL in your smart TV or monitor's browser and your slide loop will start playing automatically.
                        @endif
                    </p>
                </div>

                <!-- Feature 5: Security first -->
                <div class="glass-card p-8 rounded-3xl space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400">
                        <i data-lucide="shield-check" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-200">
                        @if(app()->getLocale() == 'ar')
                            أمان وحماية متكاملة
                        @else
                            Verified Security
                        @endif
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-400 leading-relaxed font-light">
                        @if(app()->getLocale() == 'ar')
                            حماية بياناتك وإعلاناتك من خلال تشفير كلمات المرور والتحقق الإلزامي للبريد الإلكتروني للحسابات الجديدة وتدابير الحماية لحساب المسؤول في لوحة التحكم.
                        @else
                            Full security for accounts via modern password hashing, email verification, throttled authentication routes, and user profile management controls.
                        @endif
                    </p>
                </div>

                <!-- Feature 6: Responsive & Global -->
                <div class="glass-card p-8 rounded-3xl space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400">
                        <i data-lucide="globe" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-200">
                        @if(app()->getLocale() == 'ar')
                            دعم كامل ثنائي اللغة
                        @else
                            Full Bilingual Support
                        @endif
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-400 leading-relaxed font-light">
                        @if(app()->getLocale() == 'ar')
                            تنقل في لوحة التحكم أو واجهة العرض بكل سلاسة باللغتين العربية والإنجليزية، بتوزيع واتجاهات نص متوافقة ومصممة بأرقى الخطوط وتوزيع المساحات.
                        @else
                            Toggle seamlessly between English (LTR) and Arabic (RTL) views, with pixel-perfect alignment and beautiful typography customized for each language.
                        @endif
                    </p>
                </div>

            </div>
        </section>

        <!-- Final CTA (Call to Action) Section -->
        <section class="glass-panel p-8 sm:p-16 rounded-3xl text-center space-y-6 relative overflow-hidden">
            <!-- Background orb -->
            <div class="absolute inset-0 bg-gradient-to-tr from-purple-500/5 to-blue-500/5 pointer-events-none"></div>
            
            <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-100 max-w-xl mx-auto leading-tight">
                @if(app()->getLocale() == 'ar')
                    ابدأ بتهيئة شاشاتك الذكية اليوم
                @else
                    Start configuring your screens today
                @endif
            </h2>
            <p class="text-xs sm:text-sm text-gray-400 max-w-md mx-auto font-light leading-relaxed">
                @if(app()->getLocale() == 'ar')
                    سجل حسابك مجاناً وباشر تنظيم لوحاتك الإعلانية وعروضك الرقمية في أقل من دقيقة.
                @else
                    Create your free account and launch your digital slideshow rotation in under a minute.
                @endif
            </p>
            <div class="flex justify-center gap-4 pt-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-7 py-4 rounded-xl bg-gradient-to-r from-purple-600 to-blue-500 hover:from-purple-500 hover:to-blue-400 font-bold text-sm text-white shadow-xl shadow-purple-500/10 hover:shadow-purple-500/20 hover:scale-[1.02] transition duration-200 flex items-center gap-2">
                        <span>{{ __('Admin Dashboard') }}</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 rtl:rotate-180"></i>
                    </a>
                @else
                    <a href="{{ route('register') }}" class="px-7 py-4 rounded-xl bg-gradient-to-r from-purple-600 to-blue-500 hover:from-purple-500 hover:to-blue-400 font-bold text-sm text-white shadow-xl shadow-purple-500/10 hover:shadow-purple-500/20 hover:scale-[1.02] transition duration-200 flex items-center gap-2">
                        <span>{{ __('Create Account') }}</span>
                        <i data-lucide="user-plus" class="w-4 h-4"></i>
                    </a>
                @endauth
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="glass-panel mx-4 my-4 rounded-2xl p-6 text-center space-y-4">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 max-w-7xl mx-auto">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-gradient-to-tr from-purple-600 to-blue-500 flex items-center justify-center">
                    <i data-lucide="monitor-play" class="w-4.5 h-4.5 text-white"></i>
                </div>
                <span class="text-sm font-bold text-gray-200">SmartScreen</span>
            </div>
            
            <p class="text-xs text-gray-500 font-light">
                &copy; 2026 SmartScreen. {{ __('All rights reserved.') }}
            </p>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
