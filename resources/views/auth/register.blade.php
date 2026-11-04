<x-guest-layout title="Sign Up">
    <div class="auth-shell auth-register-shell">
        <x-auth-aside
            eyebrow="Create your account"
            title="Everything you need for a simpler shop."
            description="Sign up to browse products, save your details, track orders, and manage N&J Mini Mart deliveries."
            note="It only takes a minute to get started"
        />

        <section class="auth-panel auth-register-panel">
            <div class="auth-panel-inner">
                <div class="auth-panel-heading">
                    <span class="auth-eyebrow !text-[#079b78] before:!bg-[#079b78]">Get started</span>
                    <h2 class="mt-3">Sign Up <span class="sr-only">Create Account</span></h2>
                    <p>Create your customer account and verify your email with a one-time code.</p>
                </div>

                <div class="auth-form">
                    @if ($errors->any())
                        <div class="auth-alert auth-alert-error">
                            <strong class="block mb-1">Please check the details below.</strong>
                            <ul class="list-disc list-inside space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" class="space-y-3 auth-register-form" x-data="{
                        password: '',
                        password_confirmation: '',
                        showPass: false,
                        hasLength() { return this.password.length >= 8; },
                        hasUpper() { return /[A-Z]/.test(this.password); },
                        hasLower() { return /[a-z]/.test(this.password); },
                        hasNumber() { return /[0-9]/.test(this.password); },
                        hasSymbol() { return /[^A-Za-z0-9]/.test(this.password); }
                    }">
                        @csrf

                        <div>
                            <label for="name" class="auth-label">Full name / store in-charge <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <svg class="auth-input-icon absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 20a4 4 0 00-8 0m4-8a4 4 0 100-8 4 4 0 000 8zm6-1v5m2.5-2.5h-5"/></svg>
                                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="e.g. Ahmad Razak" class="auth-input pl-11 pr-4">
                            </div>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2">
                            <div>
                                <label for="email" class="auth-label">Email address <span class="text-rose-500">*</span></label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="you@business.com" class="auth-input px-4">
                            </div>
                            <div>
                                <label for="phone" class="auth-label">WhatsApp / phone</label>
                                <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" placeholder="+60 12-345 6789" class="auth-input px-4">
                            </div>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2">
                            <div>
                                <label for="password" class="auth-label">Create a password <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <input id="password" :type="showPass ? 'text' : 'password'" name="password" x-model="password" required autocomplete="new-password" placeholder="Choose a strong password" class="auth-input px-4 pr-11">
                                    <button type="button" @click="showPass = !showPass" class="absolute right-3 top-1/2 -translate-y-1/2 p-1.5 text-slate-400 hover:text-slate-700" aria-label="Toggle password visibility">
                                        <svg x-show="!showPass" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.5 12s3.4-6 9.5-6 9.5 6 9.5 6-3.4 6-9.5 6-9.5-6-9.5-6z"/><circle cx="12" cy="12" r="2.5" stroke-width="1.8"/></svg>
                                        <svg x-show="showPass" x-cloak class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3l18 18M10.6 10.6a2 2 0 002.8 2.8M6.2 6.3C3.8 7.8 2.5 12 2.5 12s3.4 6 9.5 6c1.6 0 3-.4 4.2-1"/></svg>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label for="password_confirmation" class="auth-label">Confirm password <span class="text-rose-500">*</span></label>
                                <input id="password_confirmation" :type="showPass ? 'text' : 'password'" name="password_confirmation" x-model="password_confirmation" required autocomplete="new-password" placeholder="Enter it again" class="auth-input px-4">
                            </div>
                        </div>

                        <div class="auth-checklist">
                            <span class="auth-checklist-title">Required Password Security Rules:</span>
                            <div class="grid grid-cols-2 gap-x-3 gap-y-2">
                                <div class="flex items-center gap-1.5" :class="hasLength() ? 'text-[#07896e] font-semibold' : ''"><span>●</span><span>8+ characters</span></div>
                                <div class="flex items-center gap-1.5" :class="hasUpper() && hasLower() ? 'text-[#07896e] font-semibold' : ''"><span>●</span><span>Upper & lower case</span></div>
                                <div class="flex items-center gap-1.5" :class="hasNumber() ? 'text-[#07896e] font-semibold' : ''"><span>●</span><span>At least 1 number</span></div>
                                <div class="flex items-center gap-1.5" :class="hasSymbol() ? 'text-[#07896e] font-semibold' : ''"><span>●</span><span>1 special symbol</span></div>
                            </div>
                        </div>

                        <label for="terms" class="flex cursor-pointer items-start gap-2.5 text-xs leading-relaxed text-slate-600">
                            <input id="terms" type="checkbox" name="terms" value="1" required class="mt-0.5 h-4 w-4 shrink-0 rounded border-slate-300 text-[#079b78] focus:ring-[#079b78]">
                            <span>I agree to the <a href="{{ route('page.show', 'terms-and-conditions') }}" target="_blank" class="font-bold text-[#008d76] hover:underline">Terms of Service</a>, <a href="{{ route('page.show', 'shipping-and-delivery-policy') }}" target="_blank" class="font-bold text-[#008d76] hover:underline">Delivery Policies</a>, and <a href="{{ route('page.show', 'privacy-policy') }}" target="_blank" class="font-bold text-[#008d76] hover:underline">Privacy Policy</a>.</span>
                        </label>

                        <button type="submit" class="auth-submit">
                            Sign Up & send code
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 12h14m-6-6l6 6-6 6"/></svg>
                        </button>
                    </form>

                    <div class="auth-divider"></div>
                    <p class="text-center text-xs text-slate-500">Already have an account?</p>
                    <a href="{{ route('login') }}" class="mt-2 flex items-center justify-center gap-1 text-center auth-footer-link">
                        Sign In
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 12h14m-6-6l6 6-6 6"/></svg>
                    </a>
                </div>
            </div>
        </section>
    </div>
</x-guest-layout>
