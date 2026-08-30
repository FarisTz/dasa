<!DOCTYPE html>
<html lang="en">


<!-- index.html  21 Nov 2019 03:44:50 GMT -->
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>KAFAAT - @yield('title')</title>
  <!-- General CSS Files -->
  <link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}">
  <!-- Template CSS -->
  <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
  <link rel="stylesheet" href="{{asset('assets/css/components.css')}}">
   <link rel="stylesheet" href="{{ asset('assets/bundles/datatables/datatables.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
  <!-- Custom style CSS -->
  <link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">
  <link rel='shortcut icon' type='image/x-icon' href='{{asset('assets/img/dasa.png')}}' />
  <style>
          .milestone-card {
            transition: 0.3s;
            cursor: pointer;
        }

        .milestone-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .milestone-icon {
            font-size: 35px;
            margin-bottom: 10px;
        }
  </style>
  @stack('styles')
</head>


<body>


    @php
       $user = Auth::user();
      use App\Models\Notification as AppNotification;
      use App\Models\Log as AppLog;
      use Illuminate\Support\Str;

       $notifications = [];
       $unreadCount = 0;
       $activities = [];
       $activityCount = 0;

         if ($user) {
             // Only query notifications if table/column exist
             if (\Illuminate\Support\Facades\Schema::hasTable('notifications') && \Illuminate\Support\Facades\Schema::hasColumn('notifications', 'user_id')) {
                 $notifications = AppNotification::where('user_id', $user->id)->orderBy('created_at','desc')->limit(5)->get();
                 try {
                     $unreadCount = AppNotification::where('user_id', $user->id)->where('read', 0)->count();
                 } catch (\Exception $e) {
                     $unreadCount = 0;
                 }
             }

             // Only query activities/logs if table/column exist
             if (\Illuminate\Support\Facades\Schema::hasTable('logs') && \Illuminate\Support\Facades\Schema::hasColumn('logs', 'user_id')) {
                 $activities = AppLog::where('user_id', $user->id)->orderBy('created_at','desc')->limit(5)->get();
                 $activityCount = AppLog::where('user_id', $user->id)->count();
             }
         }
    @endphp


  <div class="loader"></div>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar sticky">
        <div class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg
									collapse-btn"> <i data-feather="align-justify"></i></a></li>
            <li><a href="#" class="nav-link nav-link-lg fullscreen-btn">
                <i data-feather="maximize"></i>
              </a></li>

          </ul>
        </div>
        <ul class="navbar-nav navbar-right">
          <li class="dropdown dropdown-list-toggle">
            <a href="#" data-toggle="dropdown" class="nav-link nav-link-lg message-toggle">
              <i data-feather="bell"></i>
              @if($unreadCount > 0)
                <span class="badge headerBadge1">{{ $unreadCount }}</span>
              @endif
            </a>
            <div class="dropdown-menu dropdown-list dropdown-menu-right pullDown">
              <div class="dropdown-header">
                Notifications
                <div class="float-right">
                  <form method="POST" action="{{ route('notifications.readAll') }}">@csrf<button class="btn btn-link btn-sm">Mark All As Read</button></form>
                </div>
              </div>
              <div class="dropdown-list-content dropdown-list-message">
                @forelse($notifications as $note)
                  <a href="#" class="dropdown-item">
                    <span class="dropdown-item-desc">
                      <span class="message-user">{{ $note->title ?? 'Notification' }}</span>
                      <span class="time messege-text">{{ Str::limit($note->message ?? '', 60) }}</span>
                      <span class="time">{{ $note->created_at->diffForHumans() }}</span>
                    </span>
                  </a>
                @empty
                  <div class="p-3 text-center text-muted">No notifications</div>
                @endforelse
              </div>
              <div class="dropdown-footer text-center">
                <a href="{{ route('notifications.index') }}">View All <i class="fas fa-chevron-right"></i></a>
              </div>
            </div>
          </li>
          <li class="dropdown"><a href="#" data-toggle="dropdown"
              class="nav-link dropdown-toggle nav-link-lg nav-link-user">
              @php
                $avatarUrl = $user && $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : asset('assets/img/dasa22.png');
              @endphp
              <img alt="image" src="{{ $avatarUrl }}" class="user-img-radious-style"> <span class="d-sm-none d-lg-inline-block"></span></a>
            <div class="dropdown-menu dropdown-menu-right pullDown">
              <div class="dropdown-title">Hello {{$user->name }}</div>
              <a href="{{ route('profile.edit') }}" class="dropdown-item has-icon"> <i class="far
										fa-user"></i> Profile
              </a> <a href="{{ route('activities.index') }}" class="dropdown-item has-icon"> <i class="fas fa-bolt"></i>
                Activities
              </a> <a href="#" class="dropdown-item has-icon"> <i class="fas fa-cog"></i>
                Settings
              </a>
              <div class="dropdown-divider"></div>
              <a href="#" onclick="event.preventDefault(); document.getElementById('app-logout-form').submit();" class="dropdown-item has-icon text-danger"> <i class="fas fa-sign-out-alt"></i>
                Logout
              </a>
              <form id="app-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
              </form>
            </div>
          </li>
        </ul>
      </nav>
            @if ($user->role === 'admin')
                @include('admin.partials.side_bar')
            @elseif($user->role === 'coordinator')
                @include('coordinator.partials.side_bar')
            @elseif($user->role === 'user')
                @include('applicant.partials.side_bar')
            @elseif($user->role === 'beneficiary')
                @include('beneficiary.partials.side_bar')
                @else
                @include('applicant.partials.side_bar')
            @endif

      <!-- Main Content -->
      <div class="main-content">
        @yield('content')



        @include('admin.partials.setting')
      </div>
     @include('admin.partials.footer')
    </div>
  </div>
  <!-- Fix Sidebar Dropdown - Add this before closing body -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ===== 1. Fix dropdown toggles =====
        var dropdownToggles = document.querySelectorAll('.menu-toggle');

        dropdownToggles.forEach(function(toggle) {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                var parentLi = this.closest('.dropdown');
                var dropdownMenu = parentLi.querySelector('.dropdown-menu');

                // Toggle the dropdown
                if (parentLi.classList.contains('active')) {
                    parentLi.classList.remove('active');
                    dropdownMenu.style.display = 'none';
                } else {
                    // Close other dropdowns
                    document.querySelectorAll('.dropdown').forEach(function(dropdown) {
                        if (dropdown !== parentLi) {
                            dropdown.classList.remove('active');
                            var menu = dropdown.querySelector('.dropdown-menu');
                            if (menu) menu.style.display = 'none';
                        }
                    });

                    parentLi.classList.add('active');
                    dropdownMenu.style.display = 'block';
                }
            });
        });

        // ===== 2. Keep dropdown open when submenu is active =====
        var activeSubmenu = document.querySelector('.dropdown-menu .active');
        if (activeSubmenu) {
            var parentDropdown = activeSubmenu.closest('.dropdown');
            if (parentDropdown) {
                parentDropdown.classList.add('active');
                var dropdownMenu = parentDropdown.querySelector('.dropdown-menu');
                if (dropdownMenu) {
                    dropdownMenu.style.display = 'block';
                }
            }
        }

        // ===== 3. Fix: When clicking on submenu items, keep parent open =====
        document.querySelectorAll('.dropdown-menu .nav-link').forEach(function(link) {
            link.addEventListener('click', function() {
                var parentDropdown = this.closest('.dropdown');
                if (parentDropdown) {
                    // Keep the dropdown open after navigation
                    setTimeout(function() {
                        parentDropdown.classList.add('active');
                        var menu = parentDropdown.querySelector('.dropdown-menu');
                        if (menu) menu.style.display = 'block';
                    }, 50);
                }
            });
        });
    });
</script>


<!--End of Tawk.to Script-->
  <!-- General JS Scripts -->
  <script src="{{asset('assets/js/app.min.js')}}"></script>
  <!-- JS Libraies -->

  <script src="{{asset('assets/bundles/apexcharts/apexcharts.min.js')}}"></script>
  <!-- Page Specific JS File -->
  <script src="{{asset('assets/js/page/index.js')}}"></script>

    <script src="assets/bundles/datatables/datatables.min.js"></script>
  <script src="{{ asset('assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{asset('assets/bundles/jquery-ui/jquery-ui.min.js')}}"></script>
  <!-- Page Specific JS File -->
  <script src="{{asset('assets/js/page/datatables.js')}}"></script>
  <!-- Template JS File -->
  <script src="{{asset('assets/js/scripts.js')}}"></script>
  <!-- Custom JS File -->
  <script src="{{asset('assets/js/custom.js')}}"></script>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>


$(function(){
    $(document).on('click','#delete',function(e){
        e.preventDefault();
        var link = $(this).attr("href");


                  Swal.fire({
                    title: 'Are you sure?',
                    text: "Delete This Data?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                  }).then((result) => {
                    if (result.isConfirmed) {
                      window.location.href = link
                      Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                      )
                    }
                  })


    });

  });

</script>

   @stack('scripts')
</body>


<!-- index.html  21 Nov 2019 03:47:04 GMT -->
</html>
