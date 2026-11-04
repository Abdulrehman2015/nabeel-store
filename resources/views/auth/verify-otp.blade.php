<x-guest-layout title="Verify One-Time Code">
    <div class="auth-shell">
        <x-auth-aside
            eyebrow="One more secure step"
            :title="(!empty($isBlocked) || session('account_blocked')) ? 'Account access locked.' : ($isReset ? 'Confirm your password reset.' : 'Verify your account.')"
            :description="(!empty($isBlocked) || session('account_blocked')) ? 'Multiple failed verification attempts detected. Your account is protected.' : ($isReset ? 'Enter the code we sent to confirm your identity before choosing a new password.' : 'Enter the code we sent to confirm your email and protect your account.')"
            note="Verification Security System"
        />

        <section class="auth-panel">
            <div class="auth-panel-inner">
                <div class="auth-panel-heading">
                    @if (!empty($isBlocked) || session('account_blocked'))
                        <span class="auth-eyebrow !text-rose-600 before:!bg-rose-600">Security Alert</span>
                        <h2 class="mt-3 text-rose-950">Account Blocked</h2>
                        <p class="text-rose-900">Your account has been blocked due to multiple failed verification attempts.</p>
                    @else
                        <span class="auth-eyebrow !text-[#079b78] before:!bg-[#079b78]">Verify your email</span>
                        <h2 class="mt-3">{{ $isReset ? 'Verify Reset Code' : 'Verify Your Account' }} <span class="sr-only">Verify Account</span></h2>
                        <p>We sent a one-time code to <strong class="font-bold text-slate-700">{{ $email }}</strong>. Enter it below to continue.</p>
                    @endif
                </div>

                <div class="auth-form">
                    @if (!empty($isBlocked) || session('account_blocked'))
                        {{-- ACCOUNT BLOCKED STATE --}}
                        <div class="rounded-2xl border-2 border-rose-200 bg-rose-50/70 p-5 sm:p-6 text-center space-y-4">
                            <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 border border-rose-200 flex items-center justify-center mx-auto">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-base font-extrabold text-rose-950">Access Suspended</h3>
                                <p class="text-xs text-rose-800 mt-1">You have exceeded the maximum allowed failed OTP verification attempts twice.</p>
                                <p class="text-xs text-rose-700 mt-0.5 font-medium">Please contact the store administrator using the details below to unlock your account.</p>
                            </div>

                            {{-- Dynamic Admin Contact Card --}}
                            @php
                                $displayAdminEmail = $adminEmail ?? session('blocked_admin_email', setting('admin_email', setting('store_email', 'admin@99bulksales.my')));
                                $displayAdminPhone = $adminPhone ?? session('blocked_admin_phone', setting('admin_phone', setting('store_phone', '+60 12-889 9900')));
                                $cleanPhone = preg_replace('/[^0-9]/', '', $displayAdminPhone);
                            @endphp
                            <div class="bg-white rounded-xl p-4 border border-rose-200/90 shadow-2xs text-left space-y-3">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block border-b border-slate-100 pb-1.5">Administrator Contact Details</span>
                                
                                {{-- Contact Email --}}
                                <div class="flex items-center gap-2.5 text-xs">
                                    <div class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 border border-sky-100 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div class="truncate">
                                        <span class="block text-[10px] text-slate-400 font-semibold uppercase">Admin Email</span>
                                        <a href="mailto:{{ $displayAdminEmail }}" class="font-bold text-sky-600 hover:text-sky-700 hover:underline truncate block text-xs">
                                            {{ $displayAdminEmail }}
                                        </a>
                                    </div>
                                </div>

                                {{-- Contact Phone / WhatsApp --}}
                                <div class="flex items-center justify-between gap-2 text-xs pt-2 border-t border-slate-100">
                                    <div class="flex items-center gap-2.5 truncate">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                        </div>
                                        <div class="truncate">
                                            <span class="block text-[10px] text-slate-400 font-semibold uppercase">Contact Number</span>
                                            <a href="tel:{{ $displayAdminPhone }}" class="font-bold text-slate-800 hover:text-emerald-700 block text-xs">
                                                {{ $displayAdminPhone }}
                                            </a>
                                        </div>
                                    </div>
                                    @if($cleanPhone)
                                        <a href="https://wa.me/{{ $cleanPhone }}?text=Hello%20Admin,%20my%20account%20({{ urlencode($email) }})%20has%20been%20blocked%20due%20to%20OTP%20verification.%20Please%20help%20me%20unlock%20it." target="_blank" class="px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-[10px] font-bold shrink-0 transition flex items-center gap-1 shadow-2xs">
                                            <span>WhatsApp</span>
                                            <span>&rarr;</span>
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-1.5 w-full py-2.5 text-xs font-bold text-slate-700 bg-white hover:bg-slate-100 rounded-xl border border-slate-200 transition">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                                Back to Sign In
                            </a>
                        </div>
                    @else
                        {{-- ACTIVE OTP ENTRY FORM --}}
                        @if (app()->environment(['local', 'testing']) && session('dev_otp_code'))
                            <div class="auth-alert auth-alert-success flex items-center justify-between gap-3">
                                <span><strong class="mr-2 uppercase tracking-wider">Test code</strong><span class="font-mono font-extrabold tracking-[.25em]">{{ session('dev_otp_code') }}</span></span>
                                <button type="button" onclick="fillOtp('{{ session('dev_otp_code') }}')" class="shrink-0 font-bold underline">Auto-fill</button>
                            </div>
                        @endif

                        @if (session('status'))
                            <div class="auth-alert auth-alert-success">{{ session('status') }}</div>
                        @endif

                        @php
                            $isOtpFailedNotice = ($errors->has('otp') && str_contains(strtolower($errors->first('otp')), 'your otp is failed')) || session('otp_failed_resend_required');
                        @endphp

                        @if ($isOtpFailedNotice)
                            <div class="auth-alert !bg-amber-50 !border-amber-300 text-amber-900 border p-3 rounded-xl space-y-1">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    <strong class="font-black text-amber-950 text-xs">Your OTP is failed, please resend the OTP.</strong>
                                </div>
                                <p class="text-[11px] text-amber-800 pl-6">You have reached 5 failed attempts for this code. Please click <strong>Resend verification code</strong> below to receive a new code.</p>
                            </div>
                        @elseif ($errors->any())
                            <div class="auth-alert auth-alert-error">
                                <strong class="block mb-1">That code could not be verified.</strong>
                                <ul class="list-disc list-inside space-y-0.5 text-xs">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ $isReset ? route('password.reset.otp.verify') : route('otp.verify.submit') }}" id="otpForm" class="space-y-6" x-data="{
                            d1: '', d2: '', d3: '', d4: '', d5: '', d6: '',
                            getFullOtp() { return `${this.d1}${this.d2}${this.d3}${this.d4}${this.d5}${this.d6}`; },
                            handleInput(e, nextId) {
                                e.target.value = e.target.value.replace(/\D/g, '').slice(0, 1);
                                if (e.target.value && nextId) document.getElementById(nextId).focus();
                            },
                            handleKeyDown(e, prevId) {
                                if (e.key === 'Backspace' && !e.target.value && prevId) document.getElementById(prevId).focus();
                            },
                            handlePaste(e) {
                                e.preventDefault();
                                const text = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 6);
                                if (text.length === 6) {
                                    this.d1 = text[0]; this.d2 = text[1]; this.d3 = text[2];
                                    this.d4 = text[3]; this.d5 = text[4]; this.d6 = text[5];
                                    document.getElementById('digit6').focus();
                                }
                            }
                        }">
                            @csrf
                            <div>
                                <label class="auth-label text-center">Verification code</label>
                                <div class="flex justify-center gap-2 sm:gap-3" @paste="handlePaste($event)">
                                    <input id="digit1" type="text" inputmode="numeric" maxlength="1" autocomplete="one-time-code" x-model="d1" @input="handleInput($event, 'digit2')" class="auth-otp-input">
                                    <input id="digit2" type="text" inputmode="numeric" maxlength="1" x-model="d2" @input="handleInput($event, 'digit3')" @keydown="handleKeyDown($event, 'digit1')" class="auth-otp-input">
                                    <input id="digit3" type="text" inputmode="numeric" maxlength="1" x-model="d3" @input="handleInput($event, 'digit4')" @keydown="handleKeyDown($event, 'digit2')" class="auth-otp-input">
                                    <input id="digit4" type="text" inputmode="numeric" maxlength="1" x-model="d4" @input="handleInput($event, 'digit5')" @keydown="handleKeyDown($event, 'digit3')" class="auth-otp-input">
                                    <input id="digit5" type="text" inputmode="numeric" maxlength="1" x-model="d5" @input="handleInput($event, 'digit6')" @keydown="handleKeyDown($event, 'digit4')" class="auth-otp-input">
                                    <input id="digit6" type="text" inputmode="numeric" maxlength="1" x-model="d6" @keydown="handleKeyDown($event, 'digit5')" class="auth-otp-input">
                                </div>
                                <input type="hidden" name="otp" id="otpReal" :value="getFullOtp()">
                            </div>

                            <div class="flex items-center justify-between rounded-xl border border-[#e5ecef] bg-[#f8fafb] px-3.5 py-3 text-[11px] text-slate-500">
                                <span class="flex items-center gap-1.5"><span class="text-[#079b78]">●</span> Valid for 10 minutes</span>
                                <span class="font-semibold text-slate-600">5 attempts per code</span>
                            </div>

                            <button type="submit" class="auth-submit">
                                Verify and continue
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 12h14m-6-6l6 6-6 6"/></svg>
                            </button>
                        </form>

                        <div class="auth-divider"></div>
                        <div class="text-center" x-data="{
                            timer: {{ $isOtpFailedNotice ? '0' : '60' }},
                            canResend: {{ $isOtpFailedNotice ? 'true' : 'false' }},
                            startTimer() {
                                if (this.canResend) return;
                                this.timer = 60;
                                const interval = setInterval(() => { this.timer--; if (this.timer <= 0) { clearInterval(interval); this.canResend = true; } }, 1000);
                            }
                        }" x-init="startTimer()">
                            <p class="text-xs text-slate-500">Didn’t receive the email or code failed?</p>
                            <form method="POST" action="{{ route('otp.resend') }}" class="mt-2 inline-block">
                                @csrf
                                <button type="submit" :disabled="!canResend" :class="canResend ? 'text-[#008d76] hover:underline cursor-pointer font-extrabold' : 'text-slate-400 cursor-not-allowed'" class="text-xs">
                                    <span x-show="!canResend">Resend code in <span x-text="timer"></span>s</span>
                                    <span x-show="canResend" x-cloak class="inline-flex items-center gap-1">
                                        <span>Resend verification code</span>
                                        <span>&rarr;</span>
                                    </span>
                                </button>
                            </form>
                        </div>

                        <a href="{{ route('login') }}" class="mt-5 flex items-center justify-center gap-1 text-xs font-bold text-slate-500 hover:text-slate-800">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Back to sign in
                        </a>
                    @endif
                </div>
            </div>
        </section>
    </div>

    <script>
        function fillOtp(code) {
            if (!code || code.length !== 6) return;
            for (let i = 0; i < 6; i++) {
                const el = document.getElementById('digit' + (i + 1));
                if (el) { el.value = code[i]; el.dispatchEvent(new Event('input', { bubbles: true })); }
            }
            document.getElementById('digit6')?.focus();
        }
    </script>
</x-guest-layout>
