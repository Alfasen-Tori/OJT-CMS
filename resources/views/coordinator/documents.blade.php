{{-- resources/views/coordinator/documents.blade.php --}}
@php use App\Models\CoordinatorDocument; @endphp
@extends('layouts.coordinator')

@section('title', 'Documents')

@section('content')
@include('layouts.partials.scripts-main')

<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-8">
        <h1 class="page-header">HONORARIUM REQUIREMENTS</h1>
      </div>
      <div class="col-sm-4">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item fw-medium">Coordinator</li>
          <li class="breadcrumb-item active text-muted">Docs</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-body">                
                <!-- Status and Document Counter -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">
                        <span id="statusBadge" class="badge py-2 px-3 
                            @if($coordinator->status === 'eligible for claim' || $coordinator->status === 'claimed')
                                bg-success-subtle text-success
                            @elseif($coordinator->status === 'for validation')
                                bg-info-subtle text-info
                            @else
                                bg-warning-subtle text-warning
                            @endif">
                            <i class="ph-fill custom-icons-i
                                @if($coordinator->status === 'eligible for claim' || $coordinator->status === 'claimed')
                                    ph-seal-check
                                @elseif($coordinator->status === 'for validation')
                                    ph-seal-warning
                                @else
                                    ph-seal-question
                                @endif 
                                mr-1"></i>
                            <span id="statusText">{{ ucfirst($coordinator->status) }}</span>
                            (<span id="documentCounter">{{ $documents->count() }}</span>/7)
                        </span>
                    </h5>
                </div>
                
                <!-- Desktop Table View -->
                <div class="d-none d-md-block">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="thead-white bg-light">
                                <tr>
                                    <th width="45%">Document Name</th>
                                    <th width="">Description</th>
                                    <th width="10%">Status</th>
                                    <th width="13%" style="white-space: no-wrap;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(CoordinatorDocument::typeLabels() as $type => $label)
                                @php $document = $documents->where('type', $type)->first(); @endphp
                                <tr data-document-type="{{ $type }}">
                                    <td class="align-middle ps-3">{{ $label }}</td>
                                    <td class="text-muted small align-middle">
                                        @switch($type)
                                            @case('consolidated_moas') Consolidated and notarized Memorandum of Agreements for all interns @break
                                            @case('consolidated_sics') Consolidated and notarized Student Internship Contracts @break
                                            @case('annex_c') ANNEX C CMO104 Series of 2017 compliance document @break
                                            @case('annex_d') ANNEX D CMO104 Series of 2017 compliance document @break
                                            @case('honorarium_request') Official honorarium request form from the President's office @break
                                            @case('special_order') Special Order issued by the President @break
                                            @case('board_resolution') Board Resolution approving the honorarium @break
                                        @endswitch
                                    </td>
                                    <td class="text-center align-middle">
                                        @if($document)
                                            <span class="badge bg-success-subtle text-success py-2 px-3 rounded-4 w-100 status-badge">Submitted</span><br>
                                            <small class="text-muted">{{ $document->created_at->format('Y-m-d') }}</small>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger py-2 px-3 rounded-pill w-100 status-badge">Missing</span>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">
                                        @if($document)
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="actionDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="ph-fill ph-gear custom-icons-i"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right py-0 overflow-hidden" aria-labelledby="actionDropdown">
                                                <button class="dropdown-item btn btn-outline-light view-document w-100 fw-medium border-bottom border-lightgray btn-flat text-dark py-2" 
                                                        data-url="{{ Storage::url($document->file_path) }}">
                                                    <i class="ph ph-eye custom-icons-i"></i>
                                                    <span>View</span>
                                                </button>
                                                <button class="dropdown-item btn btn-outline-light remove-document w-100 fw-medium btn-flat text-danger py-2" 
                                                        data-id="{{ $document->id }}">
                                                    <i class="ph ph-trash custom-icons-i"></i>
                                                    <span>Delete</span>
                                                </button>
                                            </div>
                                        </div>
                                        @else
                                            <button class="btn btn-sm btn-outline-success upload-document fw-medium" 
                                                    data-type="{{ $type }}">
                                                <i class="ph-fill ph-upload custom-icons-i"></i>
                                                <span>Upload</span>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Mobile Card View -->
                <div class="d-md-none">
                    <div class="row g-2">
                        @foreach(CoordinatorDocument::typeLabels() as $type => $label)
                        @php $document = $documents->where('type', $type)->first(); @endphp
                        <div class="col-12" data-document-type="{{ $type }}">
                            <div class="card border shadow-sm">
                                <div class="card-body">
                                    <!-- Header with Document Name and Status -->
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h6 class="card-title fw-bold mb-0 text-break">{{ $label }}</h6>
                                        <div class="text-end">
                                            @if($document)
                                                <span class="badge bg-success-subtle text-success py-1 px-2 rounded-4">Submitted</span>
                                                <small class="d-block text-muted">{{ $document->created_at->format('Y-m-d') }}</small>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger py-1 px-2 rounded-pill">Missing</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Description -->
                                    <p class="card-text text-muted small mb-3">
                                        @switch($type)
                                            @case('consolidated_moas') Consolidated and notarized Memorandum of Agreements for all interns @break
                                            @case('consolidated_sics') Consolidated and notarized Student Internship Contracts @break
                                            @case('annex_c') ANEXX CMO104 Series of 2017 compliance document @break
                                            @case('annex_d') ANEXX CMO104 Series of 2017 compliance document @break
                                            @case('honorarium_request') Official honorarium request form from the President's office @break
                                            @case('special_order') Special Order issued by the President @break
                                            @case('board_resolution') Board Resolution approving the honorarium @break
                                        @endswitch
                                    </p>
                                    
                                    <!-- Action Buttons -->
                                    <div class="d-grid gap-2">
                                        @if($document)
                                            <div class="btn-group w-100" role="group">
                                                <button class="btn btn-outline-primary view-document flex-fill" 
                                                        data-url="{{ Storage::url($document->file_path) }}">
                                                    <i class="ph ph-eye custom-icons-i me-1"></i>
                                                    View
                                                </button>
                                                <button class="btn btn-outline-danger remove-document flex-fill" 
                                                        data-id="{{ $document->id }}">
                                                    <i class="ph ph-trash custom-icons-i me-1"></i>
                                                    Delete
                                                </button>
                                            </div>
                                        @else
                                            <button class="btn btn-success upload-document w-100" 
                                                    data-type="{{ $type }}">
                                                <i class="ph-fill ph-upload custom-icons-i me-1"></i>
                                                Upload Document
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
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
                <a id="downloadLink" href="#" class="btn btn-primary fw-medium">
                    <i class="ph-fill ph-download custom-icons-i mr-1"></i>Download
                </a>
                <button type="button" class="btn btn-secondary fw-medium" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Document Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">Upload Document</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="uploadForm" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" id="documentType">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="documentFile">Select PDF File (max 5MB)</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="documentFile" name="document" accept=".pdf" required>
                            <label class="custom-file-label" for="documentFile">Choose file</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="submit" class="btn btn-success fw-medium">
                        <i class="ph-fill ph-upload custom-icons-i mr-1"></i>Upload
                    </button>
                    <button type="button" class="btn btn-secondary fw-medium" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <!-- Coordinator: Documents Management -->
    <script>
    $(document).ready(function() {
        // Upload document modal
        $('.upload-document').click(function() {
            const type = $(this).data('type');
            const label = $(this).closest('tr').find('td:first').text() || 
                        $(this).closest('.card').find('.card-title').text();
            
            $('#documentType').val(type);
            $('#uploadModal .modal-title').text('Upload ' + label);
            $('#uploadModal').modal('show');
        });

        // View document
        $('.view-document').click(function() {
            const url = $(this).data('url');
            const label = $(this).closest('tr').find('td:first').text() || 
                        $(this).closest('.card-body').find('.card-title').text();
            
            $('#documentTitle').text(label);
            $('#documentFrame').attr('src', url);
            $('#downloadLink').attr('href', url);
            $('#documentModal').modal('show');
        });

        // Remove document
        $('.remove-document').click(function() {
            const documentId = $(this).data('id');
            const row = $(this).closest('tr');
            const card = $(this).closest('.col-12');
            const documentType = row.data('document-type') || card.data('document-type');
            
            if (confirm('Are you sure you want to delete this document?')) {
                $.ajax({
                    url: '{{ route("coordinator.documents.delete", "") }}/' + documentId,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Document deleted successfully!', 'Success', {
                                timeOut: 3000,
                                progressBar: true
                            });
                            
                            // Update UI without page reload
                            updateUIAfterDelete(response, documentType);
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Error deleting document. Please try again.', 'Error', {
                            timeOut: 5000,
                            progressBar: true
                        });
                    }
                });
            }
        });

        // Upload form submission
        $('#uploadForm').submit(function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();
            const documentType = $('#documentType').val();
            
            submitBtn.prop('disabled', true).html('<i class="ph ph-circle-notch ph-spin custom-icons-i mr-1"></i>Uploading...');
            
            $.ajax({
                url: '{{ route("coordinator.documents.upload") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#uploadModal').modal('hide');
                    toastr.success('Document uploaded successfully!', 'Success', {
                        timeOut: 3000,
                        progressBar: true
                    });
                    
                    // Update UI without page reload
                    updateUIAfterUpload(response, documentType);
                },
                error: function(xhr) {
                    let errorMessage = 'Error uploading document. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    }
                    toastr.error(errorMessage, 'Error', {
                        timeOut: 5000,
                        progressBar: true
                    });
                    submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });

        // Function to update UI after successful upload
        function updateUIAfterUpload(response, documentType) {
            updateStatusUI(response);
            
            // Update the specific row/card that was uploaded
            const row = $(`[data-document-type="${documentType}"]`);
            
            if (row.length) {
                // Desktop table view
                if (row.is('tr')) {
                    // Clear existing status content and replace
                    const statusTd = row.find('td').eq(2);
                    statusTd.html(`
                        <span class="badge bg-success-subtle text-success py-2 px-3 rounded-4 w-100 status-badge">Submitted</span><br>
                        <small>${new Date().toISOString().split('T')[0]}</small>
                    `);
                    
                    // Replace upload button with dropdown
                    const actionHtml = `
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="actionDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ph-fill ph-gear custom-icons-i"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right py-0 overflow-hidden" aria-labelledby="actionDropdown">
                                <button class="dropdown-item btn btn-outline-light view-document w-100 fw-medium border-bottom border-lightgray btn-flat text-dark py-2" 
                                        data-url="${response.document.file_path}">
                                    <i class="ph ph-eye custom-icons-i"></i>
                                    <span>View</span>
                                </button>
                                <button class="dropdown-item btn btn-outline-light remove-document w-100 fw-medium btn-flat text-danger py-2" 
                                        data-id="${response.document.id}">
                                    <i class="ph ph-trash custom-icons-i"></i>
                                    <span>Delete</span>
                                </button>
                            </div>
                        </div>
                    `;
                    row.find('td:last').html(actionHtml);
                } 
                // Mobile card view
                else {
                    const statusDiv = row.find('.text-end');
                    statusDiv.html(`
                        <span class="badge bg-success-subtle text-success py-1 px-2 rounded-4">Submitted</span>
                        <small class="d-block text-muted">${new Date().toISOString().split('T')[0]}</small>
                    `);
                    
                    const actionHtml = `
                        <div class="btn-group w-100" role="group">
                            <button class="btn btn-outline-primary view-document flex-fill" 
                                    data-url="${response.document.file_path}">
                                <i class="ph ph-eye custom-icons-i me-1"></i>
                                View
                            </button>
                            <button class="btn btn-outline-danger remove-document flex-fill" 
                                    data-id="${response.document.id}">
                                <i class="ph ph-trash custom-icons-i me-1"></i>
                                Delete
                            </button>
                        </div>
                    `;
                    row.find('.d-grid').html(actionHtml);
                }
                
                // Re-bind event listeners to the new buttons
                bindEventListeners();
            }
        }

        // Function to update UI after successful delete
        function updateUIAfterDelete(response, documentType) {
            updateStatusUI(response);
            
            // Update the specific row/card that was deleted
            const row = $(`[data-document-type="${documentType}"]`);
            
            if (row.length) {
                // Desktop table view
                if (row.is('tr')) {
                    // Clear existing status content and replace
                    const statusTd = row.find('td').eq(2);
                    statusTd.html(`
                        <span class="badge bg-danger-subtle text-danger py-2 px-3 rounded-pill w-100 status-badge">Missing</span>
                    `);
                    
                    // Replace dropdown with upload button
                    const actionHtml = `
                        <button class="btn btn-sm btn-outline-success upload-document fw-medium" 
                                data-type="${documentType}">
                            <i class="ph-fill ph-upload custom-icons-i"></i>
                            <span>Upload</span>
                        </button>
                    `;
                    row.find('td:last').html(actionHtml);
                } 
                // Mobile card view
                else {
                    const statusDiv = row.find('.text-end');
                    statusDiv.html(`
                        <span class="badge bg-danger-subtle text-danger py-1 px-2 rounded-pill">Missing</span>
                    `);
                    
                    const actionHtml = `
                        <button class="btn btn-success upload-document w-100" 
                                data-type="${documentType}">
                            <i class="ph-fill ph-upload custom-icons-i me-1"></i>
                            Upload Document
                        </button>
                    `;
                    row.find('.d-grid').html(actionHtml);
                }
                
                // Re-bind event listeners to the new buttons
                bindEventListeners();
            }
        }

        // Function to update status UI (counter, badge, etc.)
        function updateStatusUI(response) {
            // Update document counter
            $('#documentCounter').text(response.document_count);
            
            // Format status text for display (capitalize first letter of each word)
            const formatStatusText = (status) => {
                return status.split(' ')
                    .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                    .join(' ');
            };
            
            const displayStatus = formatStatusText(response.status);
            $('#statusText').text(displayStatus);
            
            // Update badge appearance based on new status
            const statusBadge = $('#statusBadge');
            statusBadge.removeClass('bg-success-subtle bg-info-subtle bg-warning-subtle text-success text-info text-warning');
            
            switch(response.status) {
                case 'eligible for claim':
                case 'claimed':
                    statusBadge.addClass('bg-success-subtle text-success');
                    break;
                case 'for validation':
                    statusBadge.addClass('bg-info-subtle text-info');
                    break;
                default:
                    statusBadge.addClass('bg-warning-subtle text-warning');
            }
            
            // Update status icon
            const statusIcon = statusBadge.find('i');
            statusIcon.removeClass('ph-seal-check ph-seal-warning ph-seal-question');
            
            switch(response.status) {
                case 'eligible for claim':
                case 'claimed':
                    statusIcon.addClass('ph-seal-check');
                    break;
                case 'for validation':
                    statusIcon.addClass('ph-seal-warning');
                    break;
                default:
                    statusIcon.addClass('ph-seal-question');
            }
        }

        // Function to bind event listeners to dynamically created elements
        function bindEventListeners() {
            // Re-bind upload document buttons
            $('.upload-document').off('click').on('click', function() {
                const type = $(this).data('type');
                const label = $(this).closest('tr').find('td:first').text() || 
                            $(this).closest('.card').find('.card-title').text();
                
                $('#documentType').val(type);
                $('#uploadModal .modal-title').text('Upload ' + label);
                $('#uploadModal').modal('show');
            });

            // Re-bind view document buttons
            $('.view-document').off('click').on('click', function() {
                const url = $(this).data('url');
                const label = $(this).closest('tr').find('td:first').text() || 
                            $(this).closest('.card-body').find('.card-title').text();
                
                $('#documentTitle').text(label);
                $('#documentFrame').attr('src', url);
                $('#downloadLink').attr('href', url);
                $('#documentModal').modal('show');
            });

            // Re-bind remove document buttons
            $('.remove-document').off('click').on('click', function() {
                const documentId = $(this).data('id');
                const row = $(this).closest('tr');
                const card = $(this).closest('.col-12');
                const documentType = row.data('document-type') || card.data('document-type');
                
                if (confirm('Are you sure you want to delete this document?')) {
                    $.ajax({
                        url: '{{ route("coordinator.documents.delete", "") }}/' + documentId,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success('Document deleted successfully!', 'Success', {
                                    timeOut: 3000,
                                    progressBar: true
                                });
                                
                                updateUIAfterDelete(response, documentType);
                            }
                        },
                        error: function(xhr) {
                            toastr.error('Error deleting document. Please try again.', 'Error', {
                                timeOut: 5000,
                                progressBar: true
                            });
                        }
                    });
                }
            });
        }

        // Reset form when modal is closed
        $('#uploadModal').on('hidden.bs.modal', function() {
            $('#uploadForm')[0].reset();
            $('#uploadForm button[type="submit"]').prop('disabled', false).html('<i class="ph-fill ph-upload custom-icons-i mr-1"></i>Upload');
        });

        // Handle modal iframes - clean up when modal is closed
        $('#documentModal').on('hidden.bs.modal', function() {
            $('#documentFrame').attr('src', '');
        });

        // Define document type labels for JavaScript use
        const CoordinatorDocument = {
            typeLabels: {
                'consolidated_moas': 'Consolidated Notarized MOAs',
                'consolidated_sics': 'Consolidated Notarized SICs',
                'annex_c': 'ANNEX C Series of 2017',
                'annex_d': 'ANNEX D Series of 2017',
                'honorarium_request': 'Honorarium Request',
                'special_order': 'Special Order',
                'board_resolution': 'Board Resolution'
            }
        };
    });
    </script>

@endsection