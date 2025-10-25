{{-- resources/views/hte/interns.blade.php --}}
@extends('layouts.hte')

@section('title', 'HTE | Interns')

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="page-header">TRAINEES</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item fw-medium">HTE</li>
          <li class="breadcrumb-item active text-muted">Interns</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
    @if(count($deployedInterns) > 0)
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Deployed Students</h3>
      </div>
      
      <div class="card-body table-responsive py-0 px-3" style="position: relative;">
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
          <span class="text-primary">Loading Interns . . .</span>
        </div>
        
        <table id="internsTableHTE" class="table table-bordered mb-0">
          <thead class="table-light">
            <tr>
              <th width="12%">Student ID</th>
              <th>Name</th>
              <th>Department</th>
              <th width="15%">Coordinator</th>
              <th width="10%">Status</th>
              <th width="3%">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($deployedInterns as $deployment)
            @php
                $intern = $deployment->intern;
                $coordinator = $deployment->coordinator;
                
                // Determine if intern is completed (hours requirement met)
                $totalHours = \App\Models\Attendance::where('intern_hte_id', $deployment->id)
                    ->sum('hours_rendered');
                $isCompleted = $totalHours >= $deployment->no_of_hours;
                $statusClass = $isCompleted ? 'bg-success-subtle text-success' : 'bg-primary-subtle text-primary';
                $statusText = $isCompleted ? 'Completed' : 'Deployed';
            @endphp
            <tr>
              <td class="align-middle">{{ $intern->student_id }}</td>
              <td class="align-middle">
                <img src="{{ asset('storage/' . $intern->user->pic) }}" 
                  alt="Profile Picture" 
                  class="rounded-circle me-2 table-pfp" 
                  width="30" height="30">
                {{ $intern->user->lname }}, {{ $intern->user->fname }} 
              </td>         
              <td class="align-middle">{{ $intern->department->dept_name ?? 'N/A' }}</td>
              <td class="align-middle">
                <div class="d-flex align-items-center">
                  <img src="{{ asset('storage/' . $coordinator->user->pic) }}" 
                    alt="Coordinator Picture" 
                    class="rounded-circle me-2 table-pfp" 
                    width="25" height="25">
                  <div>
                    <strong>{{ $coordinator->user->fname }} {{ $coordinator->user->lname }}</strong>
                    <br>
                    <small class="text-muted">{{ $coordinator->faculty_id }}</small>
                  </div>
                </div>
              </td>
              <td class="align-middle">
                <span class="small badge py-2 px-3 rounded-pill {{ $statusClass }}" style="font-size: 14px">
                  {{ $statusText }}
                </span>
                @if($isCompleted)
                  <br>
                  <small class="text-muted">{{ $totalHours }}/{{ $deployment->no_of_hours }} hrs</small>
                @endif
              </td>
              <td class="text-center px-2 align-middle">
                <div class="dropdown">
                  <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="actionDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="ph-fill ph-gear custom-icons-i"></i>
                  </button>
                  <div class="dropdown-menu dropdown-menu-right py-0" aria-labelledby="actionDropdown">
                    <!-- View Option -->
                    <a class="dropdown-item btn btn-outline-light text-dark" href="">
                      <i class="ph ph-eye custom-icons-i mr-2"></i>View
                    </a>
                    
                    <!-- Evaluate Option - Only show if completed -->
                    @if($isCompleted)
                      <a class="dropdown-item border-top border-bottom border-lightgray btn btn-outline-light text-dark" href="#">
                        <i class="ph ph-clipboard-text custom-icons-i mr-2"></i>Evaluate
                      </a>
                    @endif
                  </div>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
        <div class="d-flex flex-column align-items-center justify-content-center text-muted" style="height: 70vh;">
          <i class="ph ph-users fs-1 mb-3" style="font-size: 4rem !important;"></i>
          <h4 class="text-muted mb-2">Wow, such empty.</h4>
          <p class="text-muted mb-0">Check back later for deployed students.</p>
        </div>
      @endif
    </div>
  </div>
</section>

@endsection

@section('scripts')
@if(count($deployedInterns) > 0)
<script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#internsTableHTE').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "emptyTable": "No students are currently deployed to your organization.",
                "search": "_INPUT_",
                "searchPlaceholder": "Search students...",
                "lengthMenu": "Show _MENU_ entries",
                "info": "Showing _START_ to _END_ of _TOTAL_ students",
                "paginate": {
                    "previous": "«",
                    "next": "»"
                }
            },
            "columnDefs": [
                { "orderable": false, "targets": [5] } // Disable sorting for Actions column
            ],
            "initComplete": function(settings, json) {
                // Hide loading overlay when table is ready
                $('#tableLoadingOverlay').fadeOut(300);
            }
        });
        
        // Remove the manual search input if it exists
        $('.card-header input[type="search"]').parent().remove();
        
        // Fallback: hide loading overlay after 2 seconds
        setTimeout(function() {
            $('#tableLoadingOverlay').fadeOut(300);
        }, 2000);
    });
</script>
@endif
@endsection