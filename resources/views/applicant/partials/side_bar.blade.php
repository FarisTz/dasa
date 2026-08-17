<div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="{{ route('dashboard') }}"> <img alt="image" style="width: 100px;" src="{{ asset('assets/img/dasa.png') }}" class="header-logo" /> <span
                class="logo-name">KAFAAT</span>
            </a>
          </div>
          <ul class="sidebar-menu">
            <li class="menu-header">Main</li>
            <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
              <a href="{{ route('dashboard') }}" class="nav-link"><i data-feather="monitor"></i><span>Dashboard</span></a>
            </li>
            <li class="dropdown {{ request()->routeIs('applicant.personal_information', 'applicant.o-level-education', 'applicant.a-level-education', 'applicant.motivations.index', 'applicant.application.review') ? 'active' : '' }}">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i
                  data-feather="briefcase"></i><span>Apply for scholarship</span></a>
              <ul class="dropdown-menu" style="{{ request()->routeIs('applicant.personal_information', 'applicant.o-level-education', 'applicant.a-level-education', 'applicant.motivations.index', 'applicant.application.review') ? 'display: block;' : '' }}">
                <li class="{{ request()->routeIs('applicant.personal_information') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('applicant.personal_information') }}">
                        <i></i> Personal Information
                    </a>
                </li>
                <li class="{{ request()->routeIs('applicant.o-level-education') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('applicant.o-level-education') }}">
                        <i></i> O-Level Education
                    </a>
                </li>
                <li class="{{ request()->routeIs('applicant.a-level-education') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('applicant.a-level-education') }}">
                        <i ></i> A-Level Education
                    </a>
                </li>
                <li class="{{ request()->routeIs('applicant.motivations.index') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('applicant.motivations.index') }}">
                        <i></i> Motivation Letter
                    </a>
                </li>
                <li class="{{ request()->routeIs('applicant.application.review') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('applicant.application.review') }}">
                        <i></i> Review & Submit
                    </a>
                </li>
              </ul>
            </li>


            <li class="{{ request()->routeIs('applicant.my-application') ? 'active' : '' }}"><a class="nav-link" href="{{ route('applicant.my-application') }}"><i data-feather="file"></i><span>My Applications</span></a></li>

@php
use App\Models\Application;
    $application = Application::where('user_id', auth()->id())
        ->where(function ($query) {
            $query->where('status', 'approved_full')
                  ->orWhere('status', 'approved_partial');
        })
        ->exists();
@endphp

@if($application)
    <li class="{{ request()->routeIs('applicant.acknowledgement-letter') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('applicant.acknowledgement-letter') }}">
            <i data-feather="check-circle"></i>
            <span>Acknowledgement Letter</span>
        </a>
    </li>
@endif
            <li class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}"><a class="nav-link" href="{{ route('profile.edit') }}"><i data-feather="user"></i><span>Profile</span></a></li>
            <li><a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i data-feather="log-out"></i><span>Logout</span></a></li>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
          </ul>
        </aside>
      </div>
