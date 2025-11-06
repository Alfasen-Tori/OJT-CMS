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
    $('#actionFilter, #userTypeFilter, #dateFromFilter, #dateToFilter').change(function() {
        currentPage = 1;
        loadUserData();
    });

    // Reset filters
    $('#resetFilters').click(function() {
        $('#actionFilter').val('');
        $('#userTypeFilter').val('');
        $('#dateFromFilter').val('');
        $('#dateToFilter').val('');
        $('#searchInput').val('');
        currentPage = 1;
        loadUserData();
    });

    function loadUserData() {
        const filters = {
            action: $('#actionFilter').val(),
            user_type: $('#userTypeFilter').val(),
            date_from: $('#dateFromFilter').val(),
            date_to: $('#dateToFilter').val(),
            search: $('#searchInput').val()
        };

        // Show loading state
        $('#usersTableBody').html(`
            <tr>
                <td colspan="5" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <div class="mt-2">Loading user activities...</div>
                </td>
            </tr>
        `);

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
                $('#usersTableBody').html(`
                    <tr>
                        <td colspan="5" class="text-center text-danger py-4">
                            <i class="fas fa-exclamation-triangle fa-2x mb-2"></i><br>
                            Error loading user activities. Please try again.
                        </td>
                    </tr>
                `);
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
                        <i class="fas fa-search fa-2x mb-2"></i><br>
                        No user management activities found matching your criteria.
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
                    <td class="small" title="${new Date(activity.created_at).toString()}">
                        ${timestamp}
                    </td>
                    <td>
                        <div class="font-weight-bold">${activity.user_display_name}</div>
                        <small class="text-muted">${getUserTypeDisplay(activity.user_type)}</small>
                    </td>
                    <td>${actionBadge}</td>
                    <td>
                        <div class="activity-changes">${activity.changes}</div>
                        ${renderValueChanges(activity.old_values, activity.new_values)}
                    </td>
                    <td><code class="small text-muted">${activity.ip_address || 'N/A'}</code></td>
                </tr>
            `);
        });
    }

    function renderValueChanges(oldValues, newValues) {
        if (!oldValues || !newValues) return '';
        
        let changesHtml = '<div class="mt-1 small">';
        
        Object.keys(newValues).forEach(key => {
            const oldVal = oldValues[key] || 'empty';
            const newVal = newValues[key];
            
            if (oldVal !== newVal) {
                changesHtml += `
                    <div class="change-item">
                        <span class="text-muted">${formatKey(key)}:</span> 
                        <span class="text-danger"><s>${formatValue(oldVal)}</s></span> → 
                        <span class="text-success">${formatValue(newVal)}</span>
                    </div>
                `;
            }
        });
        
        changesHtml += '</div>';
        return changesHtml;
    }

    function formatKey(key) {
        const keyMap = {
            'fname': 'First Name',
            'lname': 'Last Name',
            'email': 'Email',
            'contact': 'Contact',
            'faculty_id': 'Faculty ID',
            'student_id': 'Student ID',
            'organization_name': 'Organization',
            'organization_type': 'Organization Type',
            'hte_status': 'HTE Status',
            'can_add_hte': 'HTE Permission'
        };
        return keyMap[key] || key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
    }

    function formatValue(value) {
        if (typeof value === 'boolean') {
            return value ? 'Yes' : 'No';
        }
        if (value === null || value === '') {
            return 'empty';
        }
        return value;
    }

    function getActionBadge(action) {
        const badges = {
            'created': 'badge badge-success',
            'updated': 'badge badge-primary', 
            'deleted': 'badge badge-danger'
        };
        
        const badgeClass = badges[action] || 'badge badge-secondary';
        const actionText = action.replace('_', ' ').toUpperCase();
        return `<span class="${badgeClass}">${actionText}</span>`;
    }

    function getUserTypeDisplay(userType) {
        const types = {
            'admin': 'Administrator',
            'coordinator': 'Coordinator',
            'intern': 'Intern',
            'hte': 'HTE'
        };
        return types[userType] || userType.toUpperCase();
    }

    function updatePagination(response) {
        const paginationLinks = $('#paginationLinks');
        const paginationInfo = $('#paginationInfo');
        
        paginationLinks.empty();
        paginationInfo.empty();

        // Don't show pagination if no results
        if (response.total === 0) {
            paginationInfo.text('No entries found');
            return;
        }

        // Pagination info
        const start = (response.current_page - 1) * response.per_page + 1;
        const end = Math.min(response.current_page * response.per_page, response.total);
        paginationInfo.text(`Showing ${start} to ${end} of ${response.total} entries`);

        // Don't show pagination when only one page
        if (response.last_page <= 1) {
            return;
        }

        // Previous page
        const prevDisabled = response.current_page === 1 ? 'disabled' : '';
        paginationLinks.append(`
            <li class="page-item ${prevDisabled}">
                <a class="page-link" href="#" data-page="${response.current_page - 1}" ${prevDisabled ? 'tabindex="-1" aria-disabled="true"' : ''}>
                    <i class="fas fa-chevron-left"></i> Previous
                </a>
            </li>
        `);

        // Page numbers - show limited pages for better UX
        const totalPages = response.last_page;
        const currentPage = response.current_page;
        let startPage = Math.max(1, currentPage - 2);
        let endPage = Math.min(totalPages, currentPage + 2);

        // Adjust if we're at the beginning
        if (currentPage <= 3) {
            endPage = Math.min(5, totalPages);
        }
        
        // Adjust if we're at the end
        if (currentPage >= totalPages - 2) {
            startPage = Math.max(1, totalPages - 4);
        }

        // First page and ellipsis
        if (startPage > 1) {
            paginationLinks.append(`
                <li class="page-item">
                    <a class="page-link" href="#" data-page="1">1</a>
                </li>
            `);
            if (startPage > 2) {
                paginationLinks.append(`
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                `);
            }
        }

        // Page numbers
        for (let i = startPage; i <= endPage; i++) {
            const active = i === currentPage ? 'active' : '';
            paginationLinks.append(`
                <li class="page-item ${active}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>
            `);
        }

        // Last page and ellipsis
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                paginationLinks.append(`
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                `);
            }
            paginationLinks.append(`
                <li class="page-item">
                    <a class="page-link" href="#" data-page="${totalPages}">${totalPages}</a>
                </li>
            `);
        }

        // Next page
        const nextDisabled = response.current_page === response.last_page ? 'disabled' : '';
        paginationLinks.append(`
            <li class="page-item ${nextDisabled}">
                <a class="page-link" href="#" data-page="${response.current_page + 1}" ${nextDisabled ? 'tabindex="-1" aria-disabled="true"' : ''}>
                    Next <i class="fas fa-chevron-right"></i>
                </a>
            </li>
        `);

        // Add click handlers
        $(document).off('click', '.page-link[data-page]').on('click', '.page-link[data-page]', function(e) {
            e.preventDefault();
            const page = $(this).data('page');
            if (page && page !== currentPage) {
                currentPage = page;
                loadUserData();
                
                // Scroll to top of table
                $('html, body').animate({
                    scrollTop: $('.card').offset().top - 20
                }, 300);
            }
        });
    }
});
</script>
@endsection



