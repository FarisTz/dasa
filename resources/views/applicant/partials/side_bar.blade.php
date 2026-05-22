 <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
<<<<<<< HEAD
            <a href="{{ route('dashboard') }}"> <img alt="image" src="assets/img/logo.png" class="header-logo" /> <span
                class="logo-name">DASA</span>
=======
            <a href="{{ route('dashboard') }}"> <img alt="image" src="{{ asset('assets/img/logo.png') }}" class="header-logo" /> <span
                class="logo-name">KAAFAT</span>
>>>>>>> d6279ba2c47c1c0cbe8fd12868a7779624d6bb74
            </a>
          </div>
          <ul class="sidebar-menu">
            <li class="menu-header">Main</li>
<<<<<<< HEAD
            <li class="dropdown active">
              <a href="{{ route('dashboard') }}" class="nav-link"><i data-feather="monitor"></i><span>Dashboard</span></a>
            </li>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i
                  data-feather="briefcase"></i><span>Widgets</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="widget-chart.html">Chart Widgets</a></li>
                <li><a class="nav-link" href="widget-data.html">Data Widgets</a></li>
              </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="command"></i><span>Apps</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="chat.html">Chat</a></li>
                <li><a class="nav-link" href="portfolio.html">Portfolio</a></li>
                <li><a class="nav-link" href="blog.html">Blog</a></li>
                <li><a class="nav-link" href="calendar.html">Calendar</a></li>
              </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="mail"></i><span>Email</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="email-inbox.html">Inbox</a></li>
                <li><a class="nav-link" href="email-compose.html">Compose</a></li>
                <li><a class="nav-link" href="email-read.html">read</a></li>
              </ul>
            </li>
            <li class="menu-header">UI Elements</li>
            <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="copy"></i><span>Basic
                  Components</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="alert.html">Alert</a></li>
                <li><a class="nav-link" href="badge.html">Badge</a></li>
                <li><a class="nav-link" href="breadcrumb.html">Breadcrumb</a></li>
                <li><a class="nav-link" href="buttons.html">Buttons</a></li>
                <li><a class="nav-link" href="collapse.html">Collapse</a></li>
                <li><a class="nav-link" href="dropdown.html">Dropdown</a></li>
                <li><a class="nav-link" href="checkbox-and-radio.html">Checkbox &amp; Radios</a></li>
                <li><a class="nav-link" href="list-group.html">List Group</a></li>
                <li><a class="nav-link" href="media-object.html">Media Object</a></li>
                <li><a class="nav-link" href="navbar.html">Navbar</a></li>
                <li><a class="nav-link" href="pagination.html">Pagination</a></li>
                <li><a class="nav-link" href="popover.html">Popover</a></li>
                <li><a class="nav-link" href="progress.html">Progress</a></li>
                <li><a class="nav-link" href="tooltip.html">Tooltip</a></li>
                <li><a class="nav-link" href="flags.html">Flag</a></li>
                <li><a class="nav-link" href="typography.html">Typography</a></li>
              </ul>
            </li>

            <li><a class="nav-link" href="blank.html"><i data-feather="file"></i><span>Blank Page</span></a></li>
           




=======
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
>>>>>>> d6279ba2c47c1c0cbe8fd12868a7779624d6bb74
          </ul>
        </aside>
      </div>
