@extends('layouts.app')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center py-16 px-4 sm:px-6 lg:px-8 bg-background-alt">
    <div class="max-w-md w-full space-y-6" x-data="otpVerification()">
        <div class="text-center" data-gsap="fade-up">
            <a href="{{ url('/') }}" class="inline-block mb-4 group">
                <img src="/images/winterfell-nobg.png" alt="Winterfell Treks & Expedition" class="h-14 w-auto mx-auto object-contain transition-transform group-hover:scale-105">
            </a>
            
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-orange-100 text-accent-primary mb-3 shadow-inner">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>

            <h2 class="font-display text-2xl sm:text-3xl font-bold text-text-primary tracking-tight">
                Two-Step Verification
            </h2>
            <p class="mt-2 text-xs sm:text-sm text-text-secondary leading-relaxed max-w-sm mx-auto">
                We've sent a 6-digit security code to your email<br>
                <span class="font-semibold text-text-primary bg-gray-100 px-2 py-0.5 rounded text-xs">{{ $maskedEmail }}</span>
            </p>
        </div>

        @if(session('status'))
        <div class="p-3.5 bg-green-50 border border-green-200 text-green-800 text-xs sm:text-sm rounded-md flex items-center gap-2 shadow-2xs">
            <svg class="w-4 h-4 text-green-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <span>{{ session('status') }}</span>
        </div>
        @endif

        @if(session('error') || $errors->has('code'))
        <div class="p-3.5 bg-red-50 border border-red-200 text-red-800 text-xs sm:text-sm rounded-md flex items-center gap-2 shadow-2xs">
            <svg class="w-4 h-4 text-red-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
            <span>{{ session('error') ?: $errors->first('code') }}</span>
        </div>
        @endif

        <div class="bg-white border border-gray-200 p-8 shadow-sm rounded-lg space-y-6">
            <form method="POST" action="{{ route('login.otp.verify') }}" @submit="handleSubmit($event)">
                @csrf
                
                {{-- Hidden input holding the composite 6-digit code --}}
                <input type="hidden" name="code" x-model="fullCode">

                <div>
                    <label class="block text-xs font-bold uppercase text-text-muted mb-3 text-center tracking-wider">
                        Enter 6-Digit Passcode
                    </label>
                    
                    {{-- 6 Individual Digit Inputs --}}
                    <div class="flex items-center justify-between gap-2 sm:gap-2.5 max-w-xs mx-auto" @paste="handlePaste($event)">
                        <template x-for="(digit, idx) in digits" :key="idx">
                            <input type="text" 
                                   inputmode="numeric" 
                                   maxlength="1" 
                                   autocomplete="one-time-code"
                                   x-model="digits[idx]"
                                   :id="'digit-' + idx"
                                   @input="handleInput(idx, $event)"
                                   @keydown="handleKeydown(idx, $event)"
                                   @focus="$event.target.select()"
                                   class="w-11 h-13 sm:w-12 sm:h-14 text-center font-mono text-xl sm:text-2xl font-bold rounded-lg border-2 border-gray-200 text-gray-900 focus:border-accent-primary focus:ring-2 focus:ring-accent-primary/20 focus:outline-none transition-all duration-150 select-none shadow-xs"
                                   :class="digits[idx] ? 'border-accent-primary bg-orange-50/30' : 'bg-gray-50/50'">
                        </template>
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" 
                            :disabled="fullCode.length !== 6 || isSubmitting"
                            class="btn-primary w-full py-3.5 text-base font-bold shadow-md flex items-center justify-center gap-2 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!isSubmitting">Verify & Sign In</span>
                        <span x-show="isSubmitting" class="flex items-center gap-2">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Verifying Code...
                        </span>
                    </button>
                </div>
            </form>

            <div class="pt-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-text-secondary">
                <div>
                    <span x-show="timer > 0">
                        Resend code in <strong class="text-text-primary" x-text="timer + 's'"></strong>
                    </span>
                    <form method="POST" action="{{ route('login.otp.resend') }}" x-show="timer === 0" class="inline">
                        @csrf
                        <button type="submit" class="font-bold text-accent-primary hover:underline focus:outline-none">
                            Resend Code
                        </button>
                    </form>
                </div>

                <div>
                    <a href="{{ route('login') }}" class="text-text-muted hover:text-text-primary hover:underline flex items-center gap-1">
                        &larr; Use another account
                    </a>
                </div>
            </div>
        </div>

        <div class="text-center text-xs text-text-muted">
            <p>Protected staff console · Two-step verification required for portal security</p>
        </div>
    </div>
</div>

<script>
function otpVerification() {
    return {
        digits: ['', '', '', '', '', ''],
        timer: 60,
        timerInterval: null,
        isSubmitting: false,

        get fullCode() {
            return this.digits.join('');
        },

        init() {
            // Auto focus first digit input
            setTimeout(() => {
                const first = document.getElementById('digit-0');
                if (first) first.focus();
            }, 100);

            // Start 60s cooldown timer for resend
            this.timerInterval = setInterval(() => {
                if (this.timer > 0) {
                    this.timer--;
                } else {
                    clearInterval(this.timerInterval);
                }
            }, 1000);
        },

        handleInput(idx, event) {
            const val = event.target.value.replace(/[^0-9]/g, '');
            this.digits[idx] = val ? val[val.length - 1] : '';

            if (this.digits[idx] && idx < 5) {
                const next = document.getElementById('digit-' + (idx + 1));
                if (next) next.focus();
            }

            // If all 6 digits entered, auto-submit
            if (this.fullCode.length === 6) {
                setTimeout(() => {
                    this.isSubmitting = true;
                    event.target.closest('form').submit();
                }, 150);
            }
        },

        handleKeydown(idx, event) {
            if (event.key === 'Backspace' && !this.digits[idx] && idx > 0) {
                const prev = document.getElementById('digit-' + (idx - 1));
                if (prev) {
                    prev.focus();
                    this.digits[idx - 1] = '';
                }
            } else if (event.key === 'ArrowLeft' && idx > 0) {
                document.getElementById('digit-' + (idx - 1))?.focus();
            } else if (event.key === 'ArrowRight' && idx < 5) {
                document.getElementById('digit-' + (idx + 1))?.focus();
            }
        },

        handlePaste(event) {
            event.preventDefault();
            const pasted = (event.clipboardData || window.clipboardData).getData('text');
            const cleanDigits = pasted.replace(/[^0-9]/g, '').slice(0, 6);
            if (cleanDigits) {
                for (let i = 0; i < 6; i++) {
                    this.digits[i] = cleanDigits[i] || '';
                }
                const focusIdx = Math.min(cleanDigits.length, 5);
                document.getElementById('digit-' + focusIdx)?.focus();

                if (cleanDigits.length === 6) {
                    setTimeout(() => {
                        this.isSubmitting = true;
                        event.target.closest('form').submit();
                    }, 150);
                }
            }
        },

        handleSubmit(event) {
            if (this.fullCode.length !== 6) {
                event.preventDefault();
                return;
            }
            this.isSubmitting = true;
        }
    };
}
</script>
@endsection

