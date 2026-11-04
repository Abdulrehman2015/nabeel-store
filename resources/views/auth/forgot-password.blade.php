<x-guest-layout title="Forgot Password">
    <div class="w-full max-w-5xl space-y-6">
        {{-- Hero Header Section --}}
        <x-storefront-hero 
            title="Reset Your Password"
            description="Enter your registered email address to receive an instant, secure 6-digit OTP code to regain account access."
            badge="Account Recovery"
            subBadge="🛡️ Safe & Encrypted"
            :breadcrumbs="['Forgot Password' => null]"
        >
            <x-slot:icon>
                <svg class="w-10 h-10 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                </svg>
            </x-slot:icon>
        </x-storefront-hero>

        {{-- Auth Form Box --}}
        <div class="flex justify-center">
            <div class="w-full max-w-md">
                <!-- Auth Card -->
                <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-200/80 overflow-hidden">
                    <div class="p-6 sm:p-8">
                        <!-- Session Status Message -->
                        @if (session('status'))
                            <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>{{ session('status') }}</span>
                            </div>
                        @endif

                        <!-- Errors Header if any -->
                        @if ($errors->any())
                            <div class="mb-5 p-3.5 rounded-xl bg-red-50 border border-red-200 text-red-800 text-xs font-medium">
                                <div class="font-semibold flex items-center gap-1.5 mb-1 text-red-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Password Reset Notice
                                </div>
                                <ul class="list-disc list-inside space-y-0.5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                            @csrf

                            <!-- Email Address -->
                            <div>
                                <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Registered Email Address <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/>
                                        </svg>
                                    </div>
                                    <input id="email" 
                                           type="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           required 
                                           autofocus 
                                           placeholder="e.g. shopowner@example.com"
                                           class="block w-full pl-10 pr-3.5 py-2.5 text-sm bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">
                                </div>
                                <p class="text-[11px] text-slate-500 mt-1.5">
                                    We will generate a 6-digit one-time PIN (OTP) valid for 10 minutes to verify your ownership.
                                </p>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-2">
                                <button type="submit" 
                                        class="w-full py-3 px-4 bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm rounded-xl shadow-md transition-all duration-200 flex items-center justify-center gap-2 group cursor-pointer">
                                    <span>Send 6-Digit OTP Code</span>
                                    <svg class="w-4 h-4 text-lime-400 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Back to Login Link -->
                <div class="text-center mt-6">
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-slate-600 hover:text-emerald-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span>Remember your password? Back to Sign In</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
