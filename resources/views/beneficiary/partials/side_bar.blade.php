 <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="{{ route('dashboard') }}"> <img alt="image" src="{{ asset('assets/img/dasa.png') }}" class="header-logo" /> <span
                class="logo-name">KAFAAT</span>
            </a>
          </div>
          <ul class="sidebar-menu">

            <li class="dropdown {{ request()->routeIs('dashboard') ? 'active' : '' }}">
              <a href="{{ route('dashboard') }}" class="nav-link"><i data-feather="monitor"></i><span>Dashboard</span></a>
            </li>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i
                  data-feather="briefcase"></i><span>Apply for scholarship</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="#">Personal Information</a></li>
                <li><a class="nav-link" href="#">O-Level Education</a></li>
                <li><a class="nav-link" href="#">A-Level Education</a></li>
                <li><a class="nav-link" href="#">Motivation Letter</a></li>
                <li><a class="nav-link" href="#">Review & Submit</a></li>

              </ul>
            </li>


            <li class=" {{ request()->routeIs('beneficiary.payments.index') ? 'active' : '' }}" ><a class="dropdown" href="{{ route('beneficiary.payments.index') }}"><i data-feather="file"></i><span>Payments</span></a></li>

            <li class=" {{ request()->routeIs('beneficiary.results.index') ? 'active' : '' }}" ><a class="dropdown" href="{{ route('beneficiary.results.index') }}"><i data-feather="file"></i><span>Academic Results</span></a></li>
            <li class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}" ><a class="dropdown" href="{{ route('profile.edit') }}"><i data-feather="file"></i><span>Profile</span></a></li>
            <li><a class="nav-link" href="{{ route('logout') }}"><i data-feather="file"></i><span>Logout</span></a></li>





          </ul>
        </aside>
      </div>
