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
          <img src="{{ asset('storage/' . auth()->user()->pic) }}" class="img-circle elevation-2" alt="User Image">
        </div>
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
            <a href="{{route('intern.journals')}}" class="nav-link {{ Request::is('intern/journals*') ? 'current-page' : '' }}">
              <i class="ph{{ Request::is('intern/journals') ? '-fill' : '' }} ph-notebook nav-link-i"></i>
              <p>Weekly Journal</p>
            </a>
          </li>

          <!-- <li class="nav-item">
            <a href="{{route('intern.docs')}}" class="nav-link {{ Request::is('my-internship') ? 'current-page' : '' }}">

                <svg xmlns="http://www.w3.org/2000/svg" class="nav-link-icon" viewBox="0 0 256 256"><path d="M152,112a8,8,0,0,1-8,8H112a8,8,0,0,1,0-16h32A8,8,0,0,1,152,112Zm80-40V200a16,16,0,0,1-16,16H40a16,16,0,0,1-16-16V72A16,16,0,0,1,40,56H80V48a24,24,0,0,1,24-24h48a24,24,0,0,1,24,24v8h40A16,16,0,0,1,232,72ZM96,56h64V48a8,8,0,0,0-8-8H104a8,8,0,0,0-8,8Zm120,57.61V72H40v41.61A184,184,0,0,0,128,136,184,184,0,0,0,216,113.61Z"></path></svg>
                <p>Internship</p>
            </a>
          </li> -->

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>