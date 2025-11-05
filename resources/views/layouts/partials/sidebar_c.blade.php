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
              @if(auth()->user()->pic)
                  <img src="{{ asset('storage/' . auth()->user()->pic) }}" class="img-circle elevation-2" alt="User Image" style="width: 32px; height: 32px; object-fit: cover;">
              @else
                  @php
                      // Generate a consistent random color based on user's name
                      $name = auth()->user()->fname . auth()->user()->lname;
                      $colors = [
                          'linear-gradient(135deg, #007bff, #6610f2)', // Blue to Purple
                          'linear-gradient(135deg, #28a745, #20c997)', // Green to Teal
                          'linear-gradient(135deg, #dc3545, #fd7e14)', // Red to Orange
                          'linear-gradient(135deg, #6f42c1, #e83e8c)', // Purple to Pink
                          'linear-gradient(135deg, #17a2b8, #6f42c1)', // Teal to Purple
                          'linear-gradient(135deg, #fd7e14, #e83e8c)', // Orange to Pink
                      ];
                      
                      // Generate a consistent index based on the user's name
                      $colorIndex = crc32($name) % count($colors);
                      $randomGradient = $colors[$colorIndex];
                  @endphp
                  
                  <div class="img-circle elevation-2 d-flex align-items-center justify-content-center text-white font-weight-bold" 
                      style="width: 32px; height: 32px; font-size: 12px; background: {{ $randomGradient }};">
                      {{ strtoupper(substr(auth()->user()->fname, 0, 1) . substr(auth()->user()->lname, 0, 1)) }}
                  </div>
              @endif
          </div>
          <div class="info">
              <a href="{{ route('coordinator.profile') }}" class="d-block">{{ Auth::user()->fname }} {{ Auth::user()->lname }}</a>
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
            <a href="{{route('coordinator.deployments')}}" class="nav-link {{ Request::is('coordinator/deployment*') ? 'current-page' : '' }}">
              <i class="ph{{ Request::is('coordinator/deployment*') ? '-fill' : '' }} ph-briefcase nav-link-i"></i>
              <p>Deployments</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{route('coordinator.documents')}}" class="nav-link {{ Request::is('coordinator/honorarium*') ? 'current-page' : '' }}">
              <i class="ph{{ Request::is('coordinator/honorarium*') ? '-fill' : '' }} ph-wallet nav-link-i"></i>
              <p>Honorarium</p>
            </a>
          </li>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>