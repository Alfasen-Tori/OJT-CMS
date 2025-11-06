{{-- resources/views/admin/audit-trail/users.blade.php --}}
@extends('layouts.admin')

@section('title', 'Audit Trailing : User Management')

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="page-header">USER MANAGEMENT AUDIT TRAIL</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item fw-medium">Admin</li>
          <li class="breadcrumb-item active text-muted">Audit Trailing - User Management</li>
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
            <h3 class="card-title">User Management Activities</h3>
            
            <div class="card-tools">
              <div class="input-group input-group-sm">
                <input type="text" class="form-control" placeholder="Search activities..." id="searchInput">
                <div class="input-group-append">
                  <button class="btn btn-primary" id="searchBtn">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
          
          <div class="card-body">
            <!-- Simple Filters -->
            <div class="row mb-3">
              <div class="col-md-3">
                <select class="form-control" id="actionFilter">
                  <option value="">All Actions</option>
                  <option value="created">User Created</option>
                  <option value="updated">Profile Updated</option>
                  <option value="role_assigned">Role Assigned</option>
                  <option value="deleted">User Deleted</option>
                </select>
              </div>
              <div class="col-md-3">
                <select class="form-control" id="userTypeFilter">
                  <option value="">All Performer Types</option>
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
                <button class="btn btn-secondary w-100" id="resetFilters">
                  <i class="fas fa-redo"></i> Reset Filters
                </button>
              </div>
            </div>

            <!-- User Activities Table -->
            <div class="table-responsive">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Timestamp</th>
                    <th>Performed By</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>IP Address</th>
                  </tr>
                </thead>
                <tbody id="usersTableBody">
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

    // Load initial data
    loadUserData();

    // Search functionality
    $('#searchBtn').click(function() {
        currentPage = 1;
        loadUserData();
    });

    $('#searchInput').keypress(function(e) {
        if (e.which === 13) {
            currentPage = 1;
            loadUserData();
        }
    });

    // Filter functionality
    $('#actionFilter, #userTypeFilter, #dateFromFilter').change(function() {
        currentPage = 1;
        loadUserData();
    });

    // Reset filters
    $('#resetFilters').click(function() {
        $('#actionFilter').val('');
        $('#userTypeFilter').val('');
        $('#dateFromFilter').val('');
        $('#searchInput').val('');
        currentPage = 1;
        loadUserData();
    });

    function loadUserData() {
        const filters = {
            action: $('#actionFilter').val(),
            user_type: $('#userTypeFilter').val(),
            date_from: $('#dateFromFilter').val(),
            search: $('#searchInput').val()
        };

        $.ajax({
            url: '{{ route("admin.audit-trail.users.data") }}',
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
                console.error('Error loading user data:', xhr);
                alert('Error loading user management activities. Please try again.');
            }
        });
    }

    function updateTable(activities) {
        const tbody = $('#usersTableBody');
        tbody.empty();

        if (activities.length === 0) {
            tbody.append(`
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        <i class="fas fa-info-circle fa-2x mb-2"></i><br>
                        No user management activities found.
                    </td>
                </tr>
            `);
            return;
        }

        activities.forEach(activity => {
            const actionBadge = getActionBadge(activity.action);
            const timestamp = new Date(activity.created_at).toLocaleString();

            tbody.append(`
                <tr>
                    <td class="small">${timestamp}</td>
                    <td>
                        <div>${activity.user_display_name}</div>
                        <small class="text-muted">${activity.user_type}</small>
                    </td>
                    <td>${actionBadge}</td>
                    <td>${activity.changes}</td>
                    <td><code class="small">${activity.ip_address || 'N/A'}</code></td>
                </tr>
            `);
        });
    }

    function getActionBadge(action) {
        const badges = {
            'created': 'badge badge-success',
            'updated': 'badge badge-primary', 
            'role_assigned': 'badge badge-info',
            'deleted': 'badge badge-danger'
        };
        
        const badgeClass = badges[action] || 'badge badge-secondary';
        const actionText = action.replace('_', ' ').toUpperCase();
        return `<span class="${badgeClass}">${actionText}</span>`;
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
                loadUserData();
            }
        });
    }
});
</script>
@endsection



