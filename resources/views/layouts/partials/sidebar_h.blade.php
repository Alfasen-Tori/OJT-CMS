  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <p class="brand-link">
      <img src="{{ asset('assets/images/EVSU_Official_Logo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text fw-medium">OJT-CMS</span>
    </p>

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
              <a href="{{ route('hte.profile') }}" class="d-block">{{ Auth::user()->fname }} {{ Auth::user()->lname }}</a>
          </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

          <li class="nav-item">
            <a href="{{route('hte.dashboard')}}" class="nav-link {{ Request::is('hte/dashboard') ? 'current-page' : '' }}">
              <i class="ph{{ Request::is('hte/dashboard') ? '-fill' : '' }} ph-squares-four nav-link-i"></i>
              <p class="me-1">Dashboard</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{route('hte.moa')}}" class="nav-link {{ Request::is('hte/moa*') ? 'current-page' : '' }}">
              <i class="ph{{ Request::is('hte/moa') ? '-fill' : '' }} ph-signature nav-link-i"></i>
              <p class="me-1">MOA</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{route('hte.interns')}}" class="nav-link {{ Request::is('hte/intern*') ? 'current-page' : '' }}">
              <i class="ph{{ Request::is('hte/interns') ? '-fill' : '' }} ph-graduation-cap nav-link-i"></i>
              <p class="me-1">Interns</p>
            </a>
          </li>



          <!-- <li class="nav-item">
            <a href="/my-internship" class="nav-link {{ Request::is('my-internship') ? 'current-page' : '' }}">

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