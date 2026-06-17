 <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="{{ route('dashboard') }}"> <img alt="image" width="100px" height="100px"  src="{{ asset('assets/img/dasa.png') }}" class="header-logo" /> <span
                class="logo-name">KAAFAT</span>
            </a>
          </div>
          <ul class="sidebar-menu">
            <li class="menu-header">Main</li>
            <li class="dropdown active">
              <a href="{{ route('dashboard') }}" class="nav-link"><i data-feather="monitor"></i><span>Dashboard</span></a>
            </li>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i
                  data-feather="briefcase"></i><span>User</span></a>

              <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{ route('admin.users.index') }}">Manage user</a></li>
                <li><a class="nav-link" href="{{ route('admin.users.create') }}">Add User</a></li>
              </ul>
            </li>


            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="mail"></i><span>Scholarships</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{ route('admin.scholarships.index') }}">All Scholarships</a></li>
                <li><a class="nav-link" href="{{ route('admin.scholarships.create') }}">Add Scholarship</a></li>

              </ul>
            </li>


            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="command"></i><span>Applcation</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{ route('admin.applications.index') }}">All Applications</a></li>
                <li><a class="nav-link" href="{{ route('admin.applications.index') }}?status=approved">Approved</a></li>
                <li><a class="nav-link" href="{{ route('admin.applications.index') }}?status=rejected">Rejected</a></li>

              </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="mail"></i><span>Students</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="email-inbox.html">- All Students</a></li>
                <li><a class="nav-link" href="email-compose.html">Confirmed Students</a></li>
                <li><a class="nav-link" href="email-compose.html">Pending Confirmation</a></li>
                <li><a class="nav-link" href="email-read.html">Canceled Students</a></li>
              </ul>
            </li>


            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i
                  data-feather="shopping-bag"></i><span>Reports & Analytics</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="avatar.html">Application Report</a></li>
                <li><a class="nav-link" href="card.html">Selection Reports</a></li>
                <li><a class="nav-link" href="modal.html">Student Reports</a></li>

              </ul>
            </li>
            <li><a class="nav-link" href="blank.html"><i data-feather="file"></i><span>Profile</span></a></li>
            <li><a class="nav-link" href="blank.html"><i data-feather="file"></i><span>Log Out</span></a></li>




              </ul>
            </li>


          </ul>
        </aside>
      </div>
