<x-guest-layout title="Verify Security Code">
    <div class="w-full max-w-md">
        <!-- Auth Card -->
        <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/60 border border-slate-200/80 overflow-hidden">
            <!-- Header Banner -->
            <div class="bg-slate-900 bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 px-6 py-6 text-white text-center relative overflow-hidden">
                <div class="absolute -right-6 -bottom-6 w-24 h-24 rounded-full bg-emerald-500/10 blur-xl"></div>
                <div class="w-12 h-12 mx-auto rounded-2xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center mb-3 ring-4 ring-emerald-500/10">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold tracking-tight">
                    {{ $isReset ? 'Verify Reset Code' : 'Verify Wholesale Account' }}
                </h1>
                <p class="text-xs text-slate-300 mt-1 max-w-xs mx-auto">
                    We sent a 6-digit authentication code to <span class="font-semibold text-white">{{ $email }}</span>
                </p>
            </div>

            <div class="p-6 sm:p-8">
                <!-- Test Helper / Dev OTP Code Banner -->
                @if (session('dev_otp_code'))
                    <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-300 text-emerald-900 text-xs flex items-center justify-between gap-2 shadow-2xs">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded-md bg-emerald-600 text-white font-extrabold text-[10px] uppercase tracking-wider">Active OTP</span>
                            <span class="font-mono text-base font-extrabold tracking-widest text-emerald-800">{{ session('dev_otp_code') }}</span>
                        </div>
                        <button type="button" 
                                onclick="fillOtp('{{ session('dev_otp_code') }}')"
                                class="px-2.5 py-1 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg font-bold text-[11px] transition-colors shadow-2xs">
                            Auto-Fill
                        </button>
                    </div>
                @endif

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
                            Verification Failed
                        </div>
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" 
                      action="{{ $isReset ? route('password.reset.otp.verify') : route('otp.verify.submit') }}" 
                      id="otpForm"
                      class="space-y-5"
                      x-data="{
                          d1: '', d2: '', d3: '', d4: '', d5: '', d6: '',
                          getFullOtp() { return `${this.d1}${this.d2}${this.d3}${this.d4}${this.d5}${this.d6}`; },
                          handleInput(e, nextId) {
                              if (e.target.value.length === 1 && nextId) {
                                  document.getElementById(nextId).focus();
                              }
                          },
                          handleKeyDown(e, prevId) {
                              if (e.key === 'Backspace' && !e.target.value && prevId) {
                                  document.getElementById(prevId).focus();
                              }
                          },
                          handlePaste(e) {
                              e.preventDefault();
                              const text = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 6);
                              if (text.length >= 6) {
                                  this.d1 = text[0];
                                  this.d2 = text[1];
                                  this.d3 = text[2];
                                  this.d4 = text[3];
                                  this.d5 = text[4];
                                  this.d6 = text[5];
                                  document.getElementById('digit6').focus();
                              }
                          }
                      }">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider text-center mb-3">
                            Enter 6-Digit Verification PIN
                        </label>
                        
                        <!-- 6 Box Digit Inputs -->
                        <div class="flex items-center justify-center gap-2 sm:gap-2.5" @paste="handlePaste($event)">
                            <input id="digit1" type="text" inputmode="numeric" maxlength="1" x-model="d1"
                                   @input="handleInput($event, 'digit2')"
                                   class="w-11 sm:w-12 h-14 text-center text-xl sm:text-2xl font-mono font-extrabold bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all shadow-2xs">
                            <input id="digit2" type="text" inputmode="numeric" maxlength="1" x-model="d2"
                                   @input="handleInput($event, 'digit3')"
                                   @keydown="handleKeyDown($event, 'digit1')"
                                   class="w-11 sm:w-12 h-14 text-center text-xl sm:text-2xl font-mono font-extrabold bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all shadow-2xs">
                            <input id="digit3" type="text" inputmode="numeric" maxlength="1" x-model="d3"
                                   @input="handleInput($event, 'digit4')"
                                   @keydown="handleKeyDown($event, 'digit2')"
                                   class="w-11 sm:w-12 h-14 text-center text-xl sm:text-2xl font-mono font-extrabold bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all shadow-2xs">
                            <input id="digit4" type="text" inputmode="numeric" maxlength="1" x-model="d4"
                                   @input="handleInput($event, 'digit5')"
                                   @keydown="handleKeyDown($event, 'digit3')"
                                   class="w-11 sm:w-12 h-14 text-center text-xl sm:text-2xl font-mono font-extrabold bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all shadow-2xs">
                            <input id="digit5" type="text" inputmode="numeric" maxlength="1" x-model="d5"
                                   @input="handleInput($event, 'digit6')"
                                   @keydown="handleKeyDown($event, 'digit4')"
                                   class="w-11 sm:w-12 h-14 text-center text-xl sm:text-2xl font-mono font-extrabold bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all shadow-2xs">
                            <input id="digit6" type="text" inputmode="numeric" maxlength="1" x-model="d6"
                                   @keydown="handleKeyDown($event, 'digit5')"
                                   class="w-11 sm:w-12 h-14 text-center text-xl sm:text-2xl font-mono font-extrabold bg-slate-50 border border-slate-300 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all shadow-2xs">
                        </div>

                        <!-- Hidden Real OTP Input sent to backend -->
                        <input type="hidden" name="otp" id="otpReal" :value="getFullOtp()">
                    </div>

                    <!-- Security Info Box -->
                    <div class="flex items-center justify-between text-[11px] text-slate-500 bg-slate-50 p-2.5 rounded-lg border border-slate-200/80">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            Expires in 10 minutes
                        </span>
                        <span class="font-medium text-slate-600">Max 5 attempts</span>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" 
                                class="w-full py-3.5 px-4 bg-emerald-600 bg-gradient-to-r from-emerald-600 to-teal-600 hover:bg-emerald-700 hover:from-emerald-700 hover:to-teal-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-emerald-600/30 hover:shadow-emerald-600/40 transition-all duration-200 flex items-center justify-center gap-2 group cursor-pointer">
                            <span>Confirm & Authorize</span>
                            <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>
                </form>

                <!-- Resend Code Section -->
                <div class="mt-6 pt-5 border-t border-slate-100 text-center" 
                     x-data="{
                         timer: 60,
                         canResend: false,
                         startTimer() {
                             this.timer = 60;
                             this.canResend = false;
                             const interval = setInterval(() => {
                                 this.timer--;
                                 if (this.timer <= 0) {
                                     clearInterval(interval);
                                     this.canResend = true;
                                 }
                             }, 1000);
                         }
                     }" 
                     x-init="startTimer()">
                    <p class="text-xs text-slate-500 mb-2">
                        Didn't receive your verification code?
                    </p>

                    <form method="POST" action="{{ route('otp.resend') }}" class="inline-block">
                        @csrf
                        <button type="submit" 
                                :disabled="!canResend"
                                :class="canResend ? 'text-emerald-700 hover:text-emerald-800 hover:underline cursor-pointer font-bold' : 'text-slate-400 cursor-not-allowed font-medium'"
                                class="text-xs inline-flex items-center gap-1.5 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <span x-show="!canResend">Resend new code in <strong x-text="timer"></strong>s</span>
                            <span x-show="canResend" x-cloak>Resend Verification Code Now</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Back to Login / Start Over -->
        <div class="text-center mt-6">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Back to Sign In</span>
            </a>
        </div>
    </div>

    <script>
        function fillOtp(code) {
            if (!code || code.length !== 6) return;
            for (let i = 0; i < 6; i++) {
                const el = document.getElementById('digit' + (i + 1));
                if (el) {
                    el.value = code[i];
                    el.dispatchEvent(new Event('input'));
                }
            }
            const hidden = document.getElementById('otpReal');
            if (hidden) hidden.value = code;
        }
    </script>
</x-guest-layout>

