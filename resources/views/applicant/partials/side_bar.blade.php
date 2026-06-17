 <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="{{ route('dashboard') }}"> <img alt="image" style="width: 100px;" src="{{ asset('assets/img/dasa.png') }}" class="header-logo" /> <span
                class="logo-name">KAFAAT</span>
            </a>
          </div>
          <ul class="sidebar-menu">
            <li class="menu-header">Main</li>
            <li class="dropdown active">
              <a href="{{ route('dashboard') }}" class="nav-link"><i data-feather="monitor"></i><span>Dashboard</span></a>
            </li>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i
                  data-feather="briefcase"></i><span>Apply for scholarship</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{ route('applicant.personal_information') }}">Personal Information</a></li>
                <li><a class="nav-link" href="{{ route('applicant.o-level-education') }}">O-Level Education</a></li>
                <li><a class="nav-link" href="{{ route('applicant.a-level-education') }}">A-Level Education</a></li>
                <li><a class="nav-link" href="{{ route('applicant.motivations.index') }}">Motivation Letter</a></li>
                <li><a class="nav-link" href="#">Review & Submit</a></li>

              </ul>
            </li>


            <li><a class="nav-link" href="#"><i data-feather="file"></i><span>My Applications</span></a></li>
            <li><a class="nav-link" href="#"><i data-feather="file"></i><span>Profile</span></a></li>
            <li><a class="nav-link" href="#"><i data-feather="file"></i><span>Logout</span></a></li>
          </ul>
        </aside>
      </div>
