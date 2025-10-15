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
          <a href="#" class="d-block">{{ Auth::user()->fname }} {{ Auth::user()->lname }}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="{{route('coordinator.dashboard')}}" class="nav-link {{ Request::is('coordinator/dashboard') ? 'current-page' : '' }}">
              <i class="ph{{ Request::is('coordinator/dashboard') ? '-fill' : '' }} ph-squares-four nav-link-i "></i>
              <p>Dashboard</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{route('coordinator.interns')}}" class="nav-link {{ Request::is('coordinator/interns*') ? 'current-page' : '' }}">
              <i class="ph{{ Request::is('coordinator/interns*') ? '-fill' : '' }} ph-graduation-cap nav-link-i"></i>                
              <p>Interns</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{route('coordinator.htes')}}" class="nav-link {{ Request::is('coordinator/htes*') ? 'current-page' : '' }}">
              <i class="ph{{ Request::is('coordinator/htes*') ? '-fill' : '' }} ph-building-apartment nav-link-i"></i>
              <p>HTEs</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{route('coordinator.endorse')}}" class="nav-link {{ Request::is('coordinator/endorse') ? 'current-page' : '' }}">
              <i class="ph{{ Request::is('coordinator/endorse*') ? '-fill' : '' }} ph-paper-plane-tilt nav-link-i"></i>
              <p>Endorse</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{route('coordinator.deployments')}}" class="nav-link {{ Request::is('coordinator/deployments') ? 'current-page' : '' }}">
              <i class="ph{{ Request::is('coordinator/deployments*') ? '-fill' : '' }} ph-briefcase nav-link-i"></i>
              <p> Deployments</p>
            </a>
          </li>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>