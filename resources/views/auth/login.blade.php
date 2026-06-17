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

                        <x-text-input id="password" class="block mt-1 w-full"
                                        type="password"
                                        name="password"
                                        required autocomplete="current-password" />

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
