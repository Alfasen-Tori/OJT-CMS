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
              <div class="input-group input-group-sm" style="width: 300px;">
                <div class="input-group-prepend">
                  <div class="btn-group me-2">
                    <button type="button" class="btn btn-sm btn-success" id="printBtn">
                      <i class="fas fa-print"></i>
                      Print
                    </button>
                    <button type="button" class="btn btn-sm btn-success dropdown-toggle dropdown-icon" data-toggle="dropdown">
                      <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu" role="menu">
                      <a class="dropdown-item" href="#" id="printCurrentView">
                        <i class="fas fa-print mr-2"></i>Print Current View
                      </a>
                      <a class="dropdown-item" href="#" id="printAllData">
                        <i class="fas fa-file-pdf mr-2"></i>Print All Data
                      </a>
                    </div>
                  </div>
                </div>
                <input type="text" class="form-control" placeholder="Search activities..." id="searchInput">
                <div class="input-group-append">

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
              <table class="table table-bordered table-striped" id="usersTable">
                <thead>
                  <tr>
                    <th>Timestamp</th>
                    <th>Performed By</th>
                    <th>Action</th>
                    <th>Description</th>
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

<!-- Print Styles and Script -->
<style>
@media print {
  body * {
    visibility: hidden;
  }
  .print-section, .print-section * {
    visibility: visible;
  }
  .print-section {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
  }
  .no-print {
    display: none !important;
  }
  .table {
    border-collapse: collapse !important;
    width: 100% !important;
  }
  .table-bordered th,
  .table-bordered td {
    border: 1px solid #ddd !important;
    padding: 8px !important;
  }
  .badge {
    border: 1px solid #000 !important;
    color: #000 !important;
    background-color: transparent !important;
  }
  .change-item {
    margin: 2px 0;
  }
}
</style>

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

    // Print functionality
    $('#printCurrentView').click(function(e) {
        e.preventDefault();
        printCurrentView();
    });

    $('#printAllData').click(function(e) {
        e.preventDefault();
        printAllData();
    });

    $('#printBtn').click(function() {
        printCurrentView();
    });

    function printCurrentView() {
        // Create a print-friendly version of the current table
        const printContent = createPrintContent('Current View - User Management Audit Trail');
        
        // Open print window
        const printWindow = window.open('', '_blank');
        printWindow.document.write(printContent);
        printWindow.document.close();
        
        // Wait for content to load then print
        printWindow.onload = function() {
            printWindow.print();
        };
    }

    function printAllData() {
        // Show loading state
        $('#usersTableBody').html(`
            <tr>
                <td colspan="4" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <div class="mt-2">Preparing all data for printing...</div>
                </td>
            </tr>
        `);

        // Get all data without pagination
        $.ajax({
            url: '{{ route("admin.audit-trail.users.data") }}',
            type: 'GET',
            data: {
                action: $('#actionFilter').val(),
                user_type: $('#userTypeFilter').val(),
                date_from: $('#dateFromFilter').val(),
                search: $('#searchInput').val(),
                all_data: true
            },
            success: function(response) {
                const printContent = createPrintContent('Complete User Management Audit Trail', response.data);
                const printWindow = window.open('', '_blank');
                printWindow.document.write(printContent);
                printWindow.document.close();
                
                printWindow.onload = function() {
                    printWindow.print();
                };

                // Reload the normal view
                loadUserData();
            },
            error: function(xhr) {
                console.error('Error loading all data:', xhr);
                alert('Error loading data for printing. Please try again.');
                loadUserData();
            }
        });
    }

    function createPrintContent(title, activities = null) {
        const currentDate = new Date().toLocaleString();
        const filtersInfo = getCurrentFiltersInfo();
        
        // Use provided activities or current table data
        const tableRows = activities ? generateTableRows(activities) : $('#usersTableBody').html();

        return `
<!DOCTYPE html>
<html>
<head>
    <title>${title}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
            color: #000;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #000;
        }
        .print-info {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }
        .print-info p {
            margin: 2px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #f8f9fa !important;
            border: 1px solid #000 !important;
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }
        td {
            border: 1px solid #000 !important;
            padding: 6px;
            vertical-align: top;
        }
        .badge {
            border: 1px solid #000 !important;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .change-item {
            margin: 1px 0;
            font-size: 11px;
        }
        .text-danger s {
            color: #dc3545 !important;
        }
        .text-success {
            color: #28a745 !important;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #000;
            padding-top: 10px;
        }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>${title}</h1>
    </div>
    
    <div class="print-info">
        <p><strong>Generated on:</strong> ${currentDate}</p>
        <p><strong>Filters applied:</strong> ${filtersInfo}</p>
        <p><strong>Generated by:</strong> {{ auth()->user()->name ?? 'System Admin' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Timestamp</th>
                <th>Performed By</th>
                <th>Action</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            ${tableRows}
        </tbody>
    </table>

    <div class="footer">
        <p>User Management Audit Trail Report - Generated by Internship Management System</p>
        <p>Page generated on ${currentDate}</p>
    </div>
</body>
</html>`;
    }

    function getCurrentFiltersInfo() {
        const filters = [];
        
        if ($('#actionFilter').val()) {
            filters.push('Action: ' + $('#actionFilter option:selected').text());
        }
        if ($('#userTypeFilter').val()) {
            filters.push('Performer Type: ' + $('#userTypeFilter option:selected').text());
        }
        if ($('#dateFromFilter').val()) {
            filters.push('From: ' + $('#dateFromFilter').val());
        }
        if ($('#searchInput').val()) {
            filters.push('Search: ' + $('#searchInput').val());
        }

        return filters.length > 0 ? filters.join(', ') : 'All activities';
    }

    function generateTableRows(activities) {
        if (!activities || activities.length === 0) {
            return '<tr><td colspan="4" style="text-align: center; padding: 20px;">No user management activities found</td></tr>';
        }

        return activities.map(activity => {
            const actionBadge = getPrintActionBadge(activity.action);
            const timestamp = new Date(activity.created_at).toLocaleString();
            const userTypeDisplay = getUserTypeDisplay(activity.user_type);
            const changesHtml = renderPrintValueChanges(activity.old_values, activity.new_values);

            return `
                <tr>
                    <td>${timestamp}</td>
                    <td>
                        <strong>${escapeHtml(activity.user_display_name)}</strong><br>
                        <small>${userTypeDisplay}</small>
                    </td>
                    <td>${actionBadge}</td>
                    <td>
                        <div>${escapeHtml(activity.changes || 'User management activity')}</div>
                        ${changesHtml}
                    </td>
                </tr>
            `;
        }).join('');
    }

    function renderPrintValueChanges(oldValues, newValues) {
        if (!oldValues || !newValues) return '';
        
        let changesHtml = '<div style="margin-top: 5px;">';
        
        Object.keys(newValues).forEach(key => {
            const oldVal = oldValues[key] || 'empty';
            const newVal = newValues[key];
            
            if (oldVal !== newVal) {
                changesHtml += `
                    <div class="change-item">
                        <span>${formatKey(key)}:</span> 
                        <span class="text-danger"><s>${formatValue(oldVal)}</s></span> → 
                        <span class="text-success">${formatValue(newVal)}</span>
                    </div>
                `;
            }
        });
        
        changesHtml += '</div>';
        return changesHtml;
    }

    function getPrintActionBadge(action) {
        const badges = {
            'created': 'badge badge-success',
            'updated': 'badge badge-primary', 
            'deleted': 'badge badge-danger',
            'role_assigned': 'badge badge-info'
        };
        
        const badgeClass = badges[action] || 'badge badge-secondary';
        const actionText = action.replace('_', ' ').toUpperCase();
        return `<span class="${badgeClass}">${actionText}</span>`;
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function loadUserData() {
        const filters = {
            action: $('#actionFilter').val(),
            user_type: $('#userTypeFilter').val(),
            date_from: $('#dateFromFilter').val(),
            search: $('#searchInput').val()
        };

        // Show loading state
        $('#usersTableBody').html(`
            <tr>
                <td colspan="4" class="text-center py-4">
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
                        <td colspan="4" class="text-center text-danger py-4">
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
                    <td colspan="4" class="text-center text-muted py-4">
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