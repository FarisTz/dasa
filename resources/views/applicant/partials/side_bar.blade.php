 <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="{{ route('dashboard') }}"> <img alt="image" src="{{ asset('assets/img/logo.png') }}" class="header-logo" /> <span
                class="logo-name">KAAFAT</span>
            </a>
          </div>
          <ul class="sidebar-menu">
            <li class="menu-header">Main</li>
            <li class="dropdown {{ request()->routeIs('dashboard') ? 'active' : '' }}">
              <a href="{{ route('dashboard') }}" class="nav-link"><i data-feather="monitor"></i><span>Dashboard</span></a>
            </li>
            <li class="dropdown {{ request()->routeIs('applicant.personal_information*') || request()->routeIs('applicant.o_level*') || request()->routeIs('applicant.a_level*') ? 'active' : '' }}">
              <a href="#" class="menu-toggle nav-link has-dropdown {{ request()->routeIs('applicant.personal_information*') || request()->routeIs('applicant.o_level*') || request()->routeIs('applicant.a_level*') ? 'active' : '' }}"><i
                  data-feather="briefcase"></i><span>Applications</span></a>
              <ul class="dropdown-menu {{ request()->routeIs('applicant.personal_information*') || request()->routeIs('applicant.o_level*') || request()->routeIs('applicant.a_level*') ? 'show' : '' }}">
                <li class="{{ request()->routeIs('applicant.personal_information*') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('applicant.personal_information') }}">Personal Information</a>
                </li>
                <li class="{{ request()->routeIs('applicant.o_level*') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('applicant.o_level') }}">O-Level Education</a>
                </li>
                <li class="{{ request()->routeIs('applicant.a_level*') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('applicant.a_level') }}">A-Level Education</a>
                </li>
                <li><a class="nav-link" href="#">Review & Submit</a></li>
              </ul>
            </li>
            <li><a class="nav-link" href="#"><i data-feather="file"></i><span>My Applications</span></a></li>
            <li><a class="nav-link" href="#"><i data-feather="user"></i><span>Profile</span></a></li>
          </ul>
        </aside>
      </div>
