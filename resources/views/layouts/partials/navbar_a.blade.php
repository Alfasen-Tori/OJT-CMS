<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <!-- Dark Mode Toggle -->
    <li class="nav-item">
      <a class="nav-link" href="#" id="darkModeToggle" role="button" title="Toggle Dark Mode">
        <i class="ph-fill ph-moon custom-icons-i" id="darkModeIcon"></i>
      </a>
    </li>
    
    <li class="nav-item dropdown">
        <a class="nav-link d-flex align-items-center" data-toggle="dropdown" href="#" aria-expanded="false">
            <span class="mr-2 d-none d-sm-inline">{{ Auth::user()->fname }} {{ Auth::user()->lname }}</span>
            @if(auth()->user()->pic)
                <img src="{{ asset('storage/' . auth()->user()->pic) }}" class="img-circle elevation-2 border border-light" alt="User Image" style="width: 32px; height: 32px; object-fit: cover;">
            @else
                @php
                    $name = auth()->user()->fname . auth()->user()->lname;
                    $colors = [
                        'linear-gradient(135deg, #007bff, #6610f2)',
                        'linear-gradient(135deg, #28a745, #20c997)',
                        'linear-gradient(135deg, #dc3545, #fd7e14)',
                        'linear-gradient(135deg, #6f42c1, #e83e8c)',
                        'linear-gradient(135deg, #17a2b8, #6f42c1)',
                        'linear-gradient(135deg, #fd7e14, #e83e8c)',
                    ];
                    $colorIndex = crc32($name) % count($colors);
                    $randomGradient = $colors[$colorIndex];
                @endphp
                <div class="img-circle elevation-2 border border-light d-flex align-items-center justify-content-center text-white font-weight-bold" 
                    style="width: 32px; height: 32px; font-size: 12px; background: {{ $randomGradient }};">
                    {{ strtoupper(substr(auth()->user()->fname, 0, 1) . substr(auth()->user()->lname, 0, 1)) }}
                </div>
            @endif
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right shadow border-0" style="min-width: 220px;">
            <div class="px-3 py-2 text-center border-bottom">
                @if(auth()->user()->pic)
                    <img src="{{ asset('storage/' . auth()->user()->pic) }}" class="img-circle elevation-2 mb-2" width="60" height="60" alt="Profile Picture">
                @else
                    @php
                        $name = auth()->user()->fname . auth()->user()->lname;
                        $colors = [
                            'linear-gradient(135deg, #007bff, #6610f2)',
                            'linear-gradient(135deg, #28a745, #20c997)',
                            'linear-gradient(135deg, #dc3545, #fd7e14)',
                            'linear-gradient(135deg, #6f42c1, #e83e8c)',
                            'linear-gradient(135deg, #17a2b8, #6f42c1)',
                            'linear-gradient(135deg, #fd7e14, #e83e8c)',
                        ];
                        $colorIndex = crc32($name) % count($colors);
                        $randomGradient = $colors[$colorIndex];
                    @endphp
                    <div class="img-circle elevation-2 mb-2 d-flex align-items-center justify-content-center text-white font-weight-bold mx-auto" 
                        style="width: 60px; height: 60px; font-size: 20px; background: {{ $randomGradient }};">
                        {{ strtoupper(substr(auth()->user()->fname, 0, 1) . substr(auth()->user()->lname, 0, 1)) }}
                    </div>
                @endif
                <h6 class="mb-0">{{ Auth::user()->fname }} {{ Auth::user()->lname }}</h6>
                <small class="text-muted">Administrator</small>
            </div>
            
            <a href="{{ route('admin.profile') }}" class="dropdown-item d-flex align-items-center py-2">
                <i class="ph ph-user-gear custom-icons-i mr-2"></i>Manage Profile
            </a>
            
            <div class="dropdown-divider my-1"></div>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item d-flex align-items-center py-2 text-danger border-0 w-100 bg-transparent">
                    <i class="ph ph-sign-out custom-icons-i mr-2"></i>Sign Out
                </button>
            </form>
        </div>
    </li>
  </ul>
</nav>
<!-- /.navbar -->

<script>
document.addEventListener('DOMContentLoaded', function() {
  const darkModeToggle = document.getElementById('darkModeToggle');
  const darkModeIcon = document.getElementById('darkModeIcon');
  const html = document.documentElement;

  // Check current theme and update icon
  function updateDarkModeIcon() {
    const isDarkMode = html.classList.contains('dark-mode');
    darkModeIcon.classList.toggle('ph-moon', !isDarkMode);
    darkModeIcon.classList.toggle('ph-sun', isDarkMode);
  }

  // Toggle dark mode
  function toggleDarkMode() {
    const isDarkMode = html.classList.contains('dark-mode');
    
    if (isDarkMode) {
      html.classList.remove('dark-mode');
      localStorage.setItem('theme', 'light');
    } else {
      html.classList.add('dark-mode');
      localStorage.setItem('theme', 'dark');
    }
    
    updateDarkModeIcon();
    refreshDataTables();
  }

  function refreshDataTables() {
    if (typeof $.fn.DataTable !== 'undefined') {
      const tables = $.fn.dataTable.tables();
      if (tables.length > 0) {
        // Force a complete redraw and re-styling of DataTables
        tables.each(function() {
          const table = $(this).DataTable();
          if (table) {
            // Destroy and reinitialize to force theme application
            table.destroy();
            $(this).DataTable(getDataTablesConfig());
          }
        });
      }
    }
  }

  // DataTables configuration function
  function getDataTablesConfig() {
    return {
      language: {
        search: "_INPUT_",
        searchPlaceholder: "Search...",
      },
      dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
           "<'row'<'col-sm-12'tr>>" +
           "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
      drawCallback: function() {
        // Force theme application on draw
        applyDataTablesTheme();
      },
      initComplete: function() {
        // Apply theme immediately after initialization
        applyDataTablesTheme();
      }
    };
  }

  // Apply DataTables theme based on current mode
  function applyDataTablesTheme() {
    const isDarkMode = html.classList.contains('dark-mode');
    $('table.dataTable').each(function() {
      const $table = $(this);
      const $wrapper = $table.closest('.dataTables_wrapper');
      
      if (isDarkMode) {
        $table.addClass('dark-mode-initialized');
        $wrapper.addClass('dark-mode-initialized');
      } else {
        $table.removeClass('dark-mode-initialized');
        $wrapper.removeClass('dark-mode-initialized');
      }
    });
  }

  // Initialize DataTables after ensuring dark mode is applied
  function initializeDataTables() {
    if (typeof $.fn.DataTable === 'function') {
      // Small delay to ensure DOM is ready and dark mode is applied
      setTimeout(() => {
        $('table.dataTable:not(.dataTable)').each(function() {
          $(this).DataTable(getDataTablesConfig());
        });
        applyDataTablesTheme();
      }, 100);
    }
  }

  // Initialize on page load
  updateDarkModeIcon();
  
  // Initialize DataTables after a short delay to ensure dark mode is applied first
  initializeDataTables();

  // Add event listener
  darkModeToggle.addEventListener('click', function(e) {
    e.preventDefault();
    toggleDarkMode();
  });
});
</script>