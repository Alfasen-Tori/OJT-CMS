@extends('layouts.coordinator')

@section('title', 'Deployment Management')

@section('content')
<div class="container-fluid">
<!-- Header -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold text-dark mb-1">{{ $hte->organization_name }}</h4>
                <p class="text-muted mb-0">HTE-{{ str_pad($hte->id, 3, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div>
                <a href="{{ route('coordinator.hte.show', $hte->id) }}" class="btn btn-outline-primary">
                    <i class="ph ph-arrow-left mr-2"></i>Back to HTE
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Deployment Details Header -->
@php
    $currentCoordinatorId = auth()->user()->coordinator->id;
    $myDeployments = $endorsedInterns->where('status', 'deployed');
    $myProcessing = $endorsedInterns->where('status', 'processing');
    $hasDeploymentDetails = $myDeployments->isNotEmpty();
    $hasProcessingDetails = $myProcessing->isNotEmpty();
    
    $deploymentDetails = $hasDeploymentDetails ? $myDeployments->first() : null;
    $processingDetails = $hasProcessingDetails ? $myProcessing->first() : null;
    $totalMyStudents = $endorsedInterns->count();
    $deployedCount = $myDeployments->count();
    $processingCount = $myProcessing->count();
    $endorsedCount = $endorsedInterns->where('status', 'endorsed')->count();
@endphp

@if($hasDeploymentDetails)
<!-- Deployed State -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-success-subtle rounded p-2 me-3">
                        <i class="ph ph-calendar-check text-success fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Deployment Active</h5>
                        <p class="text-muted mb-0">Internship program in progress</p>
                    </div>
                </div>
                
                <div class="row g-4">
                    <div class="col-md-3">
                        <div class="border-start border-3 border-success ps-3">
                            <small class="text-muted fw-medium">START DATE</small>
                            <div class="fw-bold text-dark fs-6">{{ \Carbon\Carbon::parse($deploymentDetails->start_date)->format('M j, Y') }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border-start border-3 border-warning ps-3">
                            <small class="text-muted fw-medium">END DATE</small>
                            <div class="fw-bold text-dark fs-6">{{ \Carbon\Carbon::parse($deploymentDetails->end_date)->format('M j, Y') }}</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="border-start border-3 border-info ps-3">
                            <small class="text-muted fw-medium">HOURS</small>
                            <div class="fw-bold text-dark fs-6">{{ $deploymentDetails->no_of_hours }}</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="border-start border-3 border-primary ps-3">
                            <small class="text-muted fw-medium">STUDENTS</small>
                            <div class="fw-bold text-dark fs-6">{{ $deployedCount }}/{{ $totalMyStudents }}</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="bg-success-subtle rounded-pill px-3 py-2 text-center">
                            <span class="fw-bold text-success">DEPLOYED</span>
                        </div>
                    </div>
                </div>
                
                @if($hte->address)
                <div class="mt-3 pt-3 border-top">
                    <div class="d-flex align-items-center">
                        <i class="ph ph-map-pin text-muted me-2"></i>
                        <span class="text-dark">{{ $hte->address }}</span>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@elseif($hasProcessingDetails)
<!-- Processing State -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-info-subtle rounded p-2 me-3">
                        <i class="ph ph-hourglass-medium text-info fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Processing Deployment</h5>
                        <p class="text-muted mb-0">Internship details being finalized</p>
                    </div>
                </div>
                
                <div class="row g-4">
                    <div class="col-md-3">
                        <div class="border-start border-3 border-success ps-3">
                            <small class="text-muted fw-medium">START DATE</small>
                            <div class="fw-bold text-dark fs-6">{{ \Carbon\Carbon::parse($processingDetails->start_date)->format('M j, Y') }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border-start border-3 border-warning ps-3">
                            <small class="text-muted fw-medium">ESTIMATED COMPLETION DATE</small>
                            <div class="fw-bold text-dark fs-6">{{ \Carbon\Carbon::parse($processingDetails->end_date)->format('M j, Y') }}</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="border-start border-3 border-info ps-3">
                            <small class="text-muted fw-medium">HOURS</small>
                            <div class="fw-bold text-dark fs-6">{{ $processingDetails->no_of_hours }}</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="border-start border-3 border-primary ps-3">
                            <small class="text-muted fw-medium">STUDENTS</small>
                            <div class="fw-bold text-dark fs-6">{{ $processingCount }}/{{ $totalMyStudents }}</div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="bg-info-subtle rounded-pill px-3 py-2 text-center">
                            <span class="fw-bold text-info">PROCESSING</span>
                        </div>
                    </div>
                </div>
                
                @if($hte->address)
                <div class="mt-3 pt-3 border-top">
                    <div class="d-flex align-items-center">
                        <i class="ph ph-map-pin text-muted me-2"></i>
                        <span class="text-dark">{{ $hte->address }}</span>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@elseif($endorsedCount > 0)
<!-- Endorsed State -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary-subtle rounded p-2 me-3">
                        <i class="ph ph-clock-countdown text-primary fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Ready for Deployment</h5>
                        <p class="text-muted mb-0">Interns endorsed and awaiting deployment</p>
                    </div>
                </div>
                
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="border-start border-3 border-primary ps-3">
                            <small class="text-muted fw-medium">ENDORSED STUDENTS</small>
                            <div class="fw-bold text-dark fs-6">{{ $endorsedCount }} intern(s)</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border-start border-3 border-warning ps-3">
                            <small class="text-muted fw-medium">ENDORSED AT</small>
                            <div class="fw-bold text-dark fs-6">{{ $endorsedInterns->first()->endorsed_at ? \Carbon\Carbon::parse($endorsedInterns->first()->endorsed_at)->format('M j, Y') : 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-primary-subtle rounded-pill px-3 py-2 text-center">
                            <span class="fw-bold text-primary">ENDORSED</span>
                        </div>
                    </div>
                </div>
                
                @if($hte->address)
                <div class="mt-3 pt-3 border-top">
                    <div class="d-flex align-items-center">
                        <i class="ph ph-map-pin text-muted me-2"></i>
                        <span class="text-dark">{{ $hte->address }}</span>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@else
<!-- No Deployment State -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 text-center">
                <div class="bg-light rounded-circle d-inline-flex p-3 mb-3">
                    <i class="ph ph-info text-muted fs-2"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">No Active Deployment</h5>
                <p class="text-muted mb-3">You haven't endorsed any interns to this HTE yet</p>
                
                @if($hte->address)
                <div class="border-top pt-3 mt-3">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="ph ph-map-pin text-muted me-2"></i>
                        <span class="text-dark">{{ $hte->address }}</span>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

    <!-- Interns Table Section -->
    <div class="card shadow">
        <div class="card-header bg-white text-dark" id="internsHeading">
            <h3 class="card-title mb-0 d-flex align-items-center justify-content-between w-100">
                <div class="d-flex align-items-center">
                    <button class="btn btn-link text-dark text-decoration-none p-0 mr-2 toggle-btn" type="button" data-toggle="collapse" data-target="#internsCollapse" aria-expanded="true" aria-controls="internsCollapse">
                        <i class="ph ph-plus details-icons-i collapsed-icon"></i>
                        <i class="ph ph-minus details-icons-i expanded-icon"></i>
                    </button>
                    <i class="ph-fill ph-user-list details-icons-i mr-2"></i>
                    My Interns
                </div>
                <div>
                    @if($hasEndorsedForDeploy)
                        <button type="button" class="btn btn-info fw-medium" data-toggle="modal" data-target="#deployModal">
                            Initiate Deployment<i class="ph-fill ph-rocket-launch custom-icons-i ml-1"></i>
                        </button>
                    @elseif($isProcessing)
                        <a href="" class="btn btn-success fw-medium">
                            <i class="ph-fill ph-seal-check custom-icons-i mr-1"></i>Officially Deploy
                        </a>
                    @elseif($hasDeployed && $endorsementPath)
                        <a href="{{ Storage::url($endorsementPath) }}" class="btn btn-primary fw-medium" download="{{ basename($endorsementPath) }}">
                            <i class="ph-fill ph-download custom-icons-i mr-1"></i>Download Endorsement Letter
                        </a>
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
                                                @if($endorsement->status === 'processing' && $intern->status === 'processing')
                                                    <form action="{{ route('coordinator.intern.officially-deploy', $intern->id) }}" method="POST" class="m-0 p-0">
                                                        @csrf
                                                        <button class="dropdown-item btn btn-outline-light text-success" type="submit">
                                                            <i class="ph ph-rocket-launch custom-icons-i mr-2"></i>Officially Deploy
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Conditional Remove Endorsement Modal -->
                                        @if($endorsement->status === 'endorsed')
                                        <div class="modal fade" id="removeEndorsementModal{{ $intern->id }}" tabindex="-1" role="dialog" aria-labelledby="removeEndorsementModalLabel{{ $intern->id }}" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-light text-dark">
                                                        <h5 class="modal-title" id="removeEndorsementModalLabel{{ $intern->id }}">
                                                            <i class="ph-bold ph-warning details-icons-i mr-1 mt-2"></i>
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

    <!-- Deploy Confirmation Modal -->
    @if($hasEndorsedForDeploy)
    <div class="modal fade" id="deployModal" tabindex="-1" role="dialog" aria-labelledby="deployModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light text-dark">
                    <h5 class="modal-title" id="deployModalLabel">
                        <i class="ph-bold ph-question details-icons-i mr-1"></i>
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
                            Deploy
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

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


</div>
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
@endsection

