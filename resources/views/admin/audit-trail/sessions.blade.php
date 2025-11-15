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
              <div class="input-group input-group-sm" style="width: 300px;">
                <div class="input-group-prepend">
                  <div class="btn-group me-2">
                    <button type="button" class="btn btn-sm btn-success" id="printBtn">
                      <i class="fas fa-print"></i>
                      Print
                    </button>
                    <button type="button" class="btn btn-sm  btn-success dropdown-toggle dropdown-icon" data-toggle="dropdown">
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
                <input type="text" class="form-control" placeholder="Search..." id="searchInput">
                <div class="input-group-append">

                </div>
              </div>
            </div>
          </div>
          
          <div class="card-body">
            <!-- Filters -->
            <div class="row mb-3">
              <div class="col-md-3">
                <select class="form-control" id="userTypeFilter">
                  <option value="">All User Types</option>
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
              <table class="table table-bordered table-striped" id="sessionsTable">
                <thead>
                  <tr>
                    <th>User</th>
                    <th>User Type</th>
                    <th>Login Time</th>
                    <th>Logout Time</th>
                    <th>Duration</th>
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
}
</style>

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
        const printContent = createPrintContent('Current View Session Audit Trail');
        
        // Open print window
        const printWindow = window.open('', '_blank');
        printWindow.document.write(printContent);
        printWindow.document.close();
        
        // Wait for content to load then print
        printWindow.onload = function() {
            printWindow.print();
            // printWindow.close(); // Optional: close after printing
        };
    }

    function printAllData() {
        // Show loading state
        $('#sessionsTableBody').html(`
            <tr>
                <td colspan="6" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <div class="mt-2">Preparing all data for printing...</div>
                </td>
            </tr>
        `);

        // Get all data without pagination
        $.ajax({
            url: '{{ route("admin.audit-trail.sessions.data") }}',
            type: 'GET',
            data: {
                ...currentFilters,
                all_data: true // Add this parameter to your backend
            },
            success: function(response) {
                const printContent = createPrintContent('Complete Session Audit Trail', response.data);
                const printWindow = window.open('', '_blank');
                printWindow.document.write(printContent);
                printWindow.document.close();
                
                printWindow.onload = function() {
                    printWindow.print();
                };

                // Reload the normal view
                loadSessionData();
            },
            error: function(xhr) {
                console.error('Error loading all data:', xhr);
                alert('Error loading data for printing. Please try again.');
                loadSessionData();
            }
        });
    }

    function createPrintContent(title, sessions = null) {
        const currentDate = new Date().toLocaleString();
        const filtersInfo = getCurrentFiltersInfo();
        
        // Use provided sessions or current table data
        const tableRows = sessions ? generateTableRows(sessions) : $('#sessionsTableBody').html();

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
        }
        .badge {
            border: 1px solid #000 !important;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
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
                <th>User</th>
                <th>User Type</th>
                <th>Login Time</th>
                <th>Logout Time</th>
                <th>Duration</th>
                <th>User Agent</th>
            </tr>
        </thead>
        <tbody>
            ${tableRows}
        </tbody>
    </table>

    <div class="footer">
        <p>Session Audit Trail Report - Generated by Internship Management System</p>
        <p>Page generated on ${currentDate}</p>
    </div>
</body>
</html>`;
    }

    function getCurrentFiltersInfo() {
        const filters = [];
        
        if ($('#userTypeFilter').val()) {
            filters.push('User Type: ' + $('#userTypeFilter').val());
        }
        if ($('#dateFromFilter').val()) {
            filters.push('From: ' + $('#dateFromFilter').val());
        }
        if ($('#dateToFilter').val()) {
            filters.push('To: ' + $('#dateToFilter').val());
        }
        if ($('#searchInput').val()) {
            filters.push('Search: ' + $('#searchInput').val());
        }

        return filters.length > 0 ? filters.join(', ') : 'All sessions';
    }

    function generateTableRows(sessions) {
        if (!sessions || sessions.length === 0) {
            return '<tr><td colspan="6" style="text-align: center; padding: 20px;">No session data found</td></tr>';
        }

        return sessions.map(session => {
            const loginTime = session.login_at ? new Date(session.login_at).toLocaleString() : 'N/A';
            const logoutTime = session.logout_at ? new Date(session.logout_at).toLocaleString() : 'N/A';
            
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
            }
            
            const userDisplayName = session.user ? 
                `${session.user.fname} ${session.user.lname}` : 'Unknown User';

            const userTypeBadge = `<span class="badge">${session.user_type.toUpperCase()}</span>`;

            return `
                <tr>
                    <td>${escapeHtml(userDisplayName)}</td>
                    <td>${userTypeBadge}</td>
                    <td>${loginTime}</td>
                    <td>${logoutTime}</td>
                    <td><strong>${duration}</strong></td>
                    <td>${escapeHtml(session.user_agent || 'N/A')}</td>
                </tr>
            `;
        }).join('');
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function loadSessionData() {
        const filters = {
            user_type: $('#userTypeFilter').val(),
            date_from: $('#dateFromFilter').val(),
            date_to: $('#dateToFilter').val(),
            search: $('#searchInput').val()
        };

        currentFilters = filters;

        // Show loading state
        $('#sessionsTableBody').html(`
            <tr>
                <td colspan="6" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <div class="mt-2">Loading session data...</div>
                </td>
            </tr>
        `);

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
                $('#sessionsTableBody').html(`
                    <tr>
                        <td colspan="6" class="text-center text-danger py-4">
                            <i class="fas fa-exclamation-triangle fa-2x mb-2"></i><br>
                            Error loading session data. Please try again.
                        </td>
                    </tr>
                `);
            }
        });
    }

    function updateTable(sessions) {
        const tbody = $('#sessionsTableBody');
        tbody.empty();

        if (sessions.length === 0) {
            tbody.append(`
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
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
                    <td class="small">${loginTime}</td>
                    <td class="small">${logoutTime}</td>
                    <td><span class="fw-medium text-primary">${duration}</span></td>
                    <td class="small text-muted" title="${session.user_agent || 'N/A'}">
                        ${truncateUserAgent(session.user_agent)}
                    </td>
                </tr>
            `);
        });
    }

    function truncateUserAgent(userAgent) {
        if (!userAgent) return 'N/A';
        return userAgent.length > 50 ? userAgent.substring(0, 50) + '...' : userAgent;
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

        // Don't show pagination if only one page
        if (response.last_page <= 1) {
            return;
        }

        // Previous page
        const prevDisabled = response.current_page === 1 ? 'disabled' : '';
        paginationLinks.append(`
            <li class="page-item ${prevDisabled}">
                <a class="page-link" href="#" data-page="${response.current_page - 1}" ${prevDisabled ? 'tabindex="-1" aria-disabled="true"' : ''}>
                    <i class="fas fa-chevron-left"></i>
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
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
        `);

        // Add click handlers
        $('.page-link[data-page]').click(function(e) {
            e.preventDefault();
            const page = $(this).data('page');
            if (page && page !== currentPage) {
                currentPage = page;
                loadSessionData();
                
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