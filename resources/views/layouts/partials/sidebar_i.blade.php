  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a class="brand-link">
      <img src="{{ asset('assets/images/EVSU_Official_Logo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">OJT-CMS</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ auth()->user()->pic ? asset('storage/' . auth()->user()->pic) : asset('profile_pics/profile.jpg') }}" class="img-circle elevation-2" alt="User Image">        </div>
        <div class="info">
          <a href="{{route('intern.profile')}}" class="d-block">{{ Auth::user()->fname }} {{ Auth::user()->lname }}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="{{route('intern.dashboard')}}" class="nav-link {{ Request::is('intern/dashboard') ? 'current-page' : '' }}">
              <i class="ph{{ Request::is('intern/dashboard') ? '-fill' : '' }} ph-squares-four nav-link-i"></i>
              <p>Dashboard</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{route('intern.docs')}}" class="nav-link {{ Request::is('intern/docs') ? 'current-page' : '' }}">
              <i class="ph{{ Request::is('intern/docs') ? '-fill' : '' }} ph-file-text nav-link-i"></i>
              <p>Documents</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{route('intern.journals')}}" class="nav-link {{ Request::is('intern/journal*') ? 'current-page' : '' }}">
              <i class="ph{{ Request::is('intern/journal') ? '-fill' : '' }} ph-notebook nav-link-i"></i>
              <p>Journal</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{route('intern.attendances')}}" class="nav-link {{ Request::is('intern/attendances*') ? 'current-page' : '' }}">
              <i class="ph{{ Request::is('intern/attendances') ? '-fill' : '' }} ph-calendar-dots nav-link-i"></i>
              <p>Attendances</p>
            </a>
          </li>



        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>