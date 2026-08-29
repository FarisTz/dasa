<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

                <!-- Password -->
                <div>
                        <x-input-label for="password" :value="__('Password')" />

                        <div class="relative">
                                <x-text-input id="password" class="block mt-1 w-full pr-10"
                                                                type="password"
                                                                name="password"
                                                                required autocomplete="current-password" />
                        </div>

                        <div class="max-w-sm mb-2">
                            <div class="flex items-center mt-2">
                                <input data-hs-toggle-password='{"target":"#password"}' id="hs-toggle-password-confirm" type="checkbox" class="shrink-0 size-4 bg-transparent border-line-3 rounded-sm shadow-2xs text-primary focus:ring-0 focus:ring-offset-0 checked:bg-primary-checked checked:border-primary-checked disabled:opacity-50 disabled:pointer-events-none">
                                <label for="hs-toggle-password-confirm" class="ms-2 text-sm text-muted-foreground-1">Show password</label>
                            </div>
                        </div>

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
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

        <div class="flex justify-end mt-4">
            <x-primary-button>
                {{ __('Confirm') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
