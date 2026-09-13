<x-guest-layout>
    <div class="mb-8">
        <p class="text-sm font-semibold uppercase tracking-[.2em] text-[#b86d3d]">Welcome back</p>
        <h1 class="mt-3 font-['Playfair_Display'] text-4xl text-[#2d211b]">Sign in to your counter</h1>
        <p class="mt-3 text-sm leading-6 text-[#75675d]">Manage orders, stock, and your cafe day from one place.</p>
    </div>

    <x-auth-session-status class="mb-5" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" class="font-semibold text-[#59483d]" :value="__('Work email')" />
            <x-text-input id="email" class="auth-input mt-2 block w-full rounded-xl px-4 py-3 shadow-sm" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-5">
            <div class="flex items-center justify-between">
                <x-input-label for="password" class="font-semibold text-[#59483d]" :value="__('Password')" />
                <button type="button" 
                        class="text-xs font-semibold text-[#a85d32] hover:underline focus:outline-none" 
                        data-password-toggle="password">
                    <span data-eye-open>Show</span>
                    <span data-eye-closed class="hidden">Hide</span>
                </button>
            </div>

            <x-text-input id="password" class="auth-input mt-2 block w-full rounded-xl px-4 py-3 shadow-sm"
                        type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="mt-5 flex items-center">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-[#d8cabb] text-[#a85d32] shadow-sm focus:ring-[#b86d3d]" name="remember">
                <span class="ms-2 text-sm text-[#75675d]">{{ __('Keep me signed in') }}</span>
            </label>
        </div>

        <div class="mt-7 flex items-center justify-between gap-4">
            @if (Route::has('password.request'))
                <a class="text-sm font-semibold text-[#9b5b37] hover:text-[#6f3820]" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

        </div>
        <x-primary-button class="auth-submit mt-4 w-full justify-center rounded-full px-5 py-3.5 font-bold text-white shadow-sm">
                {{ __('Log in') }}
        </x-primary-button>
    </form>
    <script>
        document.querySelectorAll('[data-password-toggle]').forEach((toggle) => {
            toggle.addEventListener('click', () => {
                const input = document.getElementById(toggle.dataset.passwordToggle);
                const visible = input.type === 'text';
                input.type = visible ? 'password' : 'text';
                toggle.setAttribute('aria-label', visible ? 'Show password' : 'Hide password');
                toggle.querySelector('[data-eye-open]').classList.toggle('hidden', !visible);
                toggle.querySelector('[data-eye-closed]').classList.toggle('hidden', visible);
            });
        });
    </script>
</x-guest-layout>
