<x-guest-layout>
    <div class="mb-8">
        <p class="text-sm font-semibold uppercase tracking-[.2em] text-[#b86d3d]">Join the team</p>
        <h1 class="mt-3 font-['Playfair_Display'] text-4xl text-[#2d211b]">Create your account</h1>
        <p class="mt-3 text-sm leading-6 text-[#75675d]">Set up your cafe profile and keep every service running smoothly.</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" class="font-semibold text-[#59483d]" :value="__('Your name')" />
            <x-text-input id="name" class="auth-input mt-2 block w-full rounded-xl px-4 py-3 shadow-sm" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-5">
            <x-input-label for="email" class="font-semibold text-[#59483d]" :value="__('Work email')" />
            <x-text-input id="email" class="auth-input mt-2 block w-full rounded-xl px-4 py-3 shadow-sm" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-5">
            <x-input-label for="password" class="font-semibold text-[#59483d]" :value="__('Password')" />

            <div class="password-wrap mt-2">
                <x-text-input id="password" class="auth-input block w-full rounded-xl px-4 py-3 shadow-sm"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
                <button type="button" class="password-toggle absolute inset-y-0 right-0 z-10 flex w-12 items-center justify-center" data-password-toggle="password" aria-label="Show password">
                    <svg data-eye-open class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.5"/></svg>
                    <svg data-eye-closed class="hidden h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path d="m3 3 18 18M10.6 10.6a2 2 0 0 0 2.8 2.8M9.9 5.2A10.7 10.7 0 0 1 12 5c6 0 9.5 7 9.5 7a17.8 17.8 0 0 1-3 3.7M6.2 6.3C3.8 8 2.5 12 2.5 12s3.5 7 9.5 7c1.4 0 2.7-.3 3.8-.8"/></svg>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-5">
            <x-input-label for="password_confirmation" class="font-semibold text-[#59483d]" :value="__('Confirm password')" />

            <div class="password-wrap mt-2">
                <x-text-input id="password_confirmation" class="auth-input block w-full rounded-xl px-4 py-3 shadow-sm"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />
                <button type="button" class="password-toggle absolute inset-y-0 right-0 z-10 flex w-12 items-center justify-center" data-password-toggle="password_confirmation" aria-label="Show password">
                    <svg data-eye-open class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.5"/></svg>
                    <svg data-eye-closed class="hidden h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path d="m3 3 18 18M10.6 10.6a2 2 0 0 0 2.8 2.8M9.9 5.2A10.7 10.7 0 0 1 12 5c6 0 9.5 7 9.5 7a17.8 17.8 0 0 1-3 3.7M6.2 6.3C3.8 8 2.5 12 2.5 12s3.5 7 9.5 7c1.4 0 2.7-.3 3.8-.8"/></svg>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-7 flex items-center justify-between gap-4">
            <a class="text-sm font-semibold text-[#9b5b37] hover:text-[#6f3820]" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

        </div>
        <x-primary-button class="auth-submit mt-4 w-full justify-center rounded-full px-5 py-3.5 font-bold text-white shadow-sm">
                {{ __('Register') }}
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
