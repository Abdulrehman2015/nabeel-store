<x-guest-layout title="Sign In">
    <div class="auth-shell">
        <x-auth-aside
            eyebrow="Customer account"
            title="Everything you need, just a sign-in away."
            description="Sign in to browse products, track orders, and manage your {{ store_name() }} deliveries."
            note="Secure access for {{ store_name() }} customers"
        />

        <section class="auth-panel">
            <div class="auth-panel-inner">
                <div class="auth-panel-heading">
                    <span class="auth-eyebrow !text-[#079b78] before:!bg-[#079b78]">Welcome back</span>
                    <h2 class="mt-3">Sign In to Your Account</h2>
                    <p>Use your email and password to continue shopping and manage your orders.</p>
                </div>

                <div class="auth-form">
                    @if (session('status'))
                        <div class="auth-alert auth-alert-success">{{ session('status') }}</div>
                    @endif

                    @if (session('warning'))
                        <div class="auth-alert auth-alert-warning">{{ session('warning') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="auth-alert auth-alert-error">
                            <strong class="block mb-1">We couldn’t sign you in.</strong>
                            <ul class="list-disc list-inside space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label for="email" class="auth-label">Registered email address <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <svg class="auth-input-icon absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="you@yourbusiness.com" class="auth-input pl-11 pr-4">
                            </div>
                        </div>

                        <div x-data="{ show: false }">
                            <div class="mb-2 flex items-center justify-between">
                                <label for="password" class="auth-label !mb-0">Password <span class="text-rose-500">*</span></label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-xs font-bold text-[#008d76] hover:underline">Forgot password?</a>
                                @endif
                            </div>
                            <div class="relative">
                                <svg class="auth-input-icon absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 10V8a5 5 0 0110 0v2m-9 0h8a2 2 0 012 2v7a2 2 0 01-2 2H8a2 2 0 01-2-2v-7a2 2 0 012-2z"/></svg>
                                <input id="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="current-password" placeholder="Enter your password" class="auth-input pl-11 pr-11">
                                <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 p-1.5 text-slate-400 hover:text-slate-700" aria-label="Toggle password visibility">
                                    <svg x-show="!show" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.5 12s3.4-6 9.5-6 9.5 6 9.5 6-3.4 6-9.5 6-9.5-6-9.5-6z"/><circle cx="12" cy="12" r="2.5" stroke-width="1.8"/></svg>
                                    <svg x-show="show" x-cloak class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3l18 18M10.6 10.6a2 2 0 002.8 2.8M6.2 6.3C3.8 7.8 2.5 12 2.5 12s3.4 6 9.5 6c1.6 0 3-.4 4.2-1M9.9 6.2A9 9 0 0112 6c6.1 0 9.5 6 9.5 6s-1.2 2.1-3.2 3.8"/></svg>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-1">
                            <label for="remember_me" class="inline-flex cursor-pointer items-center gap-2 text-xs font-semibold text-slate-600">
                                <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-[#079b78] focus:ring-[#079b78]">
                                Keep me signed in
                            </label>
                            <span class="text-[11px] text-slate-400">Protected by rate limiting</span>
                        </div>

                        <button type="submit" class="auth-submit">
                            Sign In
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 12h14m-6-6l6 6-6 6"/></svg>
                        </button>
                    </form>

                    @if (app()->environment('local'))
                        <div class="auth-mini-note">
                            <span>Developer quick fill</span>
                            <div class="flex gap-3">
                                <button type="button" onclick="document.getElementById('email').value='nabeel@store.com'; document.getElementById('password').value='Admin@123456';" class="font-bold text-[#008d76] hover:underline">Admin</button>
                                <button type="button" onclick="document.getElementById('email').value='customer@example.com'; document.getElementById('password').value='Customer@123456';" class="font-bold text-[#008d76] hover:underline">Customer</button>
                            </div>
                        </div>
                    @endif

                    <div class="auth-divider"></div>
                    <p class="text-center text-xs text-slate-500">New to {{ store_name() }}?</p>
                    <a href="{{ route('register') }}" class="mt-2 flex items-center justify-center gap-1 text-center auth-footer-link">
                        Sign Up
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 12h14m-6-6l6 6-6 6"/></svg>
                    </a>
                </div>
            </div>
        </section>
    </div>
</x-guest-layout>
