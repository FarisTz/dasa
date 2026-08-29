<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" />
            <div class="relative">
                <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full pr-10" autocomplete="current-password" />
            </div>
            <div class="max-w-sm mb-2">
              <div class="flex items-center mt-2">
                <input data-hs-toggle-password='{"target":"#update_password_current_password"}' id="hs-toggle-password-current" type="checkbox" class="shrink-0 size-4 bg-transparent border-line-3 rounded-sm shadow-2xs text-primary focus:ring-0 focus:ring-offset-0 checked:bg-primary-checked checked:border-primary-checked disabled:opacity-50 disabled:pointer-events-none">
                <label for="hs-toggle-password-current" class="ms-2 text-sm text-muted-foreground-1">Show password</label>
              </div>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" />
            <div class="relative">
                <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full pr-10" autocomplete="new-password" />
            </div>
            <div class="max-w-sm mb-2">
              <div class="flex items-center mt-2">
                <input data-hs-toggle-password='{"target":"#update_password_password"}' id="hs-toggle-password-new" type="checkbox" class="shrink-0 size-4 bg-transparent border-line-3 rounded-sm shadow-2xs text-primary focus:ring-0 focus:ring-offset-0 checked:bg-primary-checked checked:border-primary-checked disabled:opacity-50 disabled:pointer-events-none">
                <label for="hs-toggle-password-new" class="ms-2 text-sm text-muted-foreground-1">Show password</label>
              </div>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
            <div class="relative">
                <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full pr-10" autocomplete="new-password" />
            </div>
            <div class="max-w-sm mb-2">
              <div class="flex items-center mt-2">
                <input data-hs-toggle-password='{"target":"#update_password_password_confirmation"}' id="hs-toggle-password-confirm" type="checkbox" class="shrink-0 size-4 bg-transparent border-line-3 rounded-sm shadow-2xs text-primary focus:ring-0 focus:ring-offset-0 checked:bg-primary-checked checked:border-primary-checked disabled:opacity-50 disabled:pointer-events-none">
                <label for="hs-toggle-password-confirm" class="ms-2 text-sm text-muted-foreground-1">Show password</label>
              </div>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 mt-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-success"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
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
</section>
