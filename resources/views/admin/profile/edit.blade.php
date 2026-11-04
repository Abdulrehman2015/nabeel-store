@extends('layouts.admin')

@section('title', 'Admin Profile & Security Settings')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto" x-data="{
    showCurrentPass: false,
    showNewPass: false,
    showConfirmPass: false
}">

    {{-- Breadcrumb & Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-400 font-medium">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-sky-600 transition">Dashboard</a>
                <span>/</span>
                <span class="text-slate-600">Settings</span>
                <span>/</span>
                <span class="text-sky-600 font-bold">Admin Profile</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-1">
                Admin Profile & Security Credentials
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Manage your administrative login email, contact phone number, and security password.
            </p>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            <a 
                href="{{ route('admin.dashboard') }}" 
                class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition flex items-center gap-1.5 cursor-pointer shadow-2xs"
            >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Back to Dashboard</span>
            </a>
        </div>
    </div>

    {{-- Global Validation Errors Alert --}}
    @if(isset($errors) && $errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-800 text-xs space-y-1 shadow-2xs">
            <div class="font-bold flex items-center gap-2 text-rose-900">
                <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span>Please correct the errors below before submitting:</span>
            </div>
            <ul class="list-disc pl-6 space-y-0.5 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Dual Column Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        {{-- ========================================================== --}}
        {{-- LEFT COLUMN: EDIT PROFILE & UPDATE PASSWORD (8 Cols)      --}}
        {{-- ========================================================== --}}
        <div class="lg:col-span-8 space-y-6">
            
            {{-- Form 1: General Info & Login Email --}}
            <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/90 shadow-2xs space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center font-bold text-sm">
                            👤
                        </div>
                        <div>
                            <h2 class="text-sm sm:text-base font-black text-slate-900 tracking-tight">
                                Profile & Administrative Email
                            </h2>
                            <p class="text-[11px] text-slate-400">
                                This email address is used to access your store's control panel.
                            </p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Name --}}
                        <div class="sm:col-span-2 space-y-1">
                            <label for="name" class="block text-xs font-bold text-slate-700">
                                Full Name <span class="text-rose-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="name" 
                                id="name" 
                                value="{{ old('name', $user->name) }}" 
                                required
                                placeholder="Store Administrator"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:bg-white focus:border-sky-500 focus:ring-2 focus:ring-sky-500/20 transition"
                            >
                            @error('name')
                                <span class="text-[11px] text-rose-500 font-medium">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="space-y-1">
                            <label for="email" class="block text-xs font-bold text-slate-700">
                                Login Email Address <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                                </div>
                                <input 
                                    type="email" 
                                    name="email" 
                                    id="email" 
                                    value="{{ old('email', $user->email) }}" 
                                    required
                                    placeholder="admin@mart.my"
                                    class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:bg-white focus:border-sky-500 focus:ring-2 focus:ring-sky-500/20 transition"
                                >
                            </div>
                            <span class="text-[10px] text-slate-400 block mt-0.5">
                                Changing your email will immediately change your administrative login credential.
                            </span>
                            @error('email')
                                <span class="text-[11px] text-rose-500 font-medium">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div class="space-y-1">
                            <label for="phone" class="block text-xs font-bold text-slate-700">
                                Contact Phone Number
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </div>
                                <input 
                                    type="tel" 
                                    name="phone" 
                                    id="phone" 
                                    value="{{ old('phone', $user->phone) }}" 
                                    placeholder="+60 12-889 9900"
                                    class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:bg-white focus:border-sky-500 focus:ring-2 focus:ring-sky-500/20 transition"
                                >
                            </div>
                            <span class="text-[10px] text-slate-400 block mt-0.5">Used for internal escalation and order alert SMS.</span>
                            @error('phone')
                                <span class="text-[11px] text-rose-500 font-medium">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-end">
                        <button 
                            type="submit" 
                            class="px-5 py-2.5 bg-sky-600 hover:bg-sky-500 active:scale-98 text-white font-bold text-xs rounded-xl shadow-xs shadow-sky-600/25 transition cursor-pointer flex items-center gap-1.5"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Save Profile Changes</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Form 2: Change Security Password --}}
            <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/90 shadow-2xs space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-sm">
                            🔐
                        </div>
                        <div>
                            <h2 class="text-sm sm:text-base font-black text-slate-900 tracking-tight">
                                Update Security Password
                            </h2>
                            <p class="text-[11px] text-slate-400">
                                Ensure your account uses a strong, complex password to protect store operations.
                            </p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.profile.password.update') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    {{-- Current Password --}}
                    <div class="space-y-1">
                        <label for="current_password" class="block text-xs font-bold text-slate-700">
                            Current Password <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input 
                                :type="showCurrentPass ? 'text' : 'password'" 
                                name="current_password" 
                                id="current_password" 
                                required
                                placeholder="Enter your current account password"
                                class="w-full pl-3.5 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:bg-white focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition"
                            >
                            <button 
                                type="button" 
                                @click="showCurrentPass = !showCurrentPass" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 transition cursor-pointer"
                                title="Show / Hide Password"
                            >
                                <span x-text="showCurrentPass ? '👁️' : '🔒'" class="text-xs"></span>
                            </button>
                        </div>
                        @error('current_password')
                            <span class="text-[11px] text-rose-500 font-medium">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                        {{-- New Password --}}
                        <div class="space-y-1">
                            <label for="password" class="block text-xs font-bold text-slate-700">
                                New Password <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <input 
                                    :type="showNewPass ? 'text' : 'password'" 
                                    name="password" 
                                    id="password" 
                                    required
                                    minlength="8"
                                    placeholder="Minimum 8 characters"
                                    class="w-full pl-3.5 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:bg-white focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition"
                                >
                                <button 
                                    type="button" 
                                    @click="showNewPass = !showNewPass" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 transition cursor-pointer"
                                    title="Show / Hide Password"
                                >
                                    <span x-text="showNewPass ? '👁️' : '🔒'" class="text-xs"></span>
                                </button>
                            </div>
                            <span class="text-[10px] text-slate-400 block mt-0.5">Use at least 8 characters with letters & numbers.</span>
                            @error('password')
                                <span class="text-[11px] text-rose-500 font-medium">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div class="space-y-1">
                            <label for="password_confirmation" class="block text-xs font-bold text-slate-700">
                                Confirm New Password <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <input 
                                    :type="showConfirmPass ? 'text' : 'password'" 
                                    name="password_confirmation" 
                                    id="password_confirmation" 
                                    required
                                    minlength="8"
                                    placeholder="Re-type new password"
                                    class="w-full pl-3.5 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:bg-white focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition"
                                >
                                <button 
                                    type="button" 
                                    @click="showConfirmPass = !showConfirmPass" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 transition cursor-pointer"
                                    title="Show / Hide Password"
                                >
                                    <span x-text="showConfirmPass ? '👁️' : '🔒'" class="text-xs"></span>
                                </button>
                            </div>
                            <span class="text-[10px] text-slate-400 block mt-0.5">Must match the new password exactly.</span>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-end">
                        <button 
                            type="submit" 
                            class="px-5 py-2.5 bg-amber-600 hover:bg-amber-500 active:scale-98 text-white font-bold text-xs rounded-xl shadow-xs shadow-amber-600/25 transition cursor-pointer flex items-center gap-1.5"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            <span>Update Security Password</span>
                        </button>
                    </div>
                </form>
            </div>

        </div>

        {{-- ========================================================== --}}
        {{-- RIGHT COLUMN: ACCOUNT CARD & AUDIT TRAIL (4 Cols)          --}}
        {{-- ========================================================== --}}
        <div class="lg:col-span-4 space-y-6">
            
            {{-- Admin Identity Card --}}
            <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-2xs text-center space-y-3">
                <div class="w-20 h-20 mx-auto rounded-3xl bg-gradient-to-br from-sky-600 to-sky-700 text-white flex items-center justify-center text-2xl font-black shadow-md shadow-sky-600/20">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>

                <div>
                    <h3 class="text-base font-black text-slate-900 leading-tight">{{ $user->name }}</h3>
                    <p class="text-xs text-slate-500 font-mono mt-0.5">{{ $user->email }}</p>
                </div>

                <div class="pt-1 flex flex-wrap items-center justify-center gap-1.5">
                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-black uppercase tracking-wider">
                        Active Account
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full bg-sky-50 text-sky-700 border border-sky-200 text-[10px] font-bold uppercase tracking-wider">
                        {{ $user->role ?? 'Store Admin' }}
                    </span>
                </div>

                <div class="pt-3 border-t border-slate-100 text-left text-xs space-y-2 text-slate-600">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Account ID:</span>
                        <span class="font-mono font-bold text-slate-800">#ADM-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Member Since:</span>
                        <span class="font-semibold text-slate-800">{{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Last Profile Update:</span>
                        <span class="font-semibold text-slate-800">{{ $user->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>

            {{-- Security Overview Card --}}
            <div class="bg-gradient-to-br from-slate-900 to-slate-950 text-white rounded-3xl p-6 border border-slate-800 space-y-3 shadow-sm">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xs font-bold">
                        🛡️
                    </div>
                    <h4 class="font-black text-xs text-white uppercase tracking-wider">Security Shield</h4>
                </div>

                <p class="text-[11px] text-slate-300 leading-relaxed">
                    Your administrative account has full read & write privileges to orders, customer personal records, stock pricing, and payments.
                </p>

                <div class="pt-2 border-t border-slate-800 space-y-1.5 text-[11px]">
                    <div class="flex items-center gap-2 text-slate-400">
                        <svg class="w-3.5 h-3.5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span>Encrypted bcrypt (12 rounds) hash</span>
                    </div>
                    <div class="flex items-center gap-2 text-slate-400">
                        <svg class="w-3.5 h-3.5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span>CSRF token & session protected</span>
                    </div>
                    <div class="flex items-center gap-2 text-slate-400">
                        <svg class="w-3.5 h-3.5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span>Activity log audit tracking active</span>
                    </div>
                </div>
            </div>

            {{-- Recent Security Logs --}}
            <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-2xs space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <h4 class="font-black text-xs text-slate-900 uppercase tracking-wider">Recent Activity</h4>
                    <a href="{{ route('admin.activity-logs.index') }}" class="text-[10px] font-bold text-sky-600 hover:underline">View All</a>
                </div>

                <div class="space-y-2.5 text-xs">
                    @forelse($recentLogs as $log)
                        <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 flex items-start justify-between gap-2">
                            <div>
                                <span class="font-bold text-slate-800 block text-[11px]">
                                    {{ ucwords(str_replace('_', ' ', $log->action)) }}
                                </span>
                                <span class="text-[10px] text-slate-400 block font-mono">IP: {{ $log->ip_address ?: '127.0.0.1' }}</span>
                            </div>
                            <span class="text-[10px] text-slate-400 font-medium whitespace-nowrap">{{ $log->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <p class="text-[11px] text-slate-400 text-center py-2">No recent security events.</p>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
