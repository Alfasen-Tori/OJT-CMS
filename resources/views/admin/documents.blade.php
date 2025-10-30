{{-- resources/views/admin/coordinators/documents.blade.php --}}
@php use App\Models\CoordinatorDocument; @endphp
@extends('layouts.admin')

@section('title', 'Coordinator Documents')

@section('content')
<section class="content-header">
  <div class="container-fluid px-3">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="page-header">COORDINATOR DOCUMENTS</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item fw-medium">Admin</li>
          <li class="breadcrumb-item"><a href="{{ route('admin.coordinators') }}">Coordinators</a></li>
          <li class="breadcrumb-item active text-muted">Documents</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
    <div class="row" style="min-height: 600px;">
      <div class="col-md-4 d-flex flex-column justify-content-between">
        <!-- Coordinator Info Card -->
        <div class="card">
          <div class="card-header bg-light">
            <h5 class="card-title mb-0">Coordinator Information</h5>
          </div>
          <div class="card-body">
            <div class="text-center mb-3">
              <img src="{{ asset('storage/' . $coordinator->user->pic) }}" 
                   alt="Profile Picture" 
                   class="rounded-circle mb-2" 
                   width="80" height="80"
                   style="object-fit: cover;">
              <h5 class="mb-1">{{ $coordinator->user->fname }} {{ $coordinator->user->lname }}</h5>
              <p class="text-muted mb-1">{{ $coordinator->faculty_id }}</p>
              <p class="text-muted mb-2">{{ $coordinator->department->short_name ?? 'N/A' }}</p>
            </div>
            
            <div class="border-top pt-3">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-medium">Email:</span>
                <span class="text-muted">{{ $coordinator->user->email }}</span>
              </div>
              <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-medium">Contact:</span>
                <span class="text-muted">{{ $coordinator->user->contact }}</span>
              </div>
              <div class="d-flex justify-content-between align-items-center">
                <span class="fw-medium">HTE Privilege:</span>
                <span class="badge {{ $coordinator->can_add_hte ? 'bg-success' : 'bg-secondary' }}">
                  {{ $coordinator->can_add_hte ? 'Allowed' : 'Not Allowed' }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Status Card - Takes remaining space -->
        <div class="card mt-1 flex-grow-1 mb-0">
          <div class="card-header bg-light">
            <h5 class="card-title mb-0">Honorarium Status</h5>
          </div>
          <div class="card-body d-flex flex-column">
            @php
              $statusClass = [
                'pending documents' => 'bg-warning',
                'for validation' => 'bg-info',
                'eligible for claim' => 'bg-success',
                'claimed' => 'bg-secondary'
              ][$coordinator->status] ?? 'bg-light text-dark';
            @endphp
            
            <div class="text-center flex-grow-1 d-flex flex-column justify-content-center">
              <span class="badge {{ $statusClass }} px-3 py-2 mb-4" style="font-size: 16px">
                {{ ucfirst($coordinator->status) }}
              </span>
              
              <p class="small text-muted mb-4">
                {{ $documents->count() }}/6 documents submitted
              </p>
              
              <!-- Action Buttons -->
              @if($coordinator->status === 'for validation')
                <button id="approveBtn" class="btn btn-success w-100 mb-2">
                  <i class="ph ph-check-circle mr-2"></i>Approve Documents
                </button>
                <small class="text-muted">Approve all documents and mark as eligible for claim</small>
              @elseif($coordinator->status === 'eligible for claim')
                <button id="markClaimedBtn" class="btn btn-primary w-100 mb-2">
                  <i class="ph ph-currency-circle-dollar mr-2"></i>Mark as Claimed
                </button>
                <small class="text-muted">Mark honorarium as claimed by coordinator</small>
              @elseif($coordinator->status === 'claimed')
                <div class="alert alert-secondary mb-0">
                  <i class="ph ph-seal-check mr-2"></i>Honorarium has been claimed
                </div>
              @else
                <div class="alert alert-warning mb-0">
                  <i class="ph ph-clock mr-2"></i>Waiting for all documents to be submitted
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-8">
        <!-- Documents Card - Full height -->
        <div class="card h-100">
          <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Required Documents</h5>
            <span class="badge {{ $documents->count() >= 6 ? 'bg-success' : 'bg-warning' }}">
              {{ $documents->count() }}/6 Submitted
            </span>
          </div>
          <div class="card-body p-0 d-flex flex-column">
            <div class="table-responsive flex-grow-1">
              <table class="table table-bordered mb-0">
                <thead class="table-light">
                  <tr>
                    <th width="40%">Document Name</th>
                    <th width="20%">Status</th>
                    <th width="25%">Submitted Date</th>
                    <th width="15%">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach(CoordinatorDocument::typeLabels() as $type => $label)
                    @php $document = $documents->where('type', $type)->first(); @endphp
                    <tr>
                      <td class="align-middle">
                        <div>
                          <strong class="d-block">{{ $label }}</strong>
                          <small class="text-muted">
                            @switch($type)
                              @case('consolidated_moas') Consolidated and notarized Memorandum of Agreements for all interns @break
                              @case('consolidated_sics') Consolidated and notarized Student Internship Contracts @break
                              @case('annex_cmo104') ANEXX CMO104 Series of 2017 compliance document @break
                              @case('honorarium_request') Official honorarium request form from the President's office @break
                              @case('special_order') Special Order issued by the President @break
                              @case('board_resolution') Board Resolution approving the honorarium @break
                            @endswitch
                          </small>
                        </div>
                      </td>
                      <td class="align-middle text-center">
                        @if($document)
                          <span class="badge bg-success-subtle text-success py-2 px-3">
                            <i class="ph ph-check-circle mr-1"></i>Submitted
                          </span>
                        @else
                          <span class="badge bg-danger-subtle text-danger py-2 px-3">
                            <i class="ph ph-x-circle mr-1"></i>Missing
                          </span>
                        @endif
                      </td>
                      <td class="align-middle text-center">
                        @if($document)
                          <span class="text-success">
                            {{ $document->created_at->format('M d, Y') }}
                          </span>
                          <br>
                          <small class="text-muted">
                            {{ $document->created_at->format('g:i A') }}
                          </small>
                        @else
                          <span class="text-muted">-</span>
                        @endif
                      </td>
                      <td class="align-middle text-center">
                        @if($document)
                          <div class="btn-group-vertical btn-group-sm">
                            <button class="btn btn-outline-primary view-document" 
                                    data-url="{{ Storage::url($document->file_path) }}"
                                    data-label="{{ $label }}">
                              <i class="ph ph-eye mr-1"></i>View
                            </button>
                            <a href="{{ Storage::url($document->file_path) }}" 
                               class="btn btn-outline-success" 
                               target="_blank"
                               download>
                              <i class="ph ph-download mr-1"></i>Download
                            </a>
                          </div>
                        @else
                          <span class="text-muted small">No document</span>
                        @endif
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <div class="card-footer bg-light mt-auto">
              <div class="row">
                <div class="col-md-6">
                  <small class="text-muted">
                    <i class="ph ph-info mr-1"></i>
                    All 6 documents must be submitted before approval
                  </small>
                </div>
                <div class="col-md-6 text-end">
                  <small class="text-muted">
                    Last updated: {{ $coordinator->updated_at->format('M d, Y g:i A') }}
                  </small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Document Preview Modal -->
<div class="modal fade" id="documentModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="documentTitle"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-0">
        <iframe id="documentFrame" src="" style="width:100%; height:70vh;" frameborder="0"></iframe>
      </div>
      <div class="modal-footer">
        <a id="downloadLink" href="#" class="btn btn-primary" target="_blank">
          <i class="ph ph-download mr-1"></i>Download
        </a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

    @include('layouts.partials.scripts-main')

    <!-- ADMIN: Coordinator Documents Management -->
    <script>
    $(document).ready(function() {
        // View document
        $('.view-document').click(function() {
            const url = $(this).data('url');
            const label = $(this).data('label');
            
            $('#documentTitle').text(label);
            $('#documentFrame').attr('src', url);
            $('#downloadLink').attr('href', url);
            $('#documentModal').modal('show');
        });

        // Approve documents
        $('#approveBtn').click(function() {
            if (confirm('Are you sure you want to approve all documents and mark this coordinator as eligible for claim?')) {
                updateStatus('eligible for claim');
            }
        });

        // Mark as claimed
        $('#markClaimedBtn').click(function() {
            if (confirm('Are you sure you want to mark this honorarium as claimed?')) {
                updateStatus('claimed');
            }
        });

        function updateStatus(newStatus) {
            const button = $(event.target);
            const originalText = button.html();
            
            button.prop('disabled', true).html('<i class="ph ph-circle-notch ph-spin mr-2"></i>Processing...');
            
            $.ajax({
                url: '{{ route("admin.coordinators.update-status", $coordinator->id) }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: newStatus
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message, 'Success');
                        
                        // Update UI
                        $('.badge.bg-warning, .badge.bg-info, .badge.bg-success, .badge.bg-secondary')
                            .removeClass('bg-warning bg-info bg-success bg-secondary')
                            .addClass(response.new_status === 'eligible for claim' ? 'bg-success' : 
                                    response.new_status === 'claimed' ? 'bg-secondary' : 'bg-info')
                            .text(response.display_status);
                        
                        // Reload page to show updated buttons
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    }
                },
                error: function(xhr) {
                    button.prop('disabled', false).html(originalText);
                    
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        toastr.error(xhr.responseJSON.message, 'Error');
                    } else {
                        toastr.error('An error occurred while updating status', 'Error');
                    }
                }
            });
        }

        // Handle modal iframes - clean up when modal is closed
        $('#documentModal').on('hidden.bs.modal', function() {
            $('#documentFrame').attr('src', '');
        });
    });
    </script>

@endsection