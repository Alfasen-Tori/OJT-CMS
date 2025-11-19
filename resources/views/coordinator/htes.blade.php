@extends('layouts.coordinator')

@section('title', 'Manage HTEs')

@section('content')
@php
    $canManageHTEs = auth()->user()->coordinator->can_add_hte == 1;
@endphp

<section class="content-header">
  @include('layouts.partials.scripts-main')

  <div class="container-fluid px-3">
    <div class="row mb-2">
      <div class="col-sm-8">
        <h1 class="page-header">HOST TRAINING ESTABLISHMENTS</h1>
      </div>
      <div class="col-sm-4">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item fw-medium">Coordinator</li>
          <li class="breadcrumb-item active text-muted">HTEs</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
    <div class="container-fluid">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 px-2">
          @if($canManageHTEs)
          <div class="d-flex flex-grow-1 justify-content-end p-0">
            <!-- <button class="btn btn-outline-success d-flex mr-2" id="importBtn">
              <span class="d-none d-sm-inline mr-1 fw-medium">Deploy</span>
              <i class="ph-bold ph-arrow-square-out custom-icons-i"></i>              
            </button> -->
            <a href="{{ route('coordinator.new_h') }}" class="btn btn-primary d-flex" id="registerBtn">
              <span>Register new</span>
            </a>
          </div>
          @endif
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
            <span class="text-primary">Loading HTEs . . .</span>
          </div>
          <table id="htesTable" class="table table-bordered mb-0">
            <thead class="table-light">
              <tr>
                <th width="11%">HTE ID</th>
                <th>Name</th>
                <th>Representative</th>
                <th>Slots</th>
                <th width="10%">MOA Status</th>
                <th width="3%">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($htes as $hte)
              @php
                  $assigned = $hte->interns_hte_count;
                  $slots = $hte->slots;
                  $available = $slots - $assigned;
                  $textClass = $available > 0 ? 'text-success' : 'text-danger';
              @endphp
              <tr>
                <td>HTE-{{ str_pad($hte->id, 3, '0', STR_PAD_LEFT) }}</td>
                <td class="align-middle">
                    @if($hte->logo) {{-- Assuming there's a logo field --}}
                        <img src="{{ asset('storage/' . $hte->logo) }}" 
                            alt="Organization Logo" 
                            class="rounded-circle me-2" 
                            width="30" height="30">
                    @else
                        @php
                            // Generate a consistent random color based on organization name
                            $orgName = $hte->organization_name;
                            $colors = [
                                'linear-gradient(135deg, #007bff, #6610f2)', // Blue to Purple
                                'linear-gradient(135deg, #28a745, #20c997)', // Green to Teal
                                'linear-gradient(135deg, #dc3545, #fd7e14)', // Red to Orange
                                'linear-gradient(135deg, #6f42c1, #e83e8c)', // Purple to Pink
                                'linear-gradient(135deg, #17a2b8, #6f42c1)', // Teal to Purple
                                'linear-gradient(135deg, #fd7e14, #e83e8c)', // Orange to Pink
                            ];
                            
                            $colorIndex = crc32($orgName) % count($colors);
                            $randomGradient = $colors[$colorIndex];
                            
                            // Get organization initial
                            $initial = strtoupper(substr($orgName, 0, 1));
                        @endphp
                        
                        <div class="rounded-circle me-2 d-inline-flex align-items-center justify-content-center text-white fw-bold" 
                            style="width: 30px; height: 30px; font-size: 11px; background: {{ $randomGradient }};">
                            {{ $initial }}
                        </div>
                    @endif
                    {{ $hte->organization_name }}
                </td>
                <td>{{ $hte->user->fname}}  {{ $hte->user->lname}}</td>

                <td class="align-middle text-center text-medium {{ $textClass }}">
                  {{ $available }}
                </td>
                <td class="">
                @if($hte->moa_path)
                  @if($hte->moa_is_signed === 'yes')
                    <span class="small badge bg-success-subtle text-success py-2 px-3 rounded-pill mr-2" style="font-size: 14px">Signed</span>
                  @else
                    <span class="small badge bg-warning-subtle text-warning py-2 px-3 rounded-pill mr-2" style="font-size: 14px">Validation Required</span>
                  @endif
                @else
                  <span class="small badge bg-danger-subtle text-danger py-2 px-3 rounded-pill" style="font-size: 14px">Missing</span>
                @endif
                </td>
                <td class="text-center px-2 align-middle">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary rounded-pill dropdown-toggle" type="button" id="actionDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ph-fill ph-gear custom-icons-i"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right shadow border-0 py-0" aria-labelledby="actionDropdown">
                            <!-- View Option -->
                            <a class="dropdown-item d-flex align-items-center py-2" href="{{ route('coordinator.hte.show', $hte->id) }}">
                                <i class="ph ph-eye custom-icons-i mr-2"></i>View
                            </a>
                            
                            <!-- Unregister Option (conditionally visible) -->
                            @if($canManageHTEs)
                            <a class="dropdown-item d-flex align-items-center py-2" href="{{ route('coordinator.edit_h', $hte->id) }}">
                                <i class="ph ph-wrench custom-icons-i mr-2"></i>Update
                            </a>
                            
                            <div class="dropdown-divider my-1"></div>
                            
                            <a class="dropdown-item d-flex align-items-center py-2 text-danger" href="#" data-toggle="modal" data-target="#unregisterHTE{{ $hte->id }}">
                                <i class="ph ph-trash custom-icons-i mr-2"></i>Unregister
                            </a>
                            @endif
                        </div>
                    </div>
                    <!-- Unregister Confirmation Modal -->
                     <div class="modal fade" id="unregisterHTE{{ $hte->id }}" tabindex="-1" role="dialog">
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
                                    <p class="text-left">Are you sure you want to unregister <strong>{{ $hte->organization_name}}</strong>? This action cannot be undone.</p>
                                    <p class="text-danger small text-left"><strong>WARNING:</strong> Any ongoing internships will be affected.</p>
                                </div>
                                <div class="modal-footer bg-light">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <form action="{{ route('coordinator.hte.destroy', $hte->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Unregister</button>
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
  </div>
</section>

    <!-- Coordinator HTE Management Table -->
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#htesTable').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "language": {
                    "emptyTable": "No HTE data found.",
                    "search": "_INPUT_",
                    "searchPlaceholder": "Search...",
                    "lengthMenu": "Show _MENU_ entries",
                    "info": "Showing _START_ to _END_ of _TOTAL_ HTEs",
                    "paginate": {
                        "previous": "«",
                        "next": "»"
                    }
                },
                "columnDefs": [
                    { "orderable": false, "targets": [5] } // Disable sorting for Actions column
                ],
                "initComplete": function() {
                    // Hide loading overlay when table is ready
                    $('#tableLoadingOverlay').fadeOut();
                }
            });
            
            // Remove the manual search input
            $('.card-header input[type="search"]').parent().remove();
        });
    </script>


<!-- @if(!$canManageHTEs)
<div class="alert alert-warning alert-dismissible fade show" role="alert">
  <strong>Notice:</strong> You have view-only access to HTEs. Management functions are restricted.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endif -->

@endsection