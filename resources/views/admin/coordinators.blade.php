{{-- resources/views/admin/coordinators/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Manage Coordinators')

@section('content')

<section class="content-header">
  <div class="container-fluid px-3">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="page-header">MANAGE COORDINATORS</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item fw-medium">Admin</li>
          <li class="breadcrumb-item active text-muted">Coordinators</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 px-2">
        <div class="d-flex flex-grow-1 justify-content-end p-0">
          <a href="{{ route('admin.new_c') }}" class="btn btn-primary d-flex">
            <span class="fw-medium">Register new</span>
          </a>
        </div>
      </div>

      <div class="card-body table-responsive py-0 px-3">
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
          <span class="text-primary">Loading Coordinators . . .</span>
        </div>
        
        <table id="coordinatorsTable" class="table table-bordered mb-0">
          <thead class="table-light">
            <tr>
              <th>Faculty ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Contact</th>
              <th>Department</th>
              <th width="12%">Claim Status</th>
              <th width="12%">HTE Privilege</th>
              <th width="3%">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($coordinators as $coordinator)
            <tr>
              <td class="align-middle">{{ $coordinator->faculty_id }}</td>
              <td class="align-middle">
                  @if($coordinator->user->pic)
                      <img src="{{ asset('storage/' . $coordinator->user->pic) }}" 
                          alt="Profile Picture" 
                          class="rounded-circle me-2 table-pfp" 
                          width="30" height="30">
                  @else
                      @php
                          // Generate a consistent random color based on user's name
                          $name = $coordinator->user->fname . $coordinator->user->lname;
                          $colors = [
                              '#007bff', // Blue
                              '#28a745', // Green
                              '#dc3545', // Red
                              '#6f42c1', // Purple
                              '#fd7e14', // Orange
                              '#20c997', // Teal
                              '#e83e8c', // Pink
                              '#17a2b8', // Cyan
                          ];
                          
                          // Generate a consistent index based on the user's name
                          $colorIndex = crc32($name) % count($colors);
                          $randomColor = $colors[$colorIndex];
                      @endphp
                      
                      <div class="rounded-circle me-2 d-inline-flex align-items-center justify-content-center text-white fw-bold" 
                          style="width: 30px; height: 30px; font-size: 11px; background-color: {{ $randomColor }};">
                          {{ strtoupper(substr($coordinator->user->fname, 0, 1) . substr($coordinator->user->lname, 0, 1)) }}
                      </div>
                  @endif
                  {{ $coordinator->user->lname }}, {{ $coordinator->user->fname }}
              </td>
              <td class="align-middle">{{ $coordinator->user->email }}</td>
              <td class="align-middle">{{ $coordinator->user->contact }}</td>
              <td class="align-middle small">{{ $coordinator->department->dept_name ?? 'N/A' }}</td>
              <td class="align-middle">
                @php
                  $statusClass = [
                    'pending documents' => 'bg-warning-subtle text-warning',
                    'for validation' => 'bg-info-subtle text-info',
                    'eligible for claim' => 'bg-success-subtle text-success',
                    'claimed' => 'bg-secondary-subtle text-secondary'
                  ][$coordinator->status] ?? 'bg-light text-dark';
                @endphp
                <span class="small badge py-2 px-3 rounded-pill {{ $statusClass }}" style="font-size: 14px">
                  {{ ucfirst($coordinator->status) }}
                </span>
              </td>
              <td class="align-middle">
                <span class="badge {{ $coordinator->can_add_hte ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} px-3 py-2 rounded-pill">
                  {{ $coordinator->can_add_hte ? 'Allowed' : 'Not Allowed' }}
                </span>
              </td>
              <td class="text-center px-2 align-middle">
                <div class="dropdown">
                  <button class="btn btn-sm btn-outline-dark px-2 rounded-pill dropdown-toggle" type="button" id="actionDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="ph-fill ph-gear custom-icons-i"></i>
                  </button>
                  <div class="dropdown-menu dropdown-menu-right py-0" aria-labelledby="actionDropdown">
                    <!-- View Option -->
                    <a class="dropdown-item btn btn-outline-light text-dark" href="{{ route('admin.coordinators.documents', $coordinator->id) }}">
                      <i class="ph ph-eye custom-icons-i mr-2"></i>View
                    </a>
                    
                    <!-- Update Option -->
                    <a class="dropdown-item border-top border-bottom border-lightgray btn btn-outline-light text-dark" href="{{ route('admin.coordinators.edit', $coordinator->id) }}">
                      <i class="ph ph-wrench custom-icons-i mr-2"></i>Update
                    </a>
                    
                    <!-- Delete Option -->
                    <a class="dropdown-item btn btn-outline-light text-danger" href="#" data-toggle="modal" data-target="#deleteCoordinator{{ $coordinator->id }}">
                      <i class="ph ph-trash custom-icons-i mr-2"></i>Delete
                    </a>
                  </div>
                </div>
                
                <!-- Delete Confirmation Modal -->
                <div class="modal fade" id="deleteCoordinator{{ $coordinator->id }}" tabindex="-1" role="dialog">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header bg-light">
                        <h5 class="modal-title">
                          <i class="ph-bold ph-warning details-icons-i mr-1"></i>
                          Confirm Account Deletion
                        </h5>                
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <p class="text-left">Are you sure you want to delete <strong>{{ $coordinator->user->fname }} {{ $coordinator->user->lname }}</strong> ({{ $coordinator->faculty_id }})? This action cannot be undone.</p>
                        <p class="text-danger small text-left"><strong>WARNING:</strong> All associated data will also be deleted.</p>
                      </div>
                      <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <form action="{{ route('coordinators.destroy', $coordinator->id) }}" method="POST">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                      </div>
                    </div>
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

    <!-- Admin: Coordinators Management Table -->
    <script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#coordinatorsTable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "emptyTable": "No coordinator data found.",
                "search": "_INPUT_",
                "searchPlaceholder": "Search...",
                "lengthMenu": "Show _MENU_ entries",
                "info": "Showing _START_ to _END_ of _TOTAL_ coordinators",
                "paginate": {
                    "previous": "«",
                    "next": "»"
                }
            },
            "columnDefs": [
                { "orderable": false, "targets": [7] } // Disable sorting for Actions column
            ],
            "initComplete": function() {
                // Hide loading overlay when table is ready
                $('#tableLoadingOverlay').fadeOut();
            }
        });
    });
    </script>
@endsection

