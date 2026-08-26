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


            <li class=" {{ request()->routeIs('beneficiary.payments.index') ? 'active' : '' }}" ><a class="dropdown" href="{{ route('beneficiary.payments.index') }}"><i data-feather="file"></i><span>Payments</span></a></li>

            <li class=" {{ request()->routeIs('beneficiary.results.index') ? 'active' : '' }}" ><a class="dropdown" href="{{ route('beneficiary.results.index') }}"><i data-feather="file"></i><span>Academic Results</span></a></li>
            <li class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}" ><a class="dropdown" href="{{ route('profile.edit') }}"><i data-feather="file"></i><span>Profile</span></a></li>
            <li class="{{ request()->routeIs('beneficiary.support*') ? 'active' : '' }}" ><a class="dropdown" href="{{ route('beneficiary.support') }}"><i data-feather="file"></i><span>Support</span></a></li>
            <li><a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i data-feather="file"></i><span>Logout</span></a></li>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>





          </ul>
        </aside>
      </div>
