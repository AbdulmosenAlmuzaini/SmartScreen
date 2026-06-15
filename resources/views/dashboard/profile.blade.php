@extends('layouts.admin')

@section('title', __('Profile Settings'))

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    
    <!-- Title Page Header -->
    <div class="flex flex-col gap-1.5">
        <h2 class="text-2xl font-bold tracking-tight text-gray-100 flex items-center gap-2.5">
            <i data-lucide="user-cog" class="text-purple-400 w-7 h-7"></i>
            <span>{{ __('Profile Settings') }}</span>
        </h2>
        <p class="text-xs text-gray-400 leading-relaxed font-light">
            {{ __('Manage your account credentials, login credentials, and security preferences.') }}
        </p>
    </div>

    <!-- Security Advisory Alert Banner -->
    <div class="p-5 rounded-2xl bg-amber-500/10 border border-amber-500/20 shadow-lg shadow-amber-500/5 flex items-start gap-4">
        <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/25 flex items-center justify-center text-amber-500 shrink-0">
            <i data-lucide="shield-alert" class="w-5.5 h-5.5 animate-pulse"></i>
        </div>
        <div>
            <h4 class="text-sm font-bold text-amber-400">{{ __('Security Tip') }}</h4>
            <p class="text-xs text-amber-300/85 mt-1 leading-relaxed">
                {{ __('It is highly recommended to change your default password and email address immediately after logging in to protect your account.') }}
            </p>
        </div>
    </div>

    <!-- Success Messages -->
    @if(session('status') == 'profile-updated')
        <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/25 text-emerald-400 text-xs flex items-center gap-2.5">
            <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i>
            <span>{{ __('Profile updated successfully.') }}</span>
        </div>
    @endif

    @if(session('status') == 'profile-updated-verification-sent')
        <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/25 text-emerald-400 text-xs flex items-start gap-2.5">
            <i data-lucide="check-circle" class="w-4 h-4 shrink-0 mt-0.5"></i>
            <span>{{ __('Profile updated successfully. A new verification link has been sent to the email address you provided during registration.') }}</span>
        </div>
    @endif

    @if(session('status') == 'password-updated')
        <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/25 text-emerald-400 text-xs flex items-center gap-2.5">
            <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i>
            <span>{{ __('Password updated successfully.') }}</span>
        </div>
    @endif

    <!-- Validation Errors -->
    @if($errors->any())
        <div class="p-4 rounded-xl bg-red-500/10 border border-red-500/25 text-red-400 text-xs flex flex-col gap-1.5">
            <div class="flex items-center gap-2 font-semibold mb-1">
                <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
                <span>{{ __('Please correct the errors below:') }}</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5 text-[11px] pl-2 rtl:pl-0 rtl:pr-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <!-- Update Profile Details Section -->
        <div class="glass-panel p-6 rounded-3xl space-y-6">
            <div class="flex items-center gap-3 border-b border-white/5 pb-4">
                <div class="w-9 h-9 rounded-lg bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400">
                    <i data-lucide="user" class="w-4.5 h-4.5"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-200">{{ __('Profile Information') }}</h3>
                    <p class="text-2xs text-gray-500 mt-0.5">{{ __("Update your account's profile name and email address.") }}</p>
                </div>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">{{ __('Name') }}</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                        class="w-full px-4 py-3 rounded-xl bg-slate-900/60 border border-white/5 focus:border-purple-500/40 text-sm text-gray-200 focus:outline-none focus:bg-slate-900 transition duration-200">
                </div>

                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label for="email" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ __('Email Address') }}</label>
                        @if ($user->hasVerifiedEmail())
                            <span class="inline-flex items-center gap-1 text-2xs text-emerald-400 font-medium">
                                <i data-lucide="badge-check" class="w-3.5 h-3.5"></i>
                                {{ __('Verified') }}
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-2xs text-amber-400 font-medium">
                                <i data-lucide="mail-warning" class="w-3.5 h-3.5"></i>
                                {{ __('Unverified') }}
                            </span>
                        @endif
                    </div>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required dir="ltr"
                        class="w-full px-4 py-3 rounded-xl bg-slate-900/60 border border-white/5 focus:border-purple-500/40 text-sm text-gray-200 focus:outline-none focus:bg-slate-900 transition duration-200">
                </div>

                <button type="submit" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-gradient-to-r from-purple-600 to-blue-500 hover:from-purple-500 hover:to-blue-400 font-semibold text-sm text-white shadow-lg shadow-purple-500/10 hover:shadow-purple-500/20 hover:scale-[1.01] transition duration-200 flex items-center justify-center gap-2 mt-2">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>{{ __('Save Changes') }}</span>
                </button>
            </form>
        </div>

        <!-- Update Password Section -->
        <div class="glass-panel p-6 rounded-3xl space-y-6">
            <div class="flex items-center gap-3 border-b border-white/5 pb-4">
                <div class="w-9 h-9 rounded-lg bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400">
                    <i data-lucide="key-round" class="w-4.5 h-4.5"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-200">{{ __('Update Password') }}</h3>
                    <p class="text-2xs text-gray-500 mt-0.5">{{ __('Ensure your account is using a long, random password to stay secure.') }}</p>
                </div>
            </div>

            <form action="{{ route('profile.password') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">{{ __('Current Password') }}</label>
                    <input type="password" name="current_password" id="current_password" required dir="ltr"
                        class="w-full px-4 py-3 rounded-xl bg-slate-900/60 border border-white/5 focus:border-purple-500/40 text-sm text-gray-200 focus:outline-none focus:bg-slate-900 transition duration-200"
                        placeholder="••••••••">
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">{{ __('New Password') }}</label>
                    <input type="password" name="password" id="password" required dir="ltr"
                        class="w-full px-4 py-3 rounded-xl bg-slate-900/60 border border-white/5 focus:border-purple-500/40 text-sm text-gray-200 focus:outline-none focus:bg-slate-900 transition duration-200"
                        placeholder="••••••••">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">{{ __('Confirm Password') }}</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required dir="ltr"
                        class="w-full px-4 py-3 rounded-xl bg-slate-900/60 border border-white/5 focus:border-purple-500/40 text-sm text-gray-200 focus:outline-none focus:bg-slate-900 transition duration-200"
                        placeholder="••••••••">
                </div>

                <button type="submit" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-gradient-to-r from-purple-600 to-blue-500 hover:from-purple-500 hover:to-blue-400 font-semibold text-sm text-white shadow-lg shadow-purple-500/10 hover:shadow-purple-500/20 hover:scale-[1.01] transition duration-200 flex items-center justify-center gap-2 mt-2">
                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                    <span>{{ __('Change Password') }}</span>
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
