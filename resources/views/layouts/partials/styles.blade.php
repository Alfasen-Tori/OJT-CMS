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

    #internsTable, #htesTable, #deploymentsTable, #internAttendanceTable, #internsTableHTE thead th {
        border-right: 1px solid #dee2e6 !important;
    }

    #allStudentsTable thead th {
        border: 1px solid #dee2e6 !important;
    }


</style>
