@extends('layouts.coordinator')

@section('title', 'HTE Details')

@section('content')
<div class="container-fluid">
    <!-- MOA Preview Modal (Unchanged) -->
    <div class="modal fade" id="moaPreviewModal" tabindex="-1" role="dialog" aria-labelledby="moaPreviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light text-dark">
                    <h5 class="modal-title" id="moaPreviewModalLabel">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" style="position: relative; top: -3px" fill="currentColor" viewBox="0 0 256 256"><path d="M80.3,120.26A58.29,58.29,0,0,1,81,97.07C83.32,87,87.89,80,92.1,80c2.57,0,2.94.67,3.12,1,.88,1.61,4,10.93-12.63,46.52A28.87,28.87,0,0,1,80.3,120.26ZM232,56V200a16,16,0,0,1-16,16H40a16,16,0,0,1-16-16V56A16,16,0,0,1,40,40H216A16,16,0,0,1,232,56ZM84,160c2-3.59,3.94-7.32,5.9-11.14,10.34-.32,22.21-7.57,35.47-21.68,5,9.69,11.38,15.25,18.87,16.55,8,1.38,16-2.38,23.94-11.2,6,5.53,16.15,11.47,31.8,11.47a8,8,0,0,0,0-16c-17.91,0-24.3-10.88-24.84-11.86a7.83,7.83,0,0,0-6.54-4.51,8,8,0,0,0-7.25,3.6c-6.78,10-11.87,13.16-14.39,12.73-4-.69-9.15-10-11.23-18a8,8,0,0,0-14-3c-8.88,10.94-16.3,17.79-22.13,21.66,15.8-35.65,13.27-48.59,9.6-55.3C107.35,69.84,102.59,64,92.1,64,79.66,64,69.68,75,65.41,93.46a75,75,0,0,0-.83,29.81c1.7,8.9,5.17,15.73,10.16,20.12-3,5.81-6.09,11.43-9,16.61H56a8,8,0,0,0,0,16h.44c-4.26,7.12-7.11,11.59-7.18,11.69a8,8,0,0,0,13.48,8.62c.36-.55,5.47-8.57,12.29-20.31H200a8,8,0,0,0,0-16Z"></path></svg>
                        Memorandum of Agreement - {{ $hte->organization_name }}
                    </h5>
                    <button type="button" class="close text-secondary" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    @if($hte->moa_path)
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe id="moaPreviewFrame" src="{{ Storage::url($hte->moa_path) }}" 
                                class="embed-responsive-item" 
                                style="border: none;"
                                allowfullscreen></iframe>
                    </div>
                    @else
                    <div class="text-center py-5 bg-light">
                        <i class="fas fa-file-pdf fa-5x text-muted mb-3"></i>
                        <h5 class="text-muted">No MOA Document Available</h5>
                        <p class="text-muted">This HTE hasn't uploaded their MOA yet</p>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    @if($hte->moa_path)
                    <div class="modal-left mr-auto">
                        <a href="{{ Storage::url($hte->moa_path) }}" class="btn btn-outline-light border-0 rounded-4 text-muted py-2" download><i class="ph-fill ph-download custom-icons-i"></i></a>
                        <button type="button" 
                                class="btn btn-outline-light border-0 rounded-4 text-muted py-2" 
                                onclick="document.getElementById('moaPreviewFrame').contentWindow.print();">
                            <i class="ph-fill ph-printer custom-icons-i py-2"></i>
                        </button>
                    </div>
                    
                    <!-- Toggle MOA Status Button -->
                    <button type="button" class="btn {{ $hte->moa_is_signed === 'yes' ? 'btn-warning' : 'btn-primary' }}" 
                            id="toggleMoaStatusBtn" data-hte-id="{{ $hte->id }}">
                        <i class="ph custom-icons-i {{ $hte->moa_is_signed === 'yes' ? 'ph-x' : 'ph-check' }} mr-1"></i>
                        {{ $hte->moa_is_signed === 'yes' ? 'Mark as Unsigned' : 'Mark as Signed' }}
                    </button>
                    @endif
                    
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="accordion" id="hteAccordion">
                <!-- HTE Details Card -->
                <div class="card shadow mb-4">
                    <div class="card-header bg-white text-dark" id="hteDetailsHeading">
                        <h3 class="card-title mb-0 d-flex justify-content-between align-items-center w-100">
                            <div class="title-left d-flex align-items-center">
                                <button class="btn btn-link text-dark text-decoration-none p-0 mr-2 toggle-btn" type="button" data-toggle="collapse" data-target="#hteDetailsCollapse" aria-expanded="true" aria-controls="hteDetailsCollapse">
                                    <i class="ph ph-plus details-icons-i collapsed-icon"></i>
                                    <i class="ph ph-minus details-icons-i expanded-icon"></i>
                                </button>
                                <i class="ph ph-building-apartment details-icons-i mr-2"></i>
                                <span class="fw-bold">{{ $hte->organization_name }}</span> 
                            </div>
                            <div class="title-right">
                                @if($canManage)
                                    <a href="{{ route('coordinator.edit_h', $hte->id) }}" class="btn btn-outline-light border-0 rounded-4 text-muted"><i class="ph ph-wrench details-icons-i p-0"></i></a>
                                    <a class="btn btn-outline-light border-0 rounded-4 text-muted" data-toggle="modal" data-target="#unregisterModal"><i class="ph ph-trash details-icons-i p-0"></i></a>
                                @endif
                            </div>
                        </h3>
                    </div>
                    
                    <div id="hteDetailsCollapse" class="collapse show" aria-labelledby="hteDetailsHeading">
                        <div class="card-body">
                            <div class="row">
                                <!-- HTE Profile Section (Unchanged) -->
                                <div class="col-md-4 d-flex flex-column">
                                    <div class="text-center mb-4">
                                        <img src="{{ asset('storage/' . auth()->user()->pic) }}" 
                                            class="img-thumbnail rounded-circle" 
                                            style="width: 200px; height: 200px; object-fit: cover;"
                                            alt="Organization Logo">
                                    </div>
                                    
                                    <div class="border p-3 rounded bg-light flex-grow-1 mt-0">
                                        <h5 class="mb-3"><i class="ph-fill ph-info details-icons-i mr-2"></i>Basic Information</h5>
                                        <ul class="list-unstyled">
                                            <li class="mb-2 align-middle"><strong>Status:</strong>
                                                @if($hte->moa_path)
                                                    @if($hte->moa_is_signed === 'yes')
                                                        <span class="small badge bg-success-subtle text-success py-2 px-3 rounded-pill mr-2" style="font-size: 14px">Signed</span>
                                                    @else
                                                        <span class="small badge bg-warning-subtle text-warning py-2 px-3 rounded-pill mr-2" style="font-size: 14px">Validation Required</span>
                                                    @endif
                                                @else
                                                    <span class="small badge bg-danger-subtle text-danger py-2 px-3 rounded-pill" style="font-size: 14px">Missing</span>
                                                @endif
                                            </li>
                                            <li class="mb-2"><strong>ID:</strong> HTE-{{ str_pad($hte->id, 3, '0', STR_PAD_LEFT) }}</li>
                                            <li class="mb-2"><strong>Type:</strong> {{ ucfirst($hte->type) }}</li>

                                            @php
                                                $textClass = $availableSlots > 0 ? 'text-success' : 'text-danger';
                                            @endphp
                                            <li class="mb-2"><strong>Available Slots:</strong><span class="{{$textClass}} text-bold"> {{ $availableSlots }}</span></li>
                                            <li class="mb-2 align-middle"><strong>MOA: </strong>
                                                @if($hte->moa_path)
                                                    @if($hte->moa_is_signed === 'yes')
                                                        <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#moaPreviewModal">
                                                            <i class="ph-fill ph-eye custom-icons-i mr-1"></i>View
                                                        </button>
                                                    @else
                                                        <button class="btn btn-sm btn-outline-warning" data-toggle="modal" data-target="#moaPreviewModal">
                                                            <i class="ph-fill ph-eye custom-icons-i mr-1"></i>Review
                                                        </button>
                                                    @endif
                                                @else
                                                    <span class="text-muted fw-medium"><i>No file uploaded.</i></span>
                                                @endif
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                
                                <!-- Contact & Details Section (Unchanged) -->
                                <div class="col-md-8">
                                    <div class="border p-3 rounded my-3 mt-lg-0 bg-light">
                                        <h5 class="mb-3"><i class="ph-fill ph-identification-card details-icons-i mr-2"></i>Contact Information</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Contact Person:</strong><br>
                                                {{ $hte->user->fname }} {{ $hte->user->lname }}</p>
                                                
                                                <p><strong>Email:</strong><br>
                                                {{ $hte->user->email }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Contact Number:</strong><br>
                                                {{ $hte->user->contact ?? 'N/A' }}</p>
                                                
                                                <p><strong>Address:</strong><br>
                                                {{ $hte->address ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Skills Section -->
                                    <div class="border p-3 rounded mb-3 bg-light">
                                        <h5 class="mb-3"><i class="ph-fill ph-list-checks details-icons-i mr-2"></i>Preferred Skills</h5>
                                        @if($hte->skills->count() > 0)
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($hte->skills as $skill)
                                                    <span class="badge bg-secondary-subtle text-dark py-2 px-3 rounded-pill">{{ $skill->name }}</span>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-muted">No specific skills requested</p>
                                        @endif
                                    </div>
                                    
                                    <!-- Description -->
                                    <div class="border p-3 rounded bg-light">
                                        <h5 class="mb-3"><i class="ph-fill ph-text-align-left details-icons-i mr-2"></i>Description</h5>
                                        <p>{{ $hte->description ?? 'No description provided' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Interns Table Section (Collapsible) -->
                <div class="card shadow">
                    <div class="card-header bg-white text-dark" id="internsHeading">
                        <h3 class="card-title mb-0 d-flex align-items-center justify-content-between w-100">
                            <div class="d-flex align-items-center">
                                <button class="btn btn-link text-dark text-decoration-none p-0 mr-2 toggle-btn" type="button" data-toggle="collapse" data-target="#internsCollapse" aria-expanded="true" aria-controls="internsCollapse">
                                    <i class="ph ph-plus details-icons-i collapsed-icon"></i>
                                    <i class="ph ph-minus details-icons-i expanded-icon"></i>
                                </button>
                                <i class="ph-fill ph-user-list details-icons-i mr-2"></i>
                                Interns
                            </div>
                            <div>
                                @if($hasEndorsedForDeploy)
                                    <button type="button" class="btn btn-success fw-medium" data-toggle="modal" data-target="#deployModal">
                                        Deploy<i class="ph-fill ph-rocket-launch custom-icons-i ml-1"></i>
                                    </button>
                                @elseif($hasDeployed && $endorsementPath)
                                    <a href="{{ Storage::url($endorsementPath) }}" class="btn btn-primary fw-medium" download="{{ basename($endorsementPath) }}">
                                        <i class="ph-fill ph-download custom-icons-i mr-1"></i>Download Endorsement Letter
                                    </a>
                                @else
                                    <span class="text-muted"></span>
                                @endif
                            </div>
                        </h3>
                    </div>
                    <div id="internsCollapse" class="collapse show" aria-labelledby="internsHeading">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="thead-light border-light-gray">
                                        <tr>
                                            <th>Student ID</th>
                                            <th>Name</th>
                                            <th>Department</th>
                                            <th>Year Level</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($endorsedInterns as $endorsement)
                                            @php
                                                $intern = $endorsement->intern;
                                            @endphp
                                            <tr id="row-endorsement-{{ $endorsement->id }}">
                                                <td>{{ $intern->student_id ?? 'N/A' }}</td>
                                                <td>{{ $intern->user->lname ?? 'N/A' }}, {{ $intern->user->fname ?? 'N/A' }}</td>
                                                <td>{{ $intern->department->dept_name ?? 'N/A' }}</td>
                                                <td>{{ $intern->year_level ?? 'N/A' }}</td>
                                                <td class="align-middle text">
                                                    @php
                                                        $status = strtolower($intern->status ?? 'unknown');
                                                        $badgeClass = match($status) {
                                                            'pending requirements' => 'bg-danger-subtle text-danger',
                                                            'ready for deployment' => 'bg-warning-subtle text-warning',
                                                            'endorsed' => 'bg-primary-subtle text-primary',
                                                            'processing' => 'bg-info-subtle text-info',
                                                            'deployed' => 'bg-success-subtle text-success',
                                                            default => 'bg-secondary'
                                                        };
                                                    @endphp
                                                    <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill">{{ ucfirst($intern->status ?? 'Unknown') }}</span>
                                                </td>
                                                <td class="text-center px-2 align-middle">
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="actionDropdown{{ $intern->id ?? $loop->index }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="ph-fill ph-gear custom-icons-i"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right py-0" aria-labelledby="actionDropdown{{ $intern->id ?? $loop->index }}">
                                                            <a class="dropdown-item btn btn-outline-light text-dark" href="{{ route('coordinator.intern.show', $intern->id ?? '') }}">
                                                                <i class="ph ph-eye custom-icons-i mr-2"></i>View
                                                            </a>
                                                            
                                                            <!-- Conditional Remove: Only if status is 'endorsed' -->
                                                            @if($endorsement->status === 'endorsed')
                                                                <a class="dropdown-item btn btn-outline-light text-danger" href="#" data-toggle="modal" data-target="#removeEndorsementModal{{ $intern->id }}">
                                                                    <i class="ph ph-trash custom-icons-i mr-2"></i>Remove
                                                                </a>
                                                            @endif

                                                            <!-- Conditional Officially Deploy: Only if interns_hte status is 'deployed' AND intern status is 'processing' -->
                                                            @if($endorsement->status === 'deployed' && $intern->status === 'processing')
                                                                <form action="{{ route('coordinator.intern.officially-deploy', $intern->id) }}" method="POST" class="m-0 p-0 px-2">
                                                                    @csrf
                                                                    <button type="submit" class="dropdown-item btn btn-outline-light text-success p-2 w-100 text-left border-0 bg-transparent"> 
                                                                        <i class="ph ph-rocket-launch custom-icons-i mr-2"></i>Officially Deploy
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Conditional Remove Endorsement Modal: Only if status is 'endorsed' (unchanged) -->
                                                    @if($endorsement->status === 'endorsed')
                                                    <div class="modal fade" id="removeEndorsementModal{{ $intern->id }}" tabindex="-1" role="dialog" aria-labelledby="removeEndorsementModalLabel{{ $intern->id }}" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-light text-dark">
                                                                    <h5 class="modal-title" id="removeEndorsementModalLabel{{ $intern->id }}">
                                                                        <i class="ph-bold ph-warning details-icons-i mr-1"></i>
                                                                        Cancel Endorsement
                                                                    </h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body text-left">
                                                                    <p>Are you sure you want to remove <strong>{{ $intern->user->fname ?? '' }} {{ $intern->user->lname ?? '' }}</strong> from <strong>{{ $hte->organization_name }}</strong>?</p>
                                                                    <p class="text-warning small"><strong>Note:</strong><em>Interns cannot be removed once deployed.</em></p>
                                                                </div>
                                                                <div class="modal-footer bg-light">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                    <button type="button" class="btn btn-danger fw-medium btn-remove-endorsement" data-interns-hte-id="{{ $endorsement->id }}" data-row-id="row-endorsement-{{ $endorsement->id }}">
                                                                        Remove Endorsement
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-4">No endorsed interns found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- CSS for dynamic + and - toggle (add to your stylesheet or <style> tag) -->
<style>
    .toggle-btn .collapsed-icon {
        display: inline;
    }
    .toggle-btn .expanded-icon {
        display: none;
    }
    .toggle-btn:not(.collapsed) .collapsed-icon {
        display: none;
    }
    .toggle-btn:not(.collapsed) .expanded-icon {
        display: inline;
    }
    .toggle-btn i {
        transition: opacity 0.2s ease-in-out;
    }
</style>

    <!-- Deploy Confirmation Modal -->
    @if($hasEndorsedForDeploy)
    <div class="modal fade" id="deployModal" tabindex="-1" role="dialog" aria-labelledby="deployModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light text-dark">
                    <h5 class="modal-title" id="deployModalLabel">
                        <i class="ph-bold ph-arrow-square-out details-icons-i mr-1"></i>
                        Confirm Deployment
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('coordinator.deploy_htes', $hte->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <!-- List of Endorsed Interns -->
                        <h5 class="mb-4"><strong>Interns Endorsed</strong></h5>
                        <ul class="list-unstyled mb-0">
                            @foreach($endorsedInterns->where('status', 'endorsed') as $endorsement)
                                @php
                                    $intern = $endorsement->intern;
                                @endphp
                                <li class="mb-2 p-2 border rounded bg-light">
                                    <img src="{{ asset('storage/' . ($intern->user->pic ?? 'default-avatar.png')) }}" 
                                        alt="Profile Picture" 
                                        class="rounded-circle me-2 table-pfp" 
                                        width="30" height="30">
                                    {{ $intern->user->lname ?? 'N/A' }}, {{ $intern->user->fname ?? 'N/A' }} ({{ $intern->student_id ?? 'N/A' }})<br>
                                    <small class="text-muted">Dept: {{ $intern->department->dept_name ?? 'N/A' }} | Year: {{ $intern->year_level }}</small>
                                </li>
                            @endforeach
                        </ul>
                        <hr>
                        <!-- Deployment Details Inputs -->
                        <h5 class="mb-4"><strong>Internship Duration</strong></h5>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label"><strong>Start Date</strong> <small class="text-muted">(Official internship start)</small></label>
                                <input type="date" class="form-control" id="start_date" name="start_date" required 
                                    value="{{ now()->format('Y-m-d') }}" min="{{ now()->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="no_of_hours" class="form-label"><strong>Required Hours</strong> <small class="text-muted">(Total hours per intern)</small></label>
                                <input type="number" class="form-control" id="no_of_hours" name="no_of_hours" required min="1" step="1"> 
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label"><strong>Estimated Weeks</strong> <small class="text-muted">(Based on 40 hours/week)</small></label>
                                <input type="text" class="form-control bg-light" id="no_of_weeks" readonly value="">
                            </div>
                            <div class="col-md-6">
                                <label for="end_date" class="form-label"><strong>Estimated End Date</strong> <small class="text-muted">(Dynamic: Start + Weeks)</small></label>
                                <input type="date" class="form-control bg-light" id="end_date" name="end_date" readonly>
                            </div>
                        </div>
                        
                        <!-- Confirmation Checkbox (Required) -->
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="confirm_deployment" name="confirm_deployment" required>
                            <label class="form-check-label" for="confirm_deployment">
                                I confirm the deployment of the students listed above to <strong>{{ $hte->organization_name }}</strong> and verify that all the internship details are accurate.
                            </label>
                        </div>
                        
                        <p class="text-warning small mt-3"><strong>Note:</strong> This action will mark these interns as deployed and cannot be undone. End date is estimated and may adjust due to class cancellations.</p>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success fw-medium" id="confirmDeployBtn" disabled>
                            <i class="ph-fill ph-check custom-icons-i mr-1"></i>Confirm Deploy
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Unregister Modal (Unchanged) -->
    @if($canManage)
    <div class="modal fade" id="unregisterModal" tabindex="-1" role="dialog">
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
                    <p>Are you sure you want to unregister <strong>{{ $hte->organization_name }}</strong>? This action cannot be undone.</p>
                    <p class="text-danger small"><strong>WARNING:</strong> Any ongoing internships will be affected.</p>
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
    @endif
</div>
@endsection
<script>
    
document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.getElementById('start_date');
    const hoursInput = document.getElementById('no_of_hours');
    const weeksInput = document.getElementById('no_of_weeks');
    const endDateInput = document.getElementById('end_date');

    function calculateDeployment() {
        const hours = parseInt(hoursInput.value) || 0;
        const weeks = Math.ceil(hours / 40); // 40 hours/week (8/day * 5 days)
        weeksInput.value = weeks > 0 ? weeks + ' weeks' : '';

        if (startDateInput.value && weeks > 0) {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(startDate);
            endDate.setDate(endDate.getDate() + (weeks * 7)); // Add weeks * 7 days
            endDateInput.value = endDate.toISOString().split('T')[0];
        } else {
            endDateInput.value = '';
        }
    }



    // Event listeners
    hoursInput.addEventListener('input', calculateDeployment);
    startDateInput.addEventListener('change', calculateDeployment);

    // Initial calculation
    calculateDeployment();

    // Enable/Disable Confirm Deploy Button based on Checkbox
    const checkbox = document.getElementById('confirm_deployment');
    const submitBtn = document.getElementById('confirmDeployBtn');
    
    if (checkbox && submitBtn) {
        checkbox.addEventListener('change', function() {
            submitBtn.disabled = !this.checked;
        });
        
        // Optional: If you want to re-enable/disable on modal show (in case of multiple opens)
        const modal = document.getElementById('deployModal');
        if (modal) {
            modal.addEventListener('shown.bs.modal', function() { // Bootstrap 4/5 event
                submitBtn.disabled = !checkbox.checked;
            });
        }
    }   
});
</script>