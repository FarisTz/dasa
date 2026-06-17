<!DOCTYPE html>
<html lang="en">


<!-- auth-login.html  21 Nov 2019 03:49:32 GMT -->
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>KAAFAT</title>
  <!-- General CSS Files -->
  <link rel="stylesheet" href="{{asset( 'assets/css/app.min.css') }}">
  <link rel="stylesheet" href="{{asset('assets/bundles/bootstrap-social/bootstrap-social.css')}}">
  <!-- Template CSS -->
  <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
  <link rel="stylesheet" href="{{asset('assets/css/components.css')}}">
  <!-- Custom style CSS -->
  <link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">
  <link rel='shortcut icon' type='image/x-icon' href="{{'assets/img/dasa.png'}}" />
</head>

<body>
  <div class="loader"></div>
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-8 offset-sm-2  ">
            <div class="card card-primary">
              <div class="card-header">
                <h4>
                    @if(Route::is('login'))
             Sign In
              @elseif(Route::is('register'))
              Sign Up
              @elseif(Route::is('password.request'))
              Forgot Password
              @else
              Reset Password
              @endif



                </h4>
              </div>
              <div class="card-body">
                <img alt="image" style="margin: 0 auto; display: block;" width="180px" src="{{ asset('assets/img/dasa.png') }}" />
                {{ $slot }}
                <div class="text-center mt-4 mb-3">

                </div>
                <div class="row sm-gutters">


                </div>
              </div>
            </div>
            <div class="mt-5 text-muted text-center">

                @if(Route::is('login'))
              Don't have an account? <a href="{{route('register')}}">Sign Up</a>
              @elseif(Route::is('register'))
              Already have an account? <a href="{{route('login')}}">Login</a>
              @elseif(Route::is('password.request'))
              Remember it? <a href="{{route('login')}}"> Login</a>
              @else
                Change you mind? <a href="{{route('login')}}"> Login</a>
              @endif

            </div>
          </div>


        </div>
      </div>
    </section>
  </div>
  <!-- General JS Scripts -->
  <script src="{{ asset('assets/js/app.min.js') }}"></script>
  <!-- JS Libraies -->
  <!-- Page Specific JS File -->
  <!-- Template JS File -->
  <script src="{{ asset('assets/js/scripts.js') }}"></script>
  <!-- Custom JS File -->
  <script src="{{ asset('assets/js/custom.js') }}"></script>
</body>


<!-- auth-login.html  21 Nov 2019 03:49:32 GMT -->
</html>



