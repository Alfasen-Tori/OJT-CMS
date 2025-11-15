<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <p class="brand-link">
        <img src="{{ asset('assets/images/EVSU_Official_Logo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">OJT-CMS</span>
    </p>

    <!-- Sidebar -->
    <div class="sidebar">
    <!-- Sidebar user (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            @if(Auth::user()->pic)
                <img src="{{ asset('storage/' . Auth::user()->pic) }}" class="img-circle elevation-2" alt="User Image" style="object-fit: cover; width: 33px; height: 33px;">
            @else
                @php
                    // Generate a consistent random color based on user's name
                    $name = Auth::user()->fname . Auth::user()->lname;
                    $colors = [
                        'linear-gradient(135deg, #007bff, #6610f2)', // Blue to Purple
                        'linear-gradient(135deg, #28a745, #20c997)', // Green to Teal
                        'linear-gradient(135deg, #dc3545, #fd7e14)', // Red to Orange
                        'linear-gradient(135deg, #6f42c1, #e83e8c)', // Purple to Pink
                        'linear-gradient(135deg, #17a2b8, #6f42c1)', // Teal to Purple
                        'linear-gradient(135deg, #fd7e14, #e83e8c)', // Orange to Pink
                        'linear-gradient(135deg, #20c997, #28a745)', // Teal to Green
                        'linear-gradient(135deg, #6610f2, #007bff)', // Purple to Blue
                    ];
                    
                    // Generate a consistent index based on the user's name
                    $colorIndex = crc32($name) % count($colors);
                    $randomGradient = $colors[$colorIndex];
                @endphp
                
                <div class="img-circle elevation-2 d-flex align-items-center justify-content-center text-white font-weight-bold" 
                    style="width: 33px; height: 33px; font-size: 12px; background: {{ $randomGradient }};">
                    {{ strtoupper(substr(Auth::user()->fname, 0, 1) . substr(Auth::user()->lname, 0, 1)) }}
                </div>
            @endif
        </div>
        <div class="info">
            <a href="{{ route('admin.profile') }}" class="d-block">{{ Auth::user()->fname }} {{ Auth::user()->lname }}</a>
        </div>
    </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                    <a href="{{route('admin.dashboard')}}" class="nav-link {{ Request::is('admin/dashboard') ? 'current-page' : '' }}">
                        <i class="ph{{ Request::is('admin/dashboard') ? '-fill' : '' }} ph-squares-four nav-link-i "></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('admin.coordinators')}}" class="nav-link {{ Request::is('admin/coordinators*') ? 'current-page' : '' }}">
                        <i class="ph{{ Request::is('admin/coordinators*') ? '-fill' : '' }} ph-chalkboard-teacher nav-link-i "></i>
                        <p>Coordinators</p>
                    </a>
                </li>

                <!-- Logs Treeview Menu -->
                <li class="nav-item {{ Request::is('admin/logs*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::is('admin/logs*') ? 'active' : '' }}">
                        <i class="ph{{ Request::is('admin/logs*') ? '-fill' : '' }} ph-clipboard-text nav-link-i"></i>
                        <p>
                            Audit Trail
                            <i class="right ph ph-caret-down"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('admin.audit-trail.sessions')}}" class="nav-link {{ Request::is('admin/logs/users') ? 'active' : '' }}">
                                <p>Sessions</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('admin.audit-trail.users')}}" class="nav-link {{ Request::is('admin/logs/users') ? 'active' : '' }}">
                                <p>User Management</p>
                            </a>
                        </li>
                        <!-- <li class="nav-item">
                            <a href="" class="nav-link {{ Request::is('admin/logs/deployments') ? 'active' : '' }}">
                                <p>Deployments</p>
                            </a>
                        </li> -->
                    </ul>
                </li>

                <!-- Old Logs Link (commented out for reference) -->
                <!--
                <li class="nav-item">
                    <a href="logs" class="nav-link {{ Request::is('admin/logs') ? 'current-page' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="nav-link-icon" viewBox="0 0 256 256"><path d="M208,32H83.31A15.86,15.86,0,0,0,72,36.69L36.69,72A15.86,15.86,0,0,0,32,83.31V208a16,16,0,0,0,16,16H208a16,16,0,0,0,16-16V48A16,16,0,0,0,208,32ZM128,184a32,32,0,1,1,32-32A32,32,0,0,1,128,184ZM172,80a4,4,0,0,1-4,4H88a4,4,0,0,1-4-4V48h88Z"></path></svg>
                        <p>Logs</p>
                    </a>
                </li>
                -->

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>