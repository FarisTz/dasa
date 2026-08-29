<x-guest-layout>

<div class="col-md-12">


    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <div class="relative">
                <x-text-input id="password" class="block mt-1 w-full pr-10"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
            </div>

            <div class="max-w-sm mb-2">
              <div class="flex items-center mt-2">
                <input data-hs-toggle-password='{"target":"#password"}' id="hs-toggle-password-checkbox-register" type="checkbox" class="shrink-0 size-4 bg-transparent border-line-3 rounded-sm shadow-2xs text-primary focus:ring-0 focus:ring-offset-0 checked:bg-primary-checked checked:border-primary-checked disabled:opacity-50 disabled:pointer-events-none">
                <label for="hs-toggle-password-checkbox-register" class="ms-2 text-sm text-muted-foreground-1">Show password</label>
              </div>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <div class="relative">
                <x-text-input id="password_confirmation" class="block mt-1 w-full pr-10"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />
            </div>

            <div class="max-w-sm mb-2">
              <div class="flex items-center mt-2">
                <input data-hs-toggle-password='{"target":"#password_confirmation"}' id="hs-toggle-password-confirmation-register" type="checkbox" class="shrink-0 size-4 bg-transparent border-line-3 rounded-sm shadow-2xs text-primary focus:ring-0 focus:ring-offset-0 checked:bg-primary-checked checked:border-primary-checked disabled:opacity-50 disabled:pointer-events-none">
                <label for="hs-toggle-password-confirmation-register" class="ms-2 text-sm text-muted-foreground-1">Show password</label>
              </div>
            </div>

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4 mt-3">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
    </div>
                <script>
                    (function () {
                        function initToggle(control) {
                            let cfg = {};
                            try { cfg = JSON.parse(control.getAttribute('data-hs-toggle-password')); } catch (e) { return; }
                            const target = cfg && cfg.target ? cfg.target : control.getAttribute('data-target');
                            if (!target) return;
                            const input = document.querySelector(target);
                            if (!input) return;

                            if (control.type === 'checkbox') {
                                // initialize
                                if (control.checked) input.type = 'text';
                                control.classList.toggle('hs-password-active', control.checked);
                                control.addEventListener('change', function () {
                                    input.type = control.checked ? 'text' : 'password';
                                    control.classList.toggle('hs-password-active', control.checked);
                                    try {
                                        control.querySelectorAll('.hs-password-active\\:hidden').forEach(el => el.classList.toggle('hidden', control.classList.contains('hs-password-active')));
                                        control.querySelectorAll('.hs-password-active\\:block').forEach(el => el.classList.toggle('hidden', !control.classList.contains('hs-password-active')));
                                    } catch (e) {}
                                });
                            } else {
                                control.classList.toggle('hs-password-active', input.type !== 'password');
                                control.addEventListener('click', function () {
                                    const active = input.type === 'password';
                                    input.type = active ? 'text' : 'password';
                                    control.classList.toggle('hs-password-active', active);
                                    try {
                                        control.querySelectorAll('.hs-password-active\\:hidden').forEach(el => el.classList.toggle('hidden', control.classList.contains('hs-password-active')));
                                        control.querySelectorAll('.hs-password-active\\:block').forEach(el => el.classList.toggle('hidden', !control.classList.contains('hs-password-active')));
                                    } catch (e) {}
                                });
                            }
                        }
                        document.querySelectorAll('[data-hs-toggle-password]').forEach(initToggle);
                    })();
                </script>
</x-guest-layout>
