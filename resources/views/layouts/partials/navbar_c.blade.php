  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <!-- <li class="nav-item d-none d-sm-inline-block">
        <a href="assets/index3.html" class="nav-link">Home</a>
      </li> -->
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link d-flex align-items-center" data-toggle="dropdown" href="#" aria-expanded="false">
                <span class="mr-2 d-none d-sm-inline">{{ Auth::user()->fname }} {{ Auth::user()->lname }}</span>
                @if(auth()->user()->pic)
                    <img src="{{ asset('storage/' . auth()->user()->pic) }}" class="img-circle elevation-2 border border-light" alt="User Image" style="width: 32px; height: 32px; object-fit: cover;">
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
                    
                    <div class="img-circle elevation-2 border border-light d-flex align-items-center justify-content-center text-white font-weight-bold" 
                        style="width: 32px; height: 32px; font-size: 12px; background: {{ $randomGradient }};">
                        {{ strtoupper(substr(auth()->user()->fname, 0, 1) . substr(auth()->user()->lname, 0, 1)) }}
                    </div>
                @endif
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right shadow overflow-hidden" style="min-width: 220px;">
                <div class="px-3 py-2 text-center border-bottom">
                    @if(auth()->user()->pic)
                        <img src="{{ asset('storage/' . auth()->user()->pic) }}" class="img-circle elevation-2 mb-2" width="60" height="60" alt="Profile Picture">
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
                        
                        <div class="img-circle elevation-2 mb-2 mx-auto d-flex align-items-center justify-content-center text-white font-weight-bold" 
                            style="width: 60px; height: 60px; font-size: 20px; background: {{ $randomGradient }};">
                            {{ strtoupper(substr(auth()->user()->fname, 0, 1) . substr(auth()->user()->lname, 0, 1)) }}
                        </div>
                    @endif
                    <h6 class="mb-0">{{ Auth::user()->fname }} {{ Auth::user()->lname }}</h6>
                    <small class="text-muted">Coordinator</small>
                </div>
                
                <a href="" class="py-2 btn btn-outline-light btn-flat border-0 w-100 text-left text-dark">
                    <i class="ph ph-user-gear custom-icons-i mr-2 text-primary"></i>Manage Profile
                </a>
                
                <div class="dropdown-divider"></div>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="py-2 btn btn-outline-light btn-flat border-0 w-100 text-left text-danger">
                        <i class="ph ph-sign-out custom-icons-i mr-2"></i>Sign Out
                    </button>
                </form>
            </div>
        </li>
    </ul>

  </nav>
  <!-- /.navbar -->