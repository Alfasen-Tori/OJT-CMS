  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light px-sm-2 px-0">
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
                <img src="{{ auth()->user()->pic ? asset('storage/' . auth()->user()->pic) : asset('profile_pics/profile.jpg') }}" class="img-circle elevation-2 border border-light" alt="User Image" style="width: 32px; height: 32px; object-fit: cover;">
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right shadow" style="min-width: 220px;">
                <div class="px-3 py-2 text-center border-bottom">
                    <img src="{{ auth()->user()->pic ? asset('storage/' . auth()->user()->pic) : asset('profile_pics/profile.jpg') }}" alt="Profile Picture" class="img-circle elevation-2 mb-2" width="60" height="60" alt="Profile Picture">
                    <h6 class="mb-0">{{ Auth::user()->fname }} {{ Auth::user()->lname }}</h6>
                    <small class="text-muted">Student</small>
                </div>
                
                <a href="{{ route('intern.profile') }}" class="py-2 btn btn-outline-light btn-flat border-0 w-100 text-left text-dark">
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