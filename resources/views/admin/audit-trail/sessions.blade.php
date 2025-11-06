{{-- resources/views/admin/audit-trail/sessions.blade.php --}}
@extends('layouts.admin')

@section('title', 'Audit Trailing : Sessions')

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="page-header">SESSION AUDIT TRAIL</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item fw-medium">Admin</li>
          <li class="breadcrumb-item active text-muted">Audit Trailing - Sessions</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">User Session Logs</h3>
            
            <div class="card-tools">
              <div class="input-group input-group-sm">
                <input type="text" class="form-control" placeholder="Search..." id="searchInput">
                <div class="input-group-append">
                  <button class="btn btn-primary" id="searchBtn">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
          
          <div class="card-body">
            <!-- Filters -->
            <div class="row mb-3">
              <div class="col-md-3">
                <select class="form-control" id="userTypeFilter">
                  <option value="">All</option>
                  <option value="admin">Admin</option>
                  <option value="coordinator">Coordinator</option>
                  <option value="intern">Intern</option>
                  <option value="hte">HTE</option>
                </select>
              </div>
              <div class="col-md-3">
                <input type="date" class="form-control" id="dateFromFilter" placeholder="Date From">
              </div>
              <div class="col-md-3">
                <input type="date" class="form-control" id="dateToFilter" placeholder="Date To">
              </div>
              <div class="col-md-3">
                <button class="btn btn-secondary w-100" id="resetFilters">
                  <i class="fas fa-redo"></i> Reset Filters
                </button>
              </div>
            </div>

            <!-- Session Logs Table -->
            <div class="table-responsive">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>User</th>
                    <th>User Type</th>
                    <th>Login Time</th>
                    <th>Logout Time</th>
                    <th>Duration</th>
                    <th>IP Address</th>
                    <th>User Agent</th>
                  </tr>
                </thead>
                <tbody id="sessionsTableBody">
                  <!-- Data will be loaded via AJAX -->
                </tbody>
              </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
              <div id="paginationInfo"></div>
              <nav>
                <ul class="pagination pagination-sm" id="paginationLinks">
                  <!-- Pagination links will be loaded via AJAX -->
                </ul>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@include('layouts.partials.scripts')

<script>
$(document).ready(function() {
    let currentPage = 1;
    let currentFilters = {};

    // Load initial data
    loadSessionData();

    // Search functionality
    $('#searchBtn').click(function() {
        currentPage = 1;
        loadSessionData();
    });

    $('#searchInput').keypress(function(e) {
        if (e.which === 13) {
            currentPage = 1;
            loadSessionData();
        }
    });

    // Filter functionality
    $('#userTypeFilter, #dateFromFilter, #dateToFilter').change(function() {
        currentPage = 1;
        loadSessionData();
    });

    // Reset filters
    $('#resetFilters').click(function() {
        $('#userTypeFilter').val('');
        $('#dateFromFilter').val('');
        $('#dateToFilter').val('');
        $('#searchInput').val('');
        currentPage = 1;
        currentFilters = {};
        loadSessionData();
    });

    function loadSessionData() {
        const filters = {
            user_type: $('#userTypeFilter').val(),
            date_from: $('#dateFromFilter').val(),
            date_to: $('#dateToFilter').val(),
            search: $('#searchInput').val()
        };

        currentFilters = filters;

        $.ajax({
            url: '{{ route("admin.audit-trail.sessions.data") }}',
            type: 'GET',
            data: {
                ...filters,
                page: currentPage
            },
            success: function(response) {
                updateTable(response.data);
                updatePagination(response);
            },
            error: function(xhr) {
                console.error('Error loading session data:', xhr);
                alert('Error loading session data. Please try again.');
            }
        });
    }

    function updateTable(sessions) {
        const tbody = $('#sessionsTableBody');
        tbody.empty();

        if (sessions.length === 0) {
            tbody.append(`
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="fas fa-info-circle fa-2x mb-2"></i><br>
                        No session logs found matching your criteria.
                    </td>
                </tr>
            `);
            return;
        }

        sessions.forEach(session => {
            const loginTime = session.login_at ? new Date(session.login_at).toLocaleString() : 'N/A';
            const logoutTime = session.logout_at ? new Date(session.logout_at).toLocaleString() : 'N/A';
            
            // Calculate duration manually if not available from server
            let duration = 'N/A';
            if (session.login_at && session.logout_at) {
                const login = new Date(session.login_at);
                const logout = new Date(session.logout_at);
                const diffMs = logout - login;
                const diffSecs = Math.floor(diffMs / 1000);
                
                const hours = Math.floor(diffSecs / 3600);
                const minutes = Math.floor((diffSecs % 3600) / 60);
                const seconds = diffSecs % 60;
                
                if (hours > 0) {
                    duration = `${hours}h ${minutes}m ${seconds}s`;
                } else if (minutes > 0) {
                    duration = `${minutes}m ${seconds}s`;
                } else {
                    duration = `${seconds}s`;
                }
            } else if (session.session_duration) {
                // Use server-calculated duration if available
                const hours = Math.floor(session.session_duration / 3600);
                const minutes = Math.floor((session.session_duration % 3600) / 60);
                const seconds = session.session_duration % 60;
                
                if (hours > 0) {
                    duration = `${hours}h ${minutes}m ${seconds}s`;
                } else if (minutes > 0) {
                    duration = `${minutes}m ${seconds}s`;
                } else {
                    duration = `${seconds}s`;
                }
            }
            
            const userDisplayName = session.user ? 
                `${session.user.fname} ${session.user.lname}` : 'Unknown User';

            const userTypeBadge = getUserTypeBadge(session.user_type);

            tbody.append(`
                <tr>
                    <td>${userDisplayName}</td>
                    <td>${userTypeBadge}</td>
                    <td>${loginTime}</td>
                    <td>${logoutTime}</td>
                    <td>${duration}</td>
                    <td><code>${session.ip_address || 'N/A'}</code></td>
                    <td class="small text-muted">${session.user_agent || 'N/A'}</td>
                </tr>
            `);
        });
    }

    function getUserTypeBadge(userType) {
        const badges = {
            'admin': 'badge badge-danger',
            'coordinator': 'badge badge-success',
            'intern': 'badge badge-primary',
            'hte': 'badge badge-warning'
        };
        
        const badgeClass = badges[userType] || 'badge badge-secondary';
        return `<span class="${badgeClass}">${userType.toUpperCase()}</span>`;
    }

    function updatePagination(response) {
        const paginationLinks = $('#paginationLinks');
        const paginationInfo = $('#paginationInfo');
        
        paginationLinks.empty();
        paginationInfo.empty();

        // Pagination info
        const start = (response.current_page - 1) * response.per_page + 1;
        const end = Math.min(response.current_page * response.per_page, response.total);
        paginationInfo.text(`Showing ${start} to ${end} of ${response.total} entries`);

        // Previous page
        const prevDisabled = response.current_page === 1 ? 'disabled' : '';
        paginationLinks.append(`
            <li class="page-item ${prevDisabled}">
                <a class="page-link" href="#" data-page="${response.current_page - 1}">Previous</a>
            </li>
        `);

        // Page numbers
        for (let i = 1; i <= response.last_page; i++) {
            const active = i === response.current_page ? 'active' : '';
            paginationLinks.append(`
                <li class="page-item ${active}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>
            `);
        }

        // Next page
        const nextDisabled = response.current_page === response.last_page ? 'disabled' : '';
        paginationLinks.append(`
            <li class="page-item ${nextDisabled}">
                <a class="page-link" href="#" data-page="${response.current_page + 1}">Next</a>
            </li>
        `);

        // Add click handlers
        $('.page-link').click(function(e) {
            e.preventDefault();
            const page = $(this).data('page');
            if (page && page !== currentPage) {
                currentPage = page;
                loadSessionData();
            }
        });
    }
});
</script>
@endsection



