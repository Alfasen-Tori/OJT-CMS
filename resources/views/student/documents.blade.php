@php use App\Models\InternDocument; @endphp
@extends('layouts.intern')

@section('title', 'Manage Requirements')

@section('content')
<section class="content-header px-0 px-sm-2">
  <div class="container-fluid =">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="page-header">REQUIREMENTS</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item fw-medium">Intern</li>
          <li class="breadcrumb-item active text-muted">Docs</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
    <div class="container-fluid px-0 px-sm-2">
        <div class="card shadow-sm ">
            <div class="card-body">                
                <!-- Document Counter -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">
                        <span id="statusBadge" class="badge py-2 px-3 
                            @if($documents->count() >= 9)
                                bg-success-subtle text-success
                            @else
                                bg-warning-subtle text-warning
                            @endif">
                            <i class="ph-fill custom-icons-i
                                @if($documents->count() >= 9)
                                    ph-seal-check
                                @else
                                    ph-seal-question
                                @endif 
                                mr-1"></i>
                            <span id="statusText">
                                @if($documents->count() >= 9)
                                    Complete
                                @else
                                    Incomplete
                                @endif
                            </span>
                            (<span id="documentCounter">{{ $documents->count() }}</span>/9)
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
                                @foreach(InternDocument::typeLabels() as $type => $label)
                                @php $document = $documents->where('type', $type)->first(); @endphp
                                <tr data-document-type="{{ $type }}">
                                    <td class="align-middle ps-3">{{ $label }}</td>
                                    <td class="text-muted small align-middle">
                                        @switch($type)
                                            @case('requirements_checklist') Signed checklist of all required documents @break
                                            @case('certificate_of_registration') Current semester registration certificate @break
                                            @case('report_of_grades') Latest official transcript with OJT qualification @break
                                            @case('application_resume') Formal application letter with updated resume @break
                                            @case('medical_certificate') Health clearance from university clinic @break
                                            @case('parent_consent') Notarized consent form from parent/guardian @break
                                            @case('insurance_certificate') Proof of valid insurance coverage @break
                                            @case('pre_deployment_certification') Certification of orientation attendance @break
                                            @case('ojt_fee_reciept') Official reciept of paid internship fee @break
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
                                            <button class="btn btn-outline-primary rounded-pill dropdown-toggle" type="button" id="actionDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="ph-fill ph-gear custom-icons-i"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right p-0 overflow-hidden" aria-labelledby="actionDropdown">
                                                <button class="dropdown-item btn btn-outline-primary view-document w-100 fw-medium border-bottom border-lightgray btn-flat py-2" 
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
                        @foreach(InternDocument::typeLabels() as $type => $label)
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
                                            @case('requirements_checklist') Signed checklist of all required documents @break
                                            @case('certificate_of_registration') Current semester registration certificate @break
                                            @case('report_of_grades') Latest official transcript with OJT qualification @break
                                            @case('application_resume') Formal application letter with updated resume @break
                                            @case('medical_certificate') Health clearance from university clinic @break
                                            @case('parent_consent') Notarized consent form from parent/guardian @break
                                            @case('insurance_certificate') Proof of valid insurance coverage @break
                                            @case('pre_deployment_certification') Certification of orientation attendance @break
                                            @case('ojt_fee_reciept') Official reciept of paid internship fee @break
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
@endsection

@push('scripts')

@endpush