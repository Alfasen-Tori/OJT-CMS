{{-- resources/views/admin/sics/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Manage Consolidated SICs')

@section('content')

<section class="content-header">
  <div class="container-fluid px-3">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="page-header">CONSOLIDATED SICs</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item fw-medium">Admin</li>
          <li class="breadcrumb-item active text-muted">Consolidated SICs</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 p-3">
        <h3 class="card-title">Consolidated Student Internship Contracts</h3>
    </div>

      <div class="card-body table-responsive py-0 px-3 position-relative">
        <!-- Loading Overlay -->
        <div id="tableLoadingOverlay" 
          style="position: absolute; 
          width: 100%; 
          height: 100%; 
          background: rgba(255,255,255,0.85); 
          display: flex; 
          flex-direction: column;
          justify-content: center; 
          align-items: center; 
          z-index: 1000;
          gap: 1rem;">
          <i class="ph-bold ph-arrows-clockwise fa-spin fs-3 text-primary"></i>
          <span class="text-primary">Loading Consolidated SICs . . .</span>
        </div>
        
        <table id="sicsTable" class="table table-bordered mb-0">
          <thead class="table-light">
            <tr>
              <th>File Name</th>
              <th>Coordinator</th>
              <th>Department</th>
              <th>Date Uploaded</th>
              <th width="10%">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($consolidatedSics as $sic)
            <tr>
              <td class="align-middle">
                <div class="d-flex align-items-center">
                  <i class="ph ph-file-pdf text-danger me-2 fs-5"></i>
                  <span class="text-truncate" style="max-width: 250px;" title="{{ basename($sic->file_path) }}">
                    {{ basename($sic->file_path) }}
                  </span>
                </div>
              </td>
              <td class="align-middle">
                @if($sic->coordinator->user->pic)
                  <img src="{{ asset('storage/' . $sic->coordinator->user->pic) }}" 
                      alt="Profile Picture" 
                      class="rounded-circle me-2 table-pfp" 
                      width="30" height="30">
                @else
                  @php
                    $name = $sic->coordinator->user->fname . $sic->coordinator->user->lname;
                    $colors = [
                        '#007bff', '#28a745', '#dc3545', '#6f42c1', 
                        '#fd7e14', '#20c997', '#e83e8c', '#17a2b8',
                    ];
                    $colorIndex = crc32($name) % count($colors);
                    $randomColor = $colors[$colorIndex];
                  @endphp
                  <div class="rounded-circle me-2 d-inline-flex align-items-center justify-content-center text-white fw-bold" 
                      style="width: 30px; height: 30px; font-size: 11px; background-color: {{ $randomColor }};">
                    {{ strtoupper(substr($sic->coordinator->user->fname, 0, 1) . substr($sic->coordinator->user->lname, 0, 1)) }}
                  </div>
                @endif
                {{ $sic->coordinator->user->lname }}, {{ $sic->coordinator->user->fname }}
                <br>
                <small class="text-muted">{{ $sic->coordinator->faculty_id }}</small>
              </td>
              <td class="align-middle">
                <span class="fw-medium">{{ $sic->coordinator->department->dept_name ?? 'N/A' }}</span>
                <br>
                <small class="text-muted">{{ $sic->coordinator->department->college->short_name ?? '' }}</small>
              </td>
              <td class="align-middle">
                {{ \Carbon\Carbon::parse($sic->created_at)->format('M d, Y') }}
                <br>
                <small class="text-muted">{{ \Carbon\Carbon::parse($sic->created_at)->format('g:i A') }}</small>
              </td>
              <td class="text-center px-2 align-middle">
                <div class="dropdown">
                  <button class="btn btn-sm btn-outline-primary px-2 rounded-pill dropdown-toggle" type="button" id="actionDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="ph-fill ph-gear custom-icons-i"></i>
                  </button>
                  <div class="dropdown-menu dropdown-menu-right shadow-sm border py-0" aria-labelledby="actionDropdown">
                    <!-- View Option -->
                    <a class="dropdown-item d-flex align-items-center py-2" href="{{ route('admin.sics.view', $sic->id) }}" target="_blank">
                      <i class="ph ph-eye custom-icons-i mr-2"></i>View
                    </a>
                    
                    <div class="dropdown-divider my-1"></div>
                    
                    <!-- Download Option -->
                    <a class="dropdown-item d-flex align-items-center py-2" href="{{ route('admin.sics.download', $sic->id) }}">
                      <i class="ph ph-download custom-icons-i mr-2"></i>Download
                    </a>
                  </div>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>

@include('layouts.partials.scripts-main')

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#sicsTable').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "language": {
            "emptyTable": "No consolidated SICs found.",
            "search": "_INPUT_",
            "searchPlaceholder": "Search SICs...",
            "lengthMenu": "Show _MENU_ entries",
            "info": "Showing _START_ to _END_ of _TOTAL_ SICs",
            "paginate": {
                "previous": "«",
                "next": "»"
            }
        },
        "columnDefs": [
            { "orderable": false, "targets": [4] } // Disable sorting for Actions column
        ],
        "order": [[3, 'desc']], // Default sort by date uploaded (newest first)
        "initComplete": function() {
            // Hide loading overlay when table is ready
            $('#tableLoadingOverlay').fadeOut();
        }
    });

    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
@endsection