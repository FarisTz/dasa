<div class="main-sidebar sidebar-style-2">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="{{ route('dashboard') }}">
        <img alt="image" width="100px" height="100px" src="{{ asset('assets/img/dasa.png') }}" class="header-logo" />
        <span class="logo-name">KAAFAT</span>
      </a>
    </div>
    <ul class="sidebar-menu">
      <li class="menu-header">Main</li>

      <!-- Dashboard -->
      <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <a href="{{ route('dashboard') }}" class="nav-link">
          <i data-feather="monitor"></i><span>Dashboard</span>
        </a>
      </li>

      <!-- Users -->
      <li class="dropdown {{ request()->routeIs('admin.users.index', 'admin.users.create') ? 'active' : '' }}">
        <a href="#" class="menu-toggle nav-link has-dropdown">
          <i data-feather="briefcase"></i><span>User</span>
        </a>
        <ul class="dropdown-menu" style="{{ request()->routeIs('admin.users.index', 'admin.users.create') ? 'display: block;' : '' }}">
          <li class="{{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.users.index') }}">Manage user</a>
          </li>
          <li class="{{ request()->routeIs('admin.users.create') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.users.create') }}">Add User</a>
          </li>
        </ul>
      </li>

      <!-- Scholarships -->
      <li class="dropdown {{ request()->routeIs('admin.scholarships.index', 'admin.scholarships.create') ? 'active' : '' }}">
        <a href="#" class="menu-toggle nav-link has-dropdown">
          <i data-feather="mail"></i><span>Scholarships</span>
        </a>
        <ul class="dropdown-menu" style="{{ request()->routeIs('admin.scholarships.index', 'admin.scholarships.create') ? 'display: block;' : '' }}">
          <li class="{{ request()->routeIs('admin.scholarships.index') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.scholarships.index') }}">All Scholarships</a>
          </li>
          <li class="{{ request()->routeIs('admin.scholarships.create') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.scholarships.create') }}">Add Scholarship</a>
          </li>
        </ul>
      </li>

      <!-- Applications -->
      <li class="dropdown {{ request()->routeIs('admin.applications.index') ? 'active' : '' }}">
        <a href="#" class="menu-toggle nav-link has-dropdown">
          <i data-feather="command"></i><span>Application</span>
        </a>
        <ul class="dropdown-menu" style="{{ request()->routeIs('admin.applications.index') ? 'display: block;' : '' }}">
          <li class="{{ request()->routeIs('admin.applications.index') && !request()->has('status') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.applications.index') }}">All Applications</a>
          </li>
          <li class="{{ request()->routeIs('admin.applications.index') && request()->get('status') == 'approved_full' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.applications.index') }}?status=approved_full">Approved Full</a>
          </li>
          <li class="{{ request()->routeIs('admin.applications.index') && request()->get('status') == 'approved_partial' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.applications.index') }}?status=approved_partial">Approved Partial</a>
          </li>
          <li class="{{ request()->routeIs('admin.applications.index') && request()->get('status') == 'rejected' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.applications.index') }}?status=rejected">Rejected</a>
          </li>
        </ul>
      </li>



      <!-- Acknowledgements -->
      <li class="dropdown {{ request()->routeIs('admin.acknowledgement.index', 'admin.acknowledgement.create') ? 'active' : '' }}">
        <a href="#" class="menu-toggle nav-link has-dropdown">
          <i data-feather="briefcase"></i><span>Acknowledgements</span>
        </a>
        <ul class="dropdown-menu" style="{{ request()->routeIs('admin.acknowledgement.index', 'admin.acknowledgement.create') ? 'display: block;' : '' }}">
          <li class="{{ request()->routeIs('admin.acknowledgement.index') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.acknowledgement.index') }}">Manage Acknowledgements</a>
          </li>
          <li class="{{ request()->routeIs('admin.acknowledgement.create') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.acknowledgement.template') }}">Upload Acknowledgement</a>
          </li>
        </ul>
      </li>



      <!-- Students -->
      <li class="dropdown">
        <a href="#" class="menu-toggle nav-link has-dropdown">
          <i data-feather="mail"></i><span>Students</span>
        </a>
        <ul class="dropdown-menu">
          <li><a class="nav-link" href="{{ route('admin.students.index') }}">All Students</a></li>
          {{-- <li><a class="nav-link" href="#">Confirmed Students</a></li>
          <li><a class="nav-link" href="#">Pending Confirmation</a></li>
          <li><a class="nav-link" href="#">Canceled Students</a></li> --}}
        </ul>
      </li>


      <!-- Acknowledgements -->
      <li class="dropdown {{ request()->routeIs('admin.installments.index', 'admin.installments.create') ? 'active' : '' }}">
        <a href="#" class="menu-toggle nav-link has-dropdown">
          <i data-feather="briefcase"></i><span>Installments</span>
        </a>
        <ul class="dropdown-menu" style="{{ request()->routeIs('admin.installments.index', 'admin.installments.create') ? 'display: block;' : '' }}">
          <li class="{{ request()->routeIs('admin.installments.index') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.installments.index') }}">All Installments</a>
          </li>
          <li class="{{ request()->routeIs('admin.installments.create') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.installments.create') }}">Add Installment</a>
          </li>
        </ul>
      </li>
      <li class="{{ request()->routeIs('admin.results.index') ? 'active' : '' }}">
        <a href="{{ route('admin.results.index') }}" class="nav-link">
          <i data-feather="monitor"></i><span>Academic Results</span>
        </a>
      </li>
      </li>

      <!-- Reports & Analytics -->
      <li class="dropdown">
        <a href="#" class="menu-toggle nav-link has-dropdown">
          <i data-feather="shopping-bag"></i><span>Reports & Analytics</span>
        </a>
        <ul class="dropdown-menu">
          <li><a class="nav-link" href="{{ route('admin.reports.index') }}">All Report</a></li>
          <li><a class="nav-link" href="{{ route('admin.reports.application') }}">Application Reports</a></li>
          <li><a class="nav-link" href="{{ route('admin.reports.academic') }}">Academic-Perfomance Reports</a></li>
            <li><a class="nav-link" href="{{ route('admin.reports.financial') }}">Beneficiary-Financial Reports</a></li>
            <li><a class="nav-link" href="{{ route('admin.reports.utilization') }}">Scholarship-Utilization Reports</a></li>
        </ul>
      </li>

      <!-- Support Tickets -->
      <li class="{{ request()->routeIs('admin.support.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.support.index') }}">
          <i data-feather="life-buoy"></i><span>Support</span>
        </a>
      </li>

      <!-- Profile -->
      <li class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('profile.edit') }}">
          <i data-feather="user"></i><span>Profile</span>
        </a>
      </li>

      <!-- Logout -->
      <li>
        <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          <i data-feather="log-out"></i><span>Log Out</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
        </form>
      </li>
    </ul>
  </aside>
</div>
