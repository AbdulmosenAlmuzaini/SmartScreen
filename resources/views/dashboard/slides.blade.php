@extends('layouts.admin')

@section('title', 'Manage Slides - ' . $screen->name)

@section('styles')
<style>
    .draggable-slide {
        transition: transform 0.2s ease, opacity 0.2s ease;
    }
    .draggable-slide.dragging {
        opacity: 0.4;
        transform: scale(0.98);
        border-color: rgba(168, 85, 247, 0.4);
    }
    .screen-preview-container {
        width: 320px;
        height: 568px; /* 9:16 aspect ratio scaled */
        border: 12px solid #111827;
        border-radius: 28px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), 0 0 40px rgba(168, 85, 247, 0.1);
        position: relative;
    }
    .screen-preview-container::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 50%;
        transform: translateX(-50%);
        width: 40px;
        height: 4px;
        background: #374151;
        border-radius: 999px;
    }
</style>
@endsection

@section('content')
<div class="space-y-8">
    
    <!-- Top breadcrumb and actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-white/5 pb-6">
        <div class="space-y-1">
            <div class="flex items-center gap-2 text-xs text-gray-500 font-medium">
                <a href="{{ route('dashboard') }}" class="hover:text-purple-400 transition">{{ __('Control Center') }}</a>
                <span>/</span>
                <span class="text-gray-300">{{ __('Manage Slides') }}</span>
            </div>
            <h2 class="text-3xl font-extrabold tracking-tight text-white flex items-center gap-3">
                <span>{{ $screen->name }}</span>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    {{ __('Online') }}
                </span>
            </h2>
            <p class="text-xs text-gray-400 font-mono select-all">
                {{ __('Link:') }} <a href="{{ route('screen.show', $screen->slug) }}" target="_blank" class="text-purple-400 hover:underline">{{ route('screen.show', $screen->slug) }}</a>
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('screen.show', $screen->slug) }}" target="_blank" class="glass-panel hover:bg-purple-600 hover:text-white px-4 py-2.5 rounded-xl text-sm font-semibold text-purple-400 border border-purple-500/20 flex items-center gap-2 transition duration-200">
                <i data-lucide="external-link" class="w-4 h-4"></i>
                <span>{{ __('Open Screen View') }}</span>
            </a>
            <a href="{{ route('dashboard') }}" class="glass-panel px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-400 hover:text-gray-200 border border-white/5 flex items-center gap-2 transition duration-200">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>{{ __('Back') }}</span>
            </a>
        </div>
    </div>

    <!-- Main Content Area: Forms, Lists and Iframe Live Monitor -->
    <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
        
        <!-- Left: Upload Form (1 Column) -->
        <div class="xl:col-span-1 space-y-4">
            <h3 class="text-lg font-semibold text-gray-200">{{ __('Upload Slide') }}</h3>
            
            <div class="glass-panel p-5 rounded-3xl">
                <form action="{{ route('slides.store', $screen->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    
                    <!-- File input -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">{{ __('Image or Video File') }}</label>
                        <div class="relative border border-white/5 hover:border-purple-500/30 rounded-xl p-4 bg-slate-900/40 text-center cursor-pointer transition">
                            <input type="file" name="image" id="image" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewUploadImage(this)">
                            <div class="space-y-1">
                                <i data-lucide="upload-cloud" class="w-8 h-8 text-purple-400 mx-auto"></i>
                                <p class="text-xs font-medium text-gray-300" id="upload-placeholder">{{ __('Choose image/video or drag here') }}</p>
                                <p class="text-[10px] text-gray-500 font-light">{{ __('Images or Videos up to 100MB') }}</p>
                            </div>
                            <!-- Image/Video preview containers -->
                            <img id="upload-preview" class="hidden mt-3 max-h-36 mx-auto rounded-lg object-contain border border-white/5" />
                            <video id="upload-preview-video" class="hidden mt-3 max-h-36 mx-auto rounded-lg object-contain border border-white/5" muted playsinline autoplay></video>
                        </div>
                    </div>

                    <!-- Caption -->
                    <div>
                        <label for="caption" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">{{ __('Caption / Title (Optional)') }}</label>
                        <input type="text" name="caption" id="caption"
                            class="w-full px-4.5 py-2.5 rounded-xl bg-slate-900/60 border border-white/5 text-sm text-gray-200 focus:outline-none focus:border-purple-500 focus:bg-slate-900 transition"
                            placeholder="{{ __('e.g. Welcome Guests') }}">
                    </div>

                    <!-- Duration -->
                    <div>
                        <label for="duration" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">{{ __('Duration (Seconds)') }}</label>
                        <div class="relative">
                            <input type="number" name="duration" id="duration" value="8" min="1" required
                                class="w-full px-4.5 py-2.5 rounded-xl bg-slate-900/60 border border-white/5 text-sm text-gray-200 focus:outline-none focus:border-purple-500 focus:bg-slate-900 transition">
                            <span class="absolute inset-y-0 right-0 pr-4.5 flex items-center text-xs text-gray-500 pointer-events-none">{{ __('sec') }}</span>
                        </div>
                    </div>

                    <!-- Scheduling time fields -->
                    <div class="grid grid-cols-2 gap-3 border-t border-white/5 pt-4">
                        <div>
                            <label for="start_time" class="block text-2xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">{{ __('Start Time') }}</label>
                            <input type="time" name="start_time" id="start_time"
                                class="w-full px-3 py-2 rounded-xl bg-slate-900/60 border border-white/5 text-xs text-gray-300 focus:outline-none focus:border-purple-500 focus:bg-slate-900 transition">
                        </div>
                        <div>
                            <label for="end_time" class="block text-2xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">{{ __('End Time') }}</label>
                            <input type="time" name="end_time" id="end_time"
                                class="w-full px-3 py-2 rounded-xl bg-slate-900/60 border border-white/5 text-xs text-gray-300 focus:outline-none focus:border-purple-500 focus:bg-slate-900 transition">
                        </div>
                    </div>
                    <p class="text-[10px] text-gray-500 font-light mt-1">{{ __('Leave empty to display 24/7. Use H:i format to schedule slide active hours.') }}</p>

                    <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-purple-600 to-blue-500 hover:from-purple-500 hover:to-blue-400 font-semibold text-sm text-white shadow shadow-purple-500/10 hover:shadow-purple-500/20 transition duration-200 flex items-center justify-center gap-2 mt-2">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        <span>{{ __('Add to Playlist') }}</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Middle: Slides Playlist Management (2 Columns) -->
        <div class="xl:col-span-2 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-200">{{ __('Slides Playlist') }}</h3>
                <span class="text-xs text-gray-500">{{ __('Drag slides to reorder') }}</span>
            </div>

            @if($slides->isEmpty())
                <div class="glass-panel p-16 rounded-3xl text-center flex flex-col items-center justify-center border-dashed border-white/5">
                    <div class="w-16 h-16 rounded-full bg-slate-900/60 flex items-center justify-center text-gray-600 mb-4 border border-white/5">
                        <i data-lucide="image-off" class="w-8 h-8"></i>
                    </div>
                    <h4 class="text-base font-semibold text-gray-300">{{ __('Playlist is Empty') }}</h4>
                    <p class="text-xs text-gray-500 max-w-sm mt-1.5 font-light">{{ __('Upload slides using the left panel. They will immediately show up in the signage display page rotation.') }}</p>
                </div>
            @else
                <div id="slides-container" class="space-y-4" ondragover="allowDrop(event)">
                    @foreach($slides as $slide)
                        <div class="glass-card p-4.5 rounded-3xl flex flex-col sm:flex-row gap-4 items-start sm:items-center relative draggable-slide"
                             draggable="true" 
                             data-id="{{ $slide->id }}"
                             ondragstart="drag(event)" 
                             ondragend="dragEnd(event)">
                            
                            <!-- Drag handle -->
                            <div class="cursor-grab text-gray-600 hover:text-purple-400 p-1 rounded transition hidden sm:block shrink-0" title="{{ __('Drag slides to reorder') }}">
                                <i data-lucide="grip-vertical" class="w-5 h-5"></i>
                            </div>

                            <!-- Slide thumbnail -->
                            <div class="w-20 h-28 rounded-xl overflow-hidden shrink-0 border border-white/5 bg-slate-900/40 relative font-sans">
                                @php
                                    $ext = pathinfo($slide->image_path, PATHINFO_EXTENSION);
                                    $isVideo = in_array(strtolower($ext), ['mp4', 'webm', 'ogg', 'avi', 'mov']);
                                @endphp
                                @if($isVideo)
                                    <video src="{{ asset('storage/' . $slide->image_path) }}" class="w-full h-full object-cover" muted playsinline></video>
                                    <span class="absolute top-1 right-1 p-1 bg-black/60 rounded-full text-white">
                                        <i data-lucide="video" class="w-3.5 h-3.5"></i>
                                    </span>
                                @else
                                    <img src="{{ asset('storage/' . $slide->image_path) }}" alt="{{ $slide->caption }}" class="w-full h-full object-cover">
                                @endif
                                <span class="absolute bottom-1 left-1 px-1.5 py-0.5 rounded text-[10px] font-bold bg-slate-950/70 text-gray-300" dir="ltr">
                                    #{{ $slide->sort_order }}
                                </span>
                            </div>

                            <!-- Edit Form (Covers layout, submits to slideUpdate) -->
                            <form action="{{ route('slides.update', $slide->id) }}" method="POST" class="flex-grow grid grid-cols-1 sm:grid-cols-2 gap-3.5 w-full">
                                @csrf
                                @method('PUT')
                                
                                <div class="space-y-3">
                                    <!-- Title/Caption -->
                                    <div>
                                        <input type="text" name="caption" value="{{ $slide->caption }}" 
                                            class="w-full px-3 py-1.5 rounded-lg bg-slate-900/40 border border-white/5 text-xs text-gray-200 focus:outline-none focus:border-purple-500 focus:bg-slate-900"
                                            placeholder="{{ __('Caption / Title (Optional)') }}">
                                    </div>
                                    
                                    <!-- Schedule Details Info -->
                                    <div class="flex items-center gap-2">
                                        <label class="flex items-center gap-1.5 text-xs text-gray-400 cursor-pointer">
                                            <input type="checkbox" name="is_active" {{ $slide->is_active ? 'checked' : '' }} class="rounded border-white/5 bg-slate-900 text-purple-600 focus:ring-0">
                                            <span>{{ __('Active Rotation') }}</span>
                                        </label>
                                        @if($slide->start_time && $slide->end_time)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] bg-purple-500/10 text-purple-400 border border-purple-500/20 font-medium font-sans" dir="ltr">
                                                <i data-lucide="clock" class="w-3 h-3 mr-1 rtl:mr-0 rtl:ml-1"></i>
                                                {{ substr($slide->start_time, 0, 5) }} - {{ substr($slide->end_time, 0, 5) }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] bg-slate-500/10 text-gray-400 border border-white/5 font-medium">
                                                <i data-lucide="calendar" class="w-3 h-3 mr-1 rtl:mr-0 rtl:ml-1"></i>
                                                {{ __('All Day') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <!-- Duration & Order Inputs -->
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label class="block text-[9px] text-gray-500 uppercase font-semibold mb-1">{{ __('Duration') }}</label>
                                            <input type="number" name="duration" value="{{ $slide->duration }}" min="1" required
                                                class="w-full px-2 py-1 rounded bg-slate-900/40 border border-white/5 text-xs text-center text-gray-200 focus:outline-none focus:border-purple-500 focus:bg-slate-900">
                                        </div>
                                        <div>
                                            <label class="block text-[9px] text-gray-500 uppercase font-semibold mb-1">{{ __('Sort Order') }}</label>
                                            <input type="number" name="sort_order" value="{{ $slide->sort_order }}" required
                                                class="w-full px-2 py-1 rounded bg-slate-900/40 border border-white/5 text-xs text-center text-gray-200 focus:outline-none focus:border-purple-500 focus:bg-slate-900">
                                        </div>
                                    </div>

                                    <!-- Start / End Timing (Editable inline) -->
                                    <div class="grid grid-cols-2 gap-2" dir="ltr">
                                        <div>
                                            <input type="text" name="start_time" value="{{ $slide->start_time ? substr($slide->start_time, 0, 5) : '' }}" placeholder="08:00"
                                                class="w-full px-2 py-1 rounded bg-slate-900/40 border border-white/5 text-2xs text-center text-gray-300 focus:outline-none focus:border-purple-500 focus:bg-slate-900">
                                        </div>
                                        <div>
                                            <input type="text" name="end_time" value="{{ $slide->end_time ? substr($slide->end_time, 0, 5) : '' }}" placeholder="17:00"
                                                class="w-full px-2 py-1 rounded bg-slate-900/40 border border-white/5 text-2xs text-center text-gray-300 focus:outline-none focus:border-purple-500 focus:bg-slate-900">
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons (Save/Delete) -->
                                <div class="col-span-1 sm:col-span-2 flex justify-end gap-2.5 pt-2 border-t border-white/5">
                                    <button type="submit" class="bg-purple-600/15 hover:bg-purple-600 text-purple-400 hover:text-white px-3 py-1.5 rounded-xl text-xs font-semibold border border-purple-500/25 transition duration-200 flex items-center gap-1.5">
                                        <i data-lucide="save" class="w-3.5 h-3.5"></i>
                                        <span>{{ __('Save') }}</span>
                                    </button>
                                </div>
                            </form>

                            <!-- Delete button (Separate Form) -->
                            <form id="delete-slide-form-{{ $slide->id }}" action="{{ route('slides.destroy', $slide->id) }}" method="POST" class="shrink-0 pt-2 sm:pt-0 self-end sm:self-center">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="triggerConfirm('{{ __('Delete Slide') }}', '{{ __('Are you sure you want to delete this slide from the playlist rotation?') }}', document.getElementById('delete-slide-form-{{ $slide->id }}'))" 
                                        class="glass-panel hover:bg-red-500/10 text-gray-500 hover:text-red-400 p-2.5 rounded-xl border border-white/5 transition duration-200" title="{{ __('Delete Slide') }}">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Right: Screen Live Preview Frame (1 Column) -->
        <div class="xl:col-span-1 flex flex-col items-center space-y-4">
            <h3 class="text-lg font-semibold text-gray-200 w-full text-center xl:text-left rtl:xl:text-right">{{ __('Live Signage Monitor') }}</h3>
            
            <div class="screen-preview-container bg-slate-950 flex items-center justify-center overflow-hidden">
                <!-- Live Display iframe -->
                <iframe src="{{ route('screen.show', $screen->slug) }}" class="w-full h-full border-none select-none pointer-events-none" title="{{ __('Live Signage Monitor') }}"></iframe>
            </div>
            
            <div class="text-center space-y-1">
                <p class="text-xs text-gray-500 font-medium flex items-center justify-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                    {{ __('Iframe Preview Loop Active') }}
                </p>
                <p class="text-[10px] text-gray-600 max-w-[280px] font-light">
                    {{ __('The mock screen above displays the live, CSS-animated SSR slideshow. Notice the smooth transitions—rendered fully without JavaScript!') }}
                </p>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
    // File upload preview
    function previewUploadImage(input) {
        const previewImg = document.getElementById('upload-preview');
        const previewVid = document.getElementById('upload-preview-video');
        const placeholder = document.getElementById('upload-placeholder');
        
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();
            
            reader.onload = function(e) {
                placeholder.textContent = file.name;
                if (file.type.startsWith('video/')) {
                    previewImg.classList.add('hidden');
                    previewVid.src = e.target.result;
                    previewVid.classList.remove('hidden');
                } else {
                    previewVid.classList.add('hidden');
                    previewImg.src = e.target.result;
                    previewImg.classList.remove('hidden');
                }
            }
            
            reader.readAsDataURL(file);
        } else {
            previewImg.src = '';
            previewImg.classList.add('hidden');
            previewVid.src = '';
            previewVid.classList.add('hidden');
            placeholder.textContent = 'Choose image/video or drag here';
        }
    }

    // Drag and Drop ordering code
    let draggedItem = null;

    function drag(event) {
        draggedItem = event.currentTarget;
        draggedItem.classList.add('dragging');
        event.dataTransfer.effectAllowed = 'move';
        event.dataTransfer.setData('text/html', draggedItem.innerHTML);
    }

    function dragEnd(event) {
        if (draggedItem) {
            draggedItem.classList.remove('dragging');
        }
        draggedItem = null;
    }

    function allowDrop(event) {
        event.preventDefault();
        event.dataTransfer.dropEffect = 'move';
        
        const container = document.getElementById('slides-container');
        const afterElement = getDragAfterElement(container, event.clientY);
        const dragging = document.querySelector('.dragging');
        
        if (dragging) {
            if (afterElement == null) {
                container.appendChild(dragging);
            } else {
                container.insertBefore(dragging, afterElement);
            }
        }
    }

    // Helper to get element to insert drag before
    function getDragAfterElement(container, y) {
        const draggableElements = [...container.querySelectorAll('.draggable-slide:not(.dragging)')];
        
        return draggableElements.reduce((closest, child) => {
            const box = child.getBoundingClientRect();
            const offset = y - box.top - box.height / 2;
            if (offset < 0 && offset > closest.offset) {
                return { offset: offset, element: child };
            } else {
                return closest;
            }
        }, { offset: Number.NEGATIVE_INFINITY }).element;
    }

    // Monitor for drops to save order
    document.getElementById('slides-container')?.addEventListener('drop', function(e) {
        e.preventDefault();
        
        // Build list of slide IDs in current DOM order
        const slides = [...document.querySelectorAll('.draggable-slide')];
        const orderIds = slides.map(slide => slide.dataset.id);
        
        // Show saving state (change background colors or opacity)
        slides.forEach(slide => slide.style.opacity = '0.7');
        
        // Send reorder request to Laravel
        fetch("{{ route('slides.reorder', $screen->id) }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ order: orderIds })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload page to reflect new orders
                window.location.reload();
            } else {
                alert('Failed to save slide order. Please try again.');
                window.location.reload();
            }
        })
        .catch(err => {
            console.error(err);
            alert('An error occurred while reordering.');
            window.location.reload();
        });
    });
</script>
@endsection
