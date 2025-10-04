{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.coordinator')

@section('title', 'Manage Interns')

@section('content')
<section class="content-header">
  <div class="container-fluid px-3">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="page-header">STUDENT INTERNS</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item fw-medium">Coordinator</li>
          <li class="breadcrumb-item active text-muted">Interns</li>
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
              <div class="d-flex flex-grow-1 justify-content-end">
                  <a class="btn btn-outline-success d-flex mr-2" data-toggle="modal" data-target="#importModal">
                      <span class="d-none d-sm-inline fw-medium mr-1">
                          Import
                      </span>
                      <svg xmlns="http://www.w3.org/2000/svg" class="table-cta-icon" viewBox="0 0 256 256">
                          <path d="M200,24H72A16,16,0,0,0,56,40V64H40A16,16,0,0,0,24,80v96a16,16,0,0,0,16,16H56v24a16,16,0,0,0,16,16H200a16,16,0,0,0,16-16V40A16,16,0,0,0,200,24ZM72,160a8,8,0,0,1-6.15-13.12L81.59,128,65.85,109.12a8,8,0,0,1,12.3-10.24L92,115.5l13.85-16.62a8,8,0,1,1,12.3,10.24L102.41,128l15.74,18.88a8,8,0,0,1-12.3,10.24L92,140.5,78.15,157.12A8,8,0,0,1,72,160Zm56,56H72V192h56Zm0-152H72V40h56Zm72,152H144V192a16,16,0,0,0,16-16v-8h40Zm0-64H160V104h40Zm0-64H160V80a16,16,0,0,0-16-16V40h56Z"></path>
                      </svg>                
                  </a>

                  <a href="{{ route('coordinator.new_i') }}" class="btn btn-primary d-flex">
                      <span class="fw-medium">Register new</span>
                  </a>
              </div>
              </div>      


            <div class="card-body table-responsive px-3 py-0">
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
                    <span class="text-primary">Loading interns . . .</span>
                </div>
                <table id="internsTable" class="table table-bordered mb-0">
                    <thead class="table-light">
                    <tr>
                        <th width="15%">Student ID</th>
                        <th style="white-space: no-wrap">Name</th>
                        <th width="5%">Section</th>
                        <th width="5%">Status</th>
                        <th width="3%">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                        @forelse ($interns as $intern)
                        <tr>
                            <td class="align-middle">{{ $intern->student_id }}</td>
                            <td class="align-middle">
                                <img src="{{ asset('storage/' . $intern->user->pic) }}" 
                                    alt="Profile Picture" 
                                    class="rounded-circle me-2 table-pfp" 
                                    width="30" height="30">
                                {{ $intern->user->lname }}, {{ $intern->user->fname }} 
                            </td>                          
                            <td class="align-middle text-center">BS{{ $intern->department->short_name ?? 'N/A' }} {{ $intern->year_level }}{{ strtoupper($intern->section) }}</td>
                            <td class="align-middle">
                                @php
                                    $status = strtolower($intern->status);
                                    $badgeClass = match($status) {
                                        'pending requirements' => 'bg-danger-subtle text-danger',
                                        'ready for deployment' => 'bg-warning-subtle text-warning',
                                        'processing' => 'bg-info-subtle text-info',
                                        'endorsed' => 'bg-primary-subtle text-primary',
                                        'deployed' => 'bg-success-subtle text-success',
                                        default => 'bg-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill">{{ ucfirst($intern->status) }}</span>
                            </td>
                            <td class="text-center px-2 align-middle">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="actionDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ph-fill ph-gear custom-icons-i"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right py-0" aria-labelledby="actionDropdown">
                                        <a class="dropdown-item btn btn-outline-light text-dark" href="{{ route('coordinator.intern.show', $intern->id) }}">
                                            <i class="ph ph-eye custom-icons-i mr-2"></i>View
                                        </a>
                                        <a class="dropdown-item border-top border-bottom border-lightgray btn btn-outline-light text-dark" href="{{ route('coordinator.edit_i', $intern->id) }}">
                                            <i class="ph ph-wrench custom-icons-i mr-2"></i>Update
                                        </a>
                                        <a class="dropdown-item btn btn-outline-light text-danger" href="#" data-toggle="modal" data-target="#removeModal{{ $intern->id }}">
                                            <i class="ph ph-trash custom-icons-i mr-2"></i>Unregister
                                        </a>
                                    </div>
                                </div>

                                <!-- Remove Modal -->
                                <div class="modal fade" id="removeModal{{ $intern->id }}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-light text-white">
                                                <h5 class="modal-title">
                                                    <i class="ph-bold ph-warning details-icons-i mr-1"></i>
                                                    Confirm Account Deletion
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="text-left">Are you sure you want to unregister <strong>{{ $intern->user->fname }} {{ $intern->user->lname }}</strong>? This action cannot be undone.</p>
                                                <p class="text-danger small text-left"><strong>WARNING:</strong> All associated internship records will also be removed.</p>
                                            </div>
                                            <div class="modal-footer bg-light">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                <form action="{{ route('coordinator.intern.destroy', $intern->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger fw-medium">Unregister</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No intern data found.</td>
                        </tr>
                        @endforelse
                    </tbody>     
                </table>
            </div>

          </div>
        </div>

  </div>
  <!-- Import Modal -->
  <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content rounded-3 overflow-hidden">
              <div class="modal-header bg-light text-dark">
                  <h5 class="modal-title" id="importModalLabel"><i class="ph-fill ph-download custom-icons-i mr-1"></i>Import Interns</h5>
                  <button type="button" class="close text-muted" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <form id="importForm" action="{{ route('coordinator.import_interns') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <input type="hidden" name="coordinator_id" value="{{ auth()->user()->coordinator->id }}">
                  <input type="hidden" name="dept_id" value="{{ auth()->user()->coordinator->dept_id }}">
                  
                  <div class="modal-body">
                      <div class="mb-3">
                          <label for="importFile" class="form-label">Excel File</label>
                          <input class="form-control" type="file" id="importFile" name="import_file" accept=".xlsx,.xls,.csv" required>
                          <div class="form-text">Download the <a href="{{ asset('templates/intern_import_template.xlsx') }}" download>import template</a> for reference</div>
                      </div>
                      
                      <div class="alert bg-success-subtle">
                          <strong>File Requirements:</strong>
                          <ul class="mb-0">
                              <li>File must be in Excel format (.xlsx, .xls, or .csv)</li>
                              <li>First row should contain headers matching the template</li>
                              <li>Required fields: First Name, Last Name, Email, Contact, Student ID, Birthdate, Year Level, Section, Academic Year, Semester</li>
                          </ul>
                      </div>
                      
                      <div id="importProgress" class="d-none">
                          <div class="progress mb-3 rounded-pill">
                              <div class="progress-bar progress-bar-animated" role="progressbar" style="width: 0%"></div>
                          </div>
                          <div class="text-center">
                              <div class="spinner-border text-primary" role="status">
                                  <span class="sr-only">Loading...</span>
                              </div>
                              <p class="mt-2 mb-0" id="progressText">Processing import...</p>
                          </div>
                      </div>
                      
                      <div id="importResults" class="d-none mt-3">
                          <h5>Import Summary</h5>
                          <div class="alert alert-success">
                              <strong>Successfully registered:</strong> <span id="successCount">0</span> interns
                          </div>
                          <div class="alert alert-danger">
                              <strong>Failed to register:</strong> <span id="failCount">0</span> interns
                          </div>
                          
                          <div id="failDetails" class="d-none">
                              <h6>Error Details:</h6>
                              <div class="table-responsive">
                                  <table class="table table-sm table-bordered">
                                      <thead>
                                          <tr>
                                              <th>Row</th>
                                              <th>Student ID</th>
                                              <th>Name</th>
                                              <th>Error</th>
                                          </tr>
                                      </thead>
                                      <tbody id="failDetailsBody"></tbody>
                                  </table>
                              </div>
                          </div>
                      </div>
                  </div>
                  
                  <div class="modal-footer bg-light">
                      <button type="submit" id="importSubmit" class="btn btn-success">Import</button>
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  </div>
              </form>
          </div>
      </div>
  </div>

</section>
@endsection
