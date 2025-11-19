<!-- Google Fonts -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{asset('assets/plugins/fontawesome-free/css/all.min.css')}}">
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Select2 Bootstrap4 Theme CSS -->
<link href="https://cdn.jsdelivr.net/gh/apalfrey/select2-bootstrap-5-theme/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<!-- Bootstrap -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
<!-- DataTable JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<!-- AdminLTE -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
<!-- Toastr -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">


<!-- Custom -->
<link rel="stylesheet" href="{{ asset('assets/fonts.css') }}">
<link rel="stylesheet" href="{{ asset('assets/colors.css') }}">


<style>
    .status-badge {
        font-size: 0.7rem !important;
    }
    .nav-link-i {
        font-size: 22px;
        position: relative;
        top: 2.5px;
    }

    .card-body {
        position: relative; /* Needed for absolute positioning of overlay */
    }
    
    #tableLoadingOverlay {
        transition: opacity 0.3s ease;
    }

    .custom-icons-i {
        font-size: 1.2rem;
        position: relative;
        top: 2px;
    }

    .spin {
        display: inline-block;
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .details-icons-i {
        font-size: 1.4rem;
    }

    .info-box {
        border-left: 4px solid #007bff;
    }

    .table-actions {
        white-space: nowrap;
    }

    .badge {
        font-size: 0.85em;
        padding: 0.35em 0.65em;
    }

    .table-pfp {
        position: relative;
        top: -2px;
    }


    .current-page {
        background-color: #8f1010 !important;
        color: #fff !important;
    }

    .main-sidebar, .brand-link {
        background-color: #a91414 !important;
    }

    .nav-link-icon {
        fill: currentColor;
        height: 22px;
        position: relative;
        bottom: 2px;
        left: 2px;
        margin-right:6px;
    }

  .infobox-icon {
      position: absolute;
      top: 10px;
      right: 10px;
      font-size: 3rem;
      opacity: 0.2;
    }

    .box-icon {
        fill: #000;
        height: 100px;
    }

    .table-cta-icon {
        fill: currentColor;
        height: 1.2rem;
        position: relative;
        top: 2px;
    }

    .table-action-icon {
        fill: currentColor;
        height: 18px;
        position: relative;
        top: -1px;
    }

    /* SKILL KNOBS */
    .knob-container {
        position: relative;
        width: 60px;
        height: 60px;
    }

    .knob-display {
        width: 100%;
        height: 100%;
        position: relative;
    }

    .knob-bg {
        position: relative;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .knob-center {
        position: absolute;
        width: 50px;
        height: 50px;
        background: white;       /* Inner circle for donut hole */
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 14px;
        color: #495057;
    }
    
    /* DATATABLES */
    #internsTable_wrapper .row:first-child {
        padding-top: 1rem;
    }

    #internsTable_wrapper .row:last-child {
        padding-bottom: 1rem;
        padding-top: 0.5rem;
    }

    #htesTable_wrapper .row:first-child {
        padding-top: 1rem;
    }

    #htesTable_wrapper .row:last-child {
        padding-bottom: 1rem;
        padding-top: 0.5rem;
    }

    #deploymentsTable_wrapper .row:first-child {
        padding-top: 1rem;
    }

    #deploymentsTable_wrapper .row:last-child {
        padding-bottom: 1rem;
        padding-top: 0.5rem;
    }

    #internAttendanceTable_wrapper .row:first-child {
        padding-top: 1rem;
    }

    #internAttendanceTable_wrapper .row:last-child {
        padding-bottom: 1rem;
        padding-top: 0.5rem;
    }

    #internsTableHTE_wrapper .row:first-child {
        padding-top: 1rem;
    }

    #internsTableHTE_wrapper .row:last-child {
        padding-bottom: 1rem;
        padding-top: 0.5rem;
    }

    #allStudentsTable_wrapper .row:first-child {
        padding-top: 1rem;
    }

    #allStudentsTable_wrapper .row:last-child {
        padding: 1rem;
    }

    #sicsTable_wrapper .row:first-child {
        padding-top: 1rem;
    }
    #sicsTable_wrapper .row:last-child {
        padding: 1rem;
    }

/* Light mode (default) */
#internsTable thead th,
#htesTable thead th,
#deploymentsTable thead th,
#internAttendanceTable thead th,
#internsTableHTE thead th,
#sicsTable thead th {
    border-right: 1px solid #dee2e6 !important;
}

/* Dark mode - more specific */
html.dark-mode #internsTable thead th,
html.dark-mode #htesTable thead th,
html.dark-mode #deploymentsTable thead th,
html.dark-mode #internAttendanceTable thead th,
html.dark-mode #internsTableHTE thead th,
html.dark-mode #sicsTable thead th {
    border-right: 1px solid #454d55 !important;
}

    #allStudentsTable thead th {
        border: 1px solid #dee2e6 !important;
    }

    #coordinatorsTable_wrapper .row:first-child {
        padding-top: 1rem;
    }

    #coordinatorsTable_wrapper .row:last-child {
        padding-bottom: 1rem;
        padding-top: 0.5rem;
    }

    /* Dark Mode Styles */
.dark-mode {
  --bs-body-bg: #1a1a1a;
  --bs-body-color: #ffffff;
}

/* Body and wrapper */
.dark-mode body {
  background-color: #1a1a1a;
  color: #ffffff;
}

.dark-mode .wrapper {
  background-color: #1a1a1a;
}

/* Navbar */
.dark-mode .navbar-white {
  background-color: #343a40 !important;
  border-color: #454d55;
}

.dark-mode .navbar-light .navbar-nav .nav-link {
  color: rgba(255, 255, 255, 0.8);
}

.dark-mode .navbar-light .navbar-nav .nav-link:hover {
  color: #ffffff;
}

/* Sidebar */
.dark-mode .main-sidebar {
  background-color: #343a40;
}

.dark-mode .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active {
  background-color: #007bff;
  color: #ffffff;
}

.dark-mode .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link {
  color: rgba(255, 255, 255, 0.8);
}

.dark-mode .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link:hover {
  background-color: rgba(255, 255, 255, 0.1);
  color: #ffffff;
}

/* Content */
.dark-mode .content-wrapper {
  background-color: #1a1a1a;
  color: #ffffff;
}

.dark-mode .card {
  background-color: #343a40;
  border-color: #454d55;
}

.dark-mode .card-header {
  background-color: #2d3338;
  border-bottom-color: #454d55;
  color: #ffffff;
}

.dark-mode .card-body {
  color: #ffffff;
}

/* DataTables Dark Mode */
.dark-mode .dataTables_wrapper {
  color: #ffffff;
}

.dark-mode .dataTables_wrapper .dataTables_length,
.dark-mode .dataTables_wrapper .dataTables_filter,
.dark-mode .dataTables_wrapper .dataTables_info,
.dark-mode .dataTables_wrapper .dataTables_processing,
.dark-mode .dataTables_wrapper .dataTables_paginate {
  color: #ffffff !important;
}

.dark-mode .dataTables_wrapper .dataTables_filter input {
  background-color: #2d3338;
  border-color: #454d55;
  color: #ffffff;
}

.dark-mode .dataTables_wrapper .dataTables_filter input:focus {
  background-color: #2d3338;
  border-color: #007bff;
  color: #ffffff;
}

.dark-mode .dataTables_wrapper .dataTables_length select {
  background-color: #2d3338;
  border-color: #454d55;
  color: #ffffff;
}

.dark-mode .dataTables_wrapper .dataTables_paginate .paginate_button {
  background-color: #2d3338 !important;
  border-color: #454d55 !important;
  color: #ffffff !important;
}

.dark-mode .dataTables_wrapper .dataTables_paginate .paginate_button.current,
.dark-mode .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
  background-color: #007bff !important;
  border-color: #007bff !important;
  color: #ffffff !important;
}

.dark-mode .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
  background-color: #495057 !important;
  border-color: #495057 !important;
  color: #ffffff !important;
}

.dark-mode .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
.dark-mode .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
  background-color: #2d3338 !important;
  border-color: #454d55 !important;
  color: #6c757d !important;
}

/* Tables */
.dark-mode .table {
  color: #ffffff;
  border-color: #454d55;
}

.dark-mode .table th,
.dark-mode .table td {
  border-color: #454d55;
}

.dark-mode .table thead th {
  border-bottom-color: #454d55;
  background-color: #2d3338;
  color: #ffffff;
}

.dark-mode .table tbody tr {
  background-color: #343a40;
}

.dark-mode .table tbody tr:hover {
  background-color: #3a4149;
}

/* DataTables specific table styles */
.dark-mode table.dataTable {
  border-color: #454d55;
}

.dark-mode table.dataTable thead th,
.dark-mode table.dataTable thead td {
  border-bottom-color: #454d55;
}

.dark-mode table.dataTable tbody th,
.dark-mode table.dataTable tbody td {
  border-top-color: #454d55;
}

.dark-mode table.dataTable.row-border tbody th,
.dark-mode table.dataTable.row-border tbody td,
.dark-mode table.dataTable.display tbody th,
.dark-mode table.dataTable.display tbody td {
  border-top-color: #454d55;
}

.dark-mode table.dataTable tbody tr:hover {
  background-color: #3a4149 !important;
}

/* Forms */
.dark-mode .form-control {
  background-color: #2d3338;
  border-color: #454d55;
  color: #ffffff;
}

.dark-mode .form-control:focus {
  background-color: #2d3338;
  border-color: #007bff;
  color: #ffffff;
}

.dark-mode .form-control::placeholder {
  color: #6c757d;
}

.dark-mode .input-group-text {
  background-color: #454d55;
  border-color: #454d55;
  color: #ffffff;
}

/* Dropdowns */
.dark-mode .dropdown-menu {
  background-color: #343a40;
  border-color: #454d55;
}

.dark-mode .dropdown-item {
  color: rgba(255, 255, 255, 0.8);
}

.dark-mode .dropdown-item:hover {
  background-color: #2d3338;
  color: #ffffff;
}

.dark-mode .dropdown-divider {
  border-color: #454d55;
}

/* Buttons */
.dark-mode .btn-secondary {
  background-color: #6c757d;
  border-color: #6c757d;
}

.dark-mode .btn-outline-secondary {
  color: #6c757d;
  border-color: #6c757d;
}

.dark-mode .btn-outline-secondary:hover {
  background-color: #6c757d;
  border-color: #6c757d;
  color: #ffffff;
}

/* Modals */
.dark-mode .modal-content {
  background-color: #343a40;
  border-color: #454d55;
  color: #ffffff;
}

.dark-mode .modal-header {
  border-bottom-color: #454d55;
}

.dark-mode .modal-footer {
  border-top-color: #454d55;
}

/* Text colors */
.dark-mode .text-dark {
  color: #ffffff !important;
}

.dark-mode .text-muted {
  color: #b6b6b6ff !important;
}

/* Badges */
.dark-mode .badge {
  color: #ffffff;
}

/* Custom scrollbar for webkit browsers */
.dark-mode ::-webkit-scrollbar {
  width: 8px;
}

.dark-mode ::-webkit-scrollbar-track {
  background: #2d3338;
}

.dark-mode ::-webkit-scrollbar-thumb {
  background: #454d55;
  border-radius: 4px;
}

.dark-mode ::-webkit-scrollbar-thumb:hover {
  background: #5a6268;
}

/* Light Mode Scrollbar */
::-webkit-scrollbar {
    width: 12px;
    height: 12px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 6px;
}

::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 6px;
    border: 2px solid #f1f1f1;
}

::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

::-webkit-scrollbar-corner {
    background: #f1f1f1;
}

/* Firefox Light Mode Scrollbar */
html {
    scrollbar-width: thin;
    scrollbar-color: #c1c1c1 #f1f1f1;
}

/* Dark Mode Scrollbar */
html.dark-mode ::-webkit-scrollbar {
    width: 12px;
    height: 12px;
}

html.dark-mode ::-webkit-scrollbar-track {
    background: #2d3338;
    border-radius: 6px;
}

html.dark-mode ::-webkit-scrollbar-thumb {
    background: #495057;
    border-radius: 6px;
    border: 2px solid #2d3338;
}

html.dark-mode ::-webkit-scrollbar-thumb:hover {
    background: #5a6268;
}

html.dark-mode ::-webkit-scrollbar-corner {
    background: #2d3338;
}

/* Firefox Dark Mode Scrollbar */
html.dark-mode {
    scrollbar-width: thin;
    scrollbar-color: #495057 #2d3338;
}

/* Specific scrollbar styling for sidebar */
.sidebar {
    scrollbar-width: thin;
    scrollbar-color: #c1c1c1 #f1f1f1;
}

html.dark-mode .sidebar {
    scrollbar-color: #495057 #2d3338;
}

/* Modal scrollbar styling */
.modal-body {
    scrollbar-width: thin;
    scrollbar-color: #c1c1c1 #f1f1f1;
}

html.dark-mode .modal-body {
    scrollbar-color: #495057 #2d3338;
}

/* DataTables scrollbar styling */
.dataTables_scrollBody {
    scrollbar-width: thin;
    scrollbar-color: #c1c1c1 #f1f1f1;
}

html.dark-mode .dataTables_scrollBody {
    scrollbar-color: #495057 #2d3338;
}

/* ===== FIX TD TEXT COLOR IN DARK MODE ===== */
/* Target only table cells that don't have text color utilities */
.dark-mode table.dataTable tbody td:not([class*="text-"]) {
  color: #ffffff;
}

.dark-mode .table td:not([class*="text-"]) {
  color: #ffffff;
}

/* Fix for regular tables without text utilities */
.dark-mode table tbody td:not([class*="text-"]),
.dark-mode table tbody th:not([class*="text-"]) {
  color: #ffffff;
}

/* Fix for DataTables cells without text utilities */
.dark-mode table.dataTable tbody tr td:not([class*="text-"]) {
  color: #ffffff;
}

/* Fix for striped rows in DataTables without text utilities */
.dark-mode table.dataTable tbody tr.odd td:not([class*="text-"]) {
  color: #ffffff;
}

.dark-mode table.dataTable tbody tr.even td:not([class*="text-"]) {
  color: #ffffff;
}

/* Fix hover states without overriding text utilities */
.dark-mode table.dataTable tbody tr.odd:hover td:not([class*="text-"]),
.dark-mode table.dataTable tbody tr.even:hover td:not([class*="text-"]) {
  color: #ffffff;
}

/* Fix for regular Bootstrap table cells without text utilities */
.dark-mode .table tbody td:not([class*="text-"]) {
  color: #ffffff;
}

/* Fix for any nested elements in table cells that don't have text utilities */
.dark-mode td:not([class*="text-"]) *:not([class*="text-"]),
.dark-mode th:not([class*="text-"]) *:not([class*="text-"]) {
  color: #ffffff;
}

/* More specific selectors to increase specificity without !important */
.dark-mode .table tbody tr td:not([class*="text-"]) {
  color: #ffffff;
}

.dark-mode .dataTables_wrapper .table tbody td:not([class*="text-"]) {
  color: #ffffff;
}

/* Fix for links inside tables without text utilities */
.dark-mode table a:not([class*="text-"]),
.dark-mode table td a:not([class*="text-"]),
.dark-mode table th a:not([class*="text-"]) {
  color: #6ea8fe;
}

.dark-mode table a:not([class*="text-"]):hover {
  color: #8bb9fe;
}

/* Dropdown Styles for Light Mode */
.dropdown-menu {
    border: 1px solid #e9ecef !important;
    box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1) !important;
    border-radius: 0.5rem;
}

.dropdown-item {
    padding: 0.5rem 1rem;
    transition: all 0.2s ease;
    border-radius: 0.25rem;
    margin: 0.1rem 0.25rem;
    width: auto;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
}

.dropdown-item.text-danger:hover {
    background-color: #f8d7da;
    color: #721c24 !important;
}

.dropdown-divider {
    border-top: 1px solid #e9ecef;
    margin: 0.25rem 0;
}

/* Dropdown Styles for Dark Mode */
html.dark-mode .dropdown-menu {
    background-color: #343a40;
    border-color: #495057 !important;
    box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.3) !important;
}

html.dark-mode .dropdown-item {
    color: rgba(255, 255, 255, 0.8);
    background-color: transparent;
}

html.dark-mode .dropdown-item:hover {
    background-color: #2d3338;
    color: #ffffff;
}

html.dark-mode .dropdown-item.text-danger {
    color: #f8a5a5 !important;
}

html.dark-mode .dropdown-item.text-danger:hover {
    background-color: #842029;
    color: #ffffff !important;
}

html.dark-mode .dropdown-divider {
    border-top-color: #495057;
}

/* Ensure dropdown toggle button works in dark mode */
html.dark-mode .btn-outline-primary {
    color: #6ea8fe;
    border-color: #6ea8fe;
}

html.dark-mode .btn-outline-primary:hover {
    background-color: #6ea8fe;A
    border-color: #6ea8fe;
    color: #ffffff;
}

html.dark-mode .dropdown-toggle::after {
    border-top-color: rgba(255, 255, 255, 0.8);
}

html.dark-mode .knob-center {
    background: #343a40;
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.3);
}

html.dark-mode .knob-center h3 {
color: #f8f9fa;
}

html.dark-mode .offcanvas {
    background: #343a40;
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.3);
}

/* Force dark mode styles for all inputs except file */
html.dark-mode .form-control:not([type="file"]) {
    background-color: #343a40 !important;
    color: #e9ecef !important;
    border-color: #6c757d !important;
}

/* File inputs in dark mode */
html.dark-mode .form-control[type="file"] {
    background-color: #343a40 !important;
    border-color: #6c757d !important;
    color: #e9ecef !important;
}

html.dark-mode .form-control[type="file"]::file-selector-button {
    background-color: #495057 !important;
    border-color: #6c757d !important;
    color: #e9ecef !important;
}

/* Autofill fix for dark mode */
html.dark-mode input:not([type="file"]):-webkit-autofill {
    -webkit-box-shadow: 0 0 0 1000px #343a40 inset !important;
    -webkit-text-fill-color: #e9ecef !important;
}






</style>
