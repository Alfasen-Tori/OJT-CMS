{{-- resources/views/coordinator/documents.blade.php --}}
@php use App\Models\CoordinatorDocument; @endphp
@extends('layouts.coordinator')

@section('title', 'Documents')

@section('content')
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
                                            @case('annex_c') ANEXX CMO104 Series of 2017 compliance document @break
                                            @case('annex_d') ANEXX CMO104 Series of 2017 compliance document @break
                                            @case('honorarium_request') Official honorarium request form from the President's office @break
                                            @case('special_order') Special Order issued by the President @break
                                            @case('board_resolution') Board Resolution approving the honorarium @break
                                        @endswitch
                                    </td>
                                    <td class="text-center align-middle">
                                        @if($document)
                                            <span class="badge bg-success-subtle text-success py-2 px-3 rounded-4 w-100 status-badge">Submitted</span><br>
                                            <small>{{ $document->created_at->format('Y-m-d') }}</small>
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
                        <input type="file" class="form-control" id="documentFile" name="document" accept=".pdf" required>
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
@endsection