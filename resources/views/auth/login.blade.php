<x-guest-layout>

    <div class="row">
        <div class="col-md-6 mt-4 ">
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                                        <!-- Password -->
                                        <div class="mt-4">
                                                <x-input-label for="password" :value="__('Password')" />

                                                <div class="relative">
                                                        <x-text-input id="password" class="block mt-1 w-full pr-10"
                                                                                        type="password"
                                                                                        name="password"
                                                                                        required autocomplete="current-password" />
                                                </div>

                                                <div class="max-w-sm mb-3">
                                                    <div class="flex items-center mt-2">
                                                        <input data-hs-toggle-password='{"target":"#password"}' id="hs-toggle-password-checkbox" type="checkbox" class="shrink-0 size-4 bg-transparent border-line-3 rounded-sm shadow-2xs text-primary focus:ring-0 focus:ring-offset-0 checked:bg-primary-checked checked:border-primary-checked disabled:opacity-50 disabled:pointer-events-none">
                                                        <label for="hs-toggle-password-checkbox" class="ms-2 text-sm text-muted-foreground-1">Show password</label>
                                                    </div>
                                                </div>

                                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                        </div>

                    <!-- Remember Me -->
                    <div  class="mt-4  ">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="" name="remember">
                            <span class="ms-2 text-sm ">{{ __('Remember me') }}</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a style="float: right" class="" href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button class="ms-3">
                            {{ __('Log in') }}
                        </x-primary-button>
                    </div>
                </form>

        </div>
        <div class="col-md-6 mt-4">
            <ul class="list-unstyled">
    <li>
        <i class="fas fa-user-plus RegisterIcon"></i>
        <span>You need to register before initiating an application.</span>
    </li>

    <li>
        <i class="fas fa-edit RegisterIcon"></i>
        <span>Signing up is free of charge and will take approximately 2 minutes. Signing up only once will be sufficient.</span>
    </li>

    <li>
        <i class="fas fa-check-circle RegisterIcon"></i>
        <span>The accuracy of the information you provide in your application is crucial in order to be considered for the scholarship award.</span>
    </li>

    <li>
        <i class="fas fa-clock RegisterIcon"></i>
        <span>The application process takes about 30-60 minutes.</span>
    </li>

    <li>
        <i class="fas fa-globe RegisterIcon"></i>
        <span>Kaafat Scholarships Information System works on Firefox, Google Chrome, Safari 5 and later versions, Microsoft Edge, Internet Explorer 8 and later versions.</span>
    </li>
</ul>

        </div>

    </div>

</x-guest-layout>

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
