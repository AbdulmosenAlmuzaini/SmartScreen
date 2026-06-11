@extends('layouts.admin')

@section('title', __('Control Center'))

@section('content')
<div class="space-y-8">
    
    <!-- Top Greeting Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold tracking-tight glow-text-purple">{{ __('Control Center') }}</h2>
            <p class="text-sm text-gray-400 mt-1 font-light">{{ __('Monitor and manage all public digital signage displays from a single hub.') }}</p>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <!-- Stat Card 1 -->
        <div class="glass-panel p-6 rounded-3xl relative overflow-hidden flex items-center gap-5">
            <div class="w-12 h-12 rounded-2xl bg-purple-500/10 flex items-center justify-center text-purple-400 border border-purple-500/20 shadow-inner">
                <i data-lucide="tv" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">{{ __('Configured Screens') }}</p>
                <h3 class="text-2xl font-bold tracking-tight text-white mt-0.5">{{ $screensCount }}</h3>
            </div>
        </div>

        <!-- Stat Card 2 -->
        <div class="glass-panel p-6 rounded-3xl relative overflow-hidden flex items-center gap-5">
            <div class="w-12 h-12 rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-400 border border-blue-500/20 shadow-inner">
                <i data-lucide="image" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">{{ __('Total Slides Uploaded') }}</p>
                <h3 class="text-2xl font-bold tracking-tight text-white mt-0.5">{{ $slidesCount }}</h3>
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="glass-panel p-6 rounded-3xl relative overflow-hidden flex items-center gap-5">
            <div class="w-12 h-12 rounded-2xl bg-green-500/10 flex items-center justify-center text-green-400 border border-green-500/20 shadow-inner">
                <i data-lucide="power" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">{{ __('Active Display Slides') }}</p>
                <h3 class="text-2xl font-bold tracking-tight text-white mt-0.5">{{ $activeSlidesCount }}</h3>
            </div>
        </div>
    </div>

    <!-- Main Workspace Grid (List on Left, Creator on Right) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Screens List (2 cols on large screen) -->
        <div class="lg:col-span-2 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-200">{{ __('Active Displays') }}</h3>
                <span class="text-xs text-gray-500">{{ $screens->count() }} {{ __('displays active') }}</span>
            </div>

            @if($screens->isEmpty())
                <div class="glass-panel p-12 rounded-3xl text-center flex flex-col items-center justify-center border-dashed border-white/5">
                    <div class="w-16 h-16 rounded-full bg-slate-900 flex items-center justify-center text-gray-600 mb-4 border border-white/5">
                        <i data-lucide="monitor-off" class="w-8 h-8"></i>
                    </div>
                    <h4 class="text-base font-semibold text-gray-300">{{ __('No Screens Configured Yet') }}</h4>
                    <p class="text-xs text-gray-500 max-w-sm mt-1.5 font-light">{{ __('Create your first screen using the configuration panel on the right to start uploading content.') }}</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @foreach($screens as $screen)
                        <div class="glass-card p-6 rounded-3xl flex flex-col justify-between h-56 relative overflow-hidden">
                            <!-- Background Accent -->
                            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-purple-500/5 to-transparent rounded-bl-full pointer-events-none"></div>
                            
                            <div>
                                <div class="flex items-start justify-between">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></div>
                                        <h4 class="font-bold text-gray-100 text-lg leading-tight">{{ $screen->name }}</h4>
                                    </div>
                                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-purple-500/10 text-purple-400 border border-purple-500/20">
                                        {{ $screen->slides_count }} {{ __('Slides') }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 mt-2 font-mono bg-slate-900/40 border border-white/5 rounded-lg py-1 px-2.5 inline-block select-all" dir="ltr">
                                    /screen/{{ $screen->slug }}
                                </p>
                                <p class="text-xs text-gray-400 mt-3.5 line-clamp-2 font-light">
                                    {{ $screen->description ?: __('No description provided.') }}
                                </p>
                            </div>

                            <div class="flex items-center gap-3 mt-6 pt-4 border-t border-white/5">
                                <!-- Launch Live Page -->
                                <a href="{{ route('screen.show', $screen->slug) }}" target="_blank"
                                   class="glass-panel hover:bg-purple-600 hover:text-white px-3 py-2 rounded-xl text-xs font-semibold text-purple-400 border border-purple-500/20 flex items-center justify-center gap-1.5 flex-grow transition duration-200"
                                   title="{{ __('Live View') }}">
                                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                    <span>{{ __('Live View') }}</span>
                                </a>

                                <!-- Manage Content -->
                                <a href="{{ route('screens.slides', $screen->id) }}"
                                   class="bg-white hover:bg-gray-100 text-slate-950 px-3.5 py-2 rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 flex-grow shadow transition duration-200">
                                    <i data-lucide="sliders" class="w-3.5 h-3.5"></i>
                                    <span>{{ __('Manage') }}</span>
                                </a>

                                <!-- Delete Screen -->
                                <form id="delete-screen-form-{{ $screen->id }}" action="{{ route('screens.destroy', $screen->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="triggerConfirm('{{ __('Delete Screen') }}', '{{ __('Are you sure you want to delete the screen ":name" and all its uploaded slides? This cannot be undone.', ['name' => $screen->name]) }}', document.getElementById('delete-screen-form-{{ $screen->id }}'))" 
                                            class="glass-panel hover:bg-red-500/10 text-gray-500 hover:text-red-400 p-2 rounded-xl border border-white/5 transition duration-200" title="{{ __('Delete Screen') }}">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Right: Screen Creator Panel -->
        <div class="space-y-4">
            <h3 class="text-lg font-semibold text-gray-200">{{ __('Add New Display Screen') }}</h3>
            
            <div class="glass-panel p-6 rounded-3xl">
                <form action="{{ route('screens.store') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <div>
                        <label for="name" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">{{ __('Display Name') }}</label>
                        <input type="text" name="name" id="name" required
                            class="w-full px-4 py-3 rounded-xl bg-slate-900/60 border border-white/5 text-sm text-gray-200 focus:outline-none focus:border-purple-500 focus:bg-slate-900/90 transition duration-200"
                            placeholder="{{ __('e.g. Lobby Entrance Screen') }}"
                            oninput="document.getElementById('slug').placeholder = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '')">
                    </div>

                    <div>
                        <label for="slug" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">{{ __('URL Slug (Optional)') }}</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 rtl:left-auto rtl:right-0 pl-3.5 rtl:pl-0 rtl:pr-3.5 flex items-center text-gray-500 text-xs font-mono select-none">
                                /screen/
                            </span>
                            <input type="text" name="slug" id="slug" dir="ltr"
                                class="w-full pl-16 pr-4 rtl:pl-4 rtl:pr-16 py-3 rounded-xl bg-slate-900/60 border border-white/5 text-sm font-mono text-gray-300 focus:outline-none focus:border-purple-500 focus:bg-slate-900/90 transition duration-200"
                                placeholder="{{ __('lobby-entrance') }}">
                        </div>
                        <p class="text-[10px] text-gray-500 mt-1.5 font-light">{{ __('Custom slug used for the browser link. Will be auto-generated from the name if left blank.') }}</p>
                    </div>

                    <div>
                        <label for="description" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">{{ __('Placement / Notes') }}</label>
                        <textarea name="description" id="description" rows="3"
                            class="w-full px-4 py-3 rounded-xl bg-slate-900/60 border border-white/5 text-sm text-gray-200 focus:outline-none focus:border-purple-500 focus:bg-slate-900/90 transition duration-200 resize-none"
                            placeholder="{{ __('e.g. Portrait screen located on the first floor reception lobby hallway.') }}"></textarea>
                    </div>

                    <button type="submit" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-purple-600 to-blue-500 hover:from-purple-500 hover:to-blue-400 font-semibold text-sm text-white shadow-lg shadow-purple-500/10 hover:shadow-purple-500/20 transition duration-200 mt-2 flex items-center justify-center gap-2">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i>
                        <span>{{ __('Register Display Screen') }}</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
