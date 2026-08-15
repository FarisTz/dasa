 <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="{{ route('dashboard') }}"> <img alt="image" src="{{ asset('assets/img/dasa.png') }}" class="header-logo" /> <span
                class="logo-name">KAFAAT</span>
            </a>
          </div>
          <ul class="sidebar-menu">
            <li class="menu-header">Main</li>
            <li class="dropdown active">
              <a href="{{ route('dashboard') }}" class="nav-link"><i data-feather="monitor"></i><span>Dashboard</span></a>
            </li>
            <li class="dropdown active">
              <a href="{{ route('coordinator.scholarships.index') }}" class="nav-link"><i data-feather="mail"></i><span>Scholarships</span></a>
            </li>

            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i
                  data-feather="briefcase"></i><span>Applications</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{ route('coordinator.applications.index') }}">All Applications</a></li>

                <li><a class="nav-link" href="{{ route('coordinator.applications.index', ['status' => 'approved_full']) }}">Approved Full</a></li>
                <li><a class="nav-link" href="{{ route('coordinator.applications.index', ['status' => 'approved_partial']) }}">Approved Partial</a></li>
                <li><a class="nav-link" href="{{ route('coordinator.applications.index', ['status' => 'rejected']) }}">Rejected</a></li>
              </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i
                  data-feather="briefcase"></i><span>Students</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="#">All Students</a></li>
                <li><a class="nav-link" href="#">Confirmed Students</a></li>
                <li><a class="nav-link" href="#">Not Confirmed Students</a></li>
                <li><a class="nav-link" href="#">Canceled Students</a></li>
              </ul>
            </li>
            <!-- Reports & Analytics -->
      <li class="dropdown">
        <a href="#" class="menu-toggle nav-link has-dropdown">
          <i data-feather="shopping-bag"></i><span>Reports & Analytics</span>
        </a>
        <ul class="dropdown-menu">
         
          <li><a class="nav-link" href="{{ route('coordinator.reports.application') }}">Application Reports</a></li>
          <li><a class="nav-link" href="{{ route('coordinator.reports.academic') }}">Academic-Perfomance Reports</a></li>
            <li><a class="nav-link" href="{{ route('coordinator.reports.financial') }}">Beneficiary-Financial Reports</a></li>
            <li><a class="nav-link" href="{{ route('coordinator.reports.utilization') }}">Scholarship-Utilization Reports</a></li>
        </ul>
      </li>

            <li><a class="nav-link" href="{{ route('profile.edit') }}"><i data-feather="file"></i><span>Profile</span></a></li>
            <li><a class="nav-link" href="{{ route('logout') }}"><i data-feather="file"></i><span>Logout</span></a></li>
          </ul>
        </aside>
      </div>

