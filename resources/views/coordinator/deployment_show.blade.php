@extends('layouts.coordinator')

@section('title', 'Deployment Management')

@section('content')
<div class="container-fluid">
    
@include('layouts.partials.scripts-main')

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
    $myCompleted = $endorsedInterns->where('status', 'completed');
    $hasDeploymentDetails = $myDeployments->isNotEmpty();
    $hasProcessingDetails = $myProcessing->isNotEmpty();
    $hasCompletedDetails = $myCompleted->isNotEmpty();
    
    // ✅ Check if ALL interns are completed
    $allInternsCompleted = $endorsedInterns->isNotEmpty() && 
                          $endorsedInterns->where('status', 'completed')->count() === $endorsedInterns->count();
    
    $deploymentDetails = $hasDeploymentDetails ? $myDeployments->first() : null;
    $processingDetails = $hasProcessingDetails ? $myProcessing->first() : null;
    $completedDetails = $hasCompletedDetails ? $myCompleted->first() : null;
    $totalMyStudents = $endorsedInterns->count();
    $deployedCount = $myDeployments->count();
    $processingCount = $myProcessing->count();
    $completedCount = $myCompleted->count();
    $endorsedCount = $endorsedInterns->where('status', 'endorsed')->count();
@endphp

@if($allInternsCompleted)
<!-- ✅ COMPLETED STATE - All interns completed -->
<div class="row mb-2">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-success-subtle rounded d-flex justify-content-center align-items-center me-2" style="width: 50px; height: 50px;">
                        <i class="fas fa-graduation-cap text-success fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Program Completed</h5>
                        <p class="text-muted mb-0">All interns have successfully completed their internship</p>
                    </div>
                </div>

                
                <div class="row g-4">
                    <div class="col-sm-2 col-6">
                        <div class="border-start border-3 border-success ps-3">
                            <small class="text-muted fw-medium">START DATE</small>
                            <div class="fw-bold text-dark fs-6">{{ \Carbon\Carbon::parse($completedDetails->start_date)->format('M j, Y') }}</div>
                        </div>
                    </div>
                    <div class="col-sm-2 col-6">
                        <div class="border-start border-3 border-warning ps-3">
                            <small class="text-muted fw-medium">END DATE</small>
                            <div class="fw-bold text-dark fs-6">{{ \Carbon\Carbon::parse($completedDetails->end_date)->format('M j, Y') }}</div>
                        </div>
                    </div>
                    <div class="col-sm-2 col-6">
                        <div class="border-start border-3 border-info ps-3">
                            <small class="text-muted fw-medium">HOURS</small>
                            <div class="fw-bold text-dark fs-6">{{ $completedDetails->no_of_hours }}</div>
                        </div>
                    </div>
                    <div class="col-sm-2 col-6">
                        <div class="border-start border-3 border-primary ps-3">
                            <small class="text-muted fw-medium">STUDENTS</small>
                            <div class="fw-bold text-dark fs-6">{{ $completedCount }}/{{ $totalMyStudents }}</div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-12">
                        <div class="bg-success-subtle rounded-pill px-3 py-2 text-center">
                            <span class="fw-bold text-success">COMPLETED</span>
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

@elseif($hasDeploymentDetails)
<!-- Deployed State -->
<div class="row mb-2">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-success-subtle rounded d-flex justify-content-center align-items-center me-2" style="width: 50px; height: 50px;">
                        <i class="ph ph-calendar-check text-success fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Deployment Active</h5>
                        <p class="text-muted mb-0">Internship program in progress</p>
                    </div>
                </div>

                
                <div class="row g-4">
                    <div class="col-sm-2 col-6">
                        <div class="border-start border-3 border-success ps-3">
                            <small class="text-muted fw-medium">START DATE</small>
                            <div class="fw-bold text-dark fs-6">{{ \Carbon\Carbon::parse($deploymentDetails->start_date)->format('M j, Y') }}</div>
                        </div>
                    </div>
                    <div class="col-sm-2 col-6">
                        <div class="border-start border-3 border-warning ps-3">
                            <small class="text-muted fw-medium">END DATE</small>
                            <div class="fw-bold text-dark fs-6">{{ \Carbon\Carbon::parse($deploymentDetails->end_date)->format('M j, Y') }}</div>
                        </div>
                    </div>
                    <div class="col-sm-2 col-6">
                        <div class="border-start border-3 border-info ps-3">
                            <small class="text-muted fw-medium">HOURS</small>
                            <div class="fw-bold text-dark fs-6">{{ $deploymentDetails->no_of_hours }}</div>
                        </div>
                    </div>
                    <div class="col-sm-2 col-6">
                        <div class="border-start border-3 border-primary ps-3">
                            <small class="text-muted fw-medium">STUDENTS</small>
                            <div class="fw-bold text-dark fs-6">{{ $deployedCount }}/{{ $totalMyStudents }}</div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-12">
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
                    <div class="bg-info-subtle rounded d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                        <i class="ph ph-hourglass-medium text-info fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Processing Deployment</h5>
                        <p class="text-muted mb-0">Internship details being finalized</p>
                    </div>
                </div>

                
                <div class="row g-4">
                    <div class="col-sm-2 col-6">
                        <div class="border-start border-3 border-success ps-3">
                            <small class="text-muted fw-medium">START DATE</small>
                            <div class="fw-bold text-dark fs-6">{{ \Carbon\Carbon::parse($processingDetails->start_date)->format('M j, Y') }}</div>
                        </div>
                    </div>
                    <div class="col-sm-2 col-6">
                        <div class="border-start border-3 border-warning ps-3">
                            <small class="text-muted fw-medium">ESTIMATED END DATE</small>
                            <div class="fw-bold text-dark fs-6">{{ \Carbon\Carbon::parse($processingDetails->end_date)->format('M j, Y') }}</div>
                        </div>
                    </div>
                    <div class="col-sm-2 col-6">
                        <div class="border-start border-3 border-info ps-3">
                            <small class="text-muted fw-medium">HOURS</small>
                            <div class="fw-bold text-dark fs-6">{{ $processingDetails->no_of_hours }}</div>
                        </div>
                    </div>
                    <div class="col-sm-2 col-6">
                        <div class="border-start border-3 border-primary ps-3">
                            <small class="text-muted fw-medium">STUDENTS</small>
                            <div class="fw-bold text-dark fs-6">{{ $processingCount }}/{{ $totalMyStudents }}</div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-6">
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
                    <div class="bg-primary-subtle rounded d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
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
        <div class="card-header" id="internsHeading">
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
                        <button type="button" class="btn btn-danger fw-medium" data-toggle="modal" data-target="#cancelEndorsementModal">
                            Cancel Endorsement<i class="ph-bold ph-x-circle custom-icons-i ml-1"></i>
                        </button>
                        <button type="button" class="btn btn-info fw-medium" data-toggle="modal" data-target="#deployModal">
                            Initiate Deployment<i class="ph-fill ph-rocket-launch custom-icons-i ml-1"></i>
                        </button>
                    @elseif($isProcessing)
                        <button class="btn btn-success fw-medium" data-toggle="modal" data-target="#officiallyDeployModal">
                            <i class="ph-fill ph-seal-check custom-icons-i mr-1" ></i>Officially Deploy
                        </button>
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
                                    <td class="align-middle">
                                        @if($intern->user->pic)
                                            <img src="{{ asset('storage/' . $intern->user->pic) }}" 
                                                alt="Profile Picture" 
                                                class="rounded-circle me-2 table-pfp" 
                                                width="30" height="30">
                                        @else
                                            @php
                                                // Generate a consistent random color based on user's name
                                                $name = $intern->user->fname . $intern->user->lname;
                                                $colors = [
                                                    'linear-gradient(135deg, #007bff, #6610f2)', // Blue to Purple
                                                    'linear-gradient(135deg, #28a745, #20c997)', // Green to Teal
                                                    'linear-gradient(135deg, #dc3545, #fd7e14)', // Red to Orange
                                                    'linear-gradient(135deg, #6f42c1, #e83e8c)', // Purple to Pink
                                                    'linear-gradient(135deg, #17a2b8, #6f42c1)', // Teal to Purple
                                                    'linear-gradient(135deg, #fd7e14, #e83e8c)', // Orange to Pink
                                                ];
                                                
                                                // Generate a consistent index based on the user's name
                                                $colorIndex = crc32($name) % count($colors);
                                                $randomGradient = $colors[$colorIndex];
                                            @endphp
                                            
                                            <div class="rounded-circle me-2 d-inline-flex align-items-center justify-content-center text-white fw-bold" 
                                                style="width: 30px; height: 30px; font-size: 11px; background: {{ $randomGradient }};">
                                                {{ strtoupper(substr($intern->user->fname, 0, 1) . substr($intern->user->lname, 0, 1)) }}
                                            </div>
                                        @endif
                                        {{ $intern->user->lname }}, {{ $intern->user->fname }} 
                                    </td>   
                                    <td>{{ $intern->department->dept_name ?? 'N/A' }}</td>
                                    <td>{{ $intern->year_level ?? 'N/A' }}</td>
                                    <td class="align-middle text">
                                        @php
                                            $status = strtolower($endorsement->status ?? 'unknown');
                                            $badgeClass = match($status) {
                                                'endorsed' => 'bg-primary-subtle text-primary',
                                                'processing' => 'bg-info-subtle text-info',
                                                'deployed' => 'bg-success-subtle text-success',
                                                'completed' => 'bg-secondary-subtle text-secondary',
                                                default => 'bg-secondary'
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill">{{ ucfirst($endorsement->status ?? 'Unknown') }}</span>
                                    </td>
                                    <td class="text-center px-2 align-middle">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-primary rounded-pill dropdown-toggle" type="button" id="actionDropdown{{ $intern->id ?? $loop->index }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="ph-fill ph-gear custom-icons-i"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right py-0" aria-labelledby="actionDropdown{{ $intern->id ?? $loop->index }}">
                                                <a class="dropdown-item btn btn-outline-light text-dark" href="{{ route('coordinator.intern.show', $intern->id ?? '') }}">
                                                    <i class="ph ph-eye custom-icons-i mr-2"></i>View
                                                </a>
                                                
                                                <!-- Conditional Remove: Only if status is 'endorsed' -->
                                                @if($endorsement->status === 'endorsed')
                                                    <a class="dropdown-item border-lightgray border-top rounded-0 btn btn-outline-light text-danger" href="#" data-toggle="modal" data-target="#removeEndorsementModal{{ $intern->id }}">
                                                        <i class="ph ph-trash custom-icons-i mr-2"></i>Remove
                                                    </a>
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
                                <div class="d-flex align-items-center">
                                    @if($intern->user->pic)
                                        <img src="{{ asset('storage/' . $intern->user->pic) }}" 
                                            alt="Profile Picture" 
                                            class="rounded-circle me-3" 
                                            width="40" height="40"
                                            style="object-fit: cover;">
                                    @else
                                        @php
                                            // Generate a consistent random color based on user's name
                                            $name = $intern->user->fname . $intern->user->lname;
                                            $colors = [
                                                'linear-gradient(135deg, #007bff, #6610f2)', // Blue to Purple
                                                'linear-gradient(135deg, #28a745, #20c997)', // Green to Teal
                                                'linear-gradient(135deg, #dc3545, #fd7e14)', // Red to Orange
                                                'linear-gradient(135deg, #6f42c1, #e83e8c)', // Purple to Pink
                                                'linear-gradient(135deg, #17a2b8, #6f42c1)', // Teal to Purple
                                                'linear-gradient(135deg, #fd7e14, #e83e8c)', // Orange to Pink
                                            ];
                                            
                                            // Generate a consistent index based on the user's name
                                            $colorIndex = crc32($name) % count($colors);
                                            $randomGradient = $colors[$colorIndex];
                                        @endphp
                                        
                                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0" 
                                            style="width: 40px; height: 40px; font-size: 14px; background: {{ $randomGradient }};">
                                            {{ strtoupper(substr($intern->user->fname, 0, 1) . substr($intern->user->lname, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <div class="fw-bold mb-1">
                                            {{ $intern->user->lname }}, {{ $intern->user->fname }} 
                                            <span class="text-muted">({{ $intern->student_id ?? 'N/A' }})</span>
                                        </div>
                                        <small class="text-muted">
                                            {{ $intern->department->short_name ?? 'N/A' }}-{{ $intern->year_level }}{{ strtoupper($intern->section) }}
                                        </small>
                                    </div>
                                </div>
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
                        
                        <p class="text-warning small mt-3"><strong>Note:</strong> This action will initiaite the internship deployment process. Cancellelation of endorsements will not be available beyond this point.</p>
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

    <!-- Cancel Endorsement Modal -->
    <div class="modal fade" id="cancelEndorsementModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title">
                        <i class="ph-bold ph-warning details-icons-i mr-1"></i>
                        Cancel Endorsement
                    </h5>                
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to cancel the endorsement to <strong>{{ $hte->organization_name }}</strong>? This action will:</p>
                    <ul>
                        <li>Remove all student endorsements to this HTE</li>
                        <li>Revert <strong>{{ $endorsedInterns->where('status', 'endorsed')->count() }}</strong> student(s) status back to "Ready for Deployment"</li>
                    </ul>
                    <p class="text-danger"><strong>This action cannot be undone.</strong></p>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <!-- Use HTE ID instead of endorsement ID -->
                    <form action="{{ route('coordinator.deployment.cancel-endorsement', $hte->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            Cancel All Endorsements ({{ $endorsedInterns->where('status', 'endorsed')->count() }} students)
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Officially Deploy Modal -->
    <div class="modal fade" id="officiallyDeployModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title">
                        <i class="ph-bold ph-seal-check details-icons-i mr-1"></i>
                        Officially Deploy
                    </h5>                
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Officially deploy the students listed below to <strong>{{ $hte->organization_name }}</strong>. Confirm that you have already submitted the following requirements:</p>
                    <ul>
                        <li>Signed Student Internship Contract</li>
                        <li>Signed Memorandum of Agreement</li>
                        <li>List of Student Interns</li>
                    </ul>
                    <p class="text-danger small"><strong>Important:</strong> Submit the neccesary requirements first before deploying.</p>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form action="{{ route('coordinator.deployment.officially-deploy', $endorsement->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-success">Confirm</button>
                    </form>
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

        <!-- Coordinator Deployments Management Table -->
    <script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#deploymentsTable').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "language": {
            "emptyTable": "No deployments found.",
            "search": "_INPUT_",
            "searchPlaceholder": "Search...",
            "lengthMenu": "Show _MENU_ entries",
            "info": "Showing _START_ to _END_ of _TOTAL_ deployments",
            "paginate": {
            "previous": "«",
            "next": "»"
            }
        },
        "columnDefs": [
            { "orderable": false, "targets": [5] }
        ],
        "initComplete": function() {
            // Hide loading overlay when table is ready
            $('#tableLoadingOverlay').fadeOut();
        }
        });
    });
    </script>

    <!-- Coordinator: HTE/show - Remove Endorsement -->
    <script>
        $(document).ready(function() {
            // Handle Remove Endorsement button click inside modal
            $('.btn-remove-endorsement').on('click', function() {
                const internsHteId = $(this).data('interns-hte-id');
                const rowId = $(this).data('row-id');
                const $modal = $(this).closest('.modal');

                $(this).prop('disabled', true).text('Removing...');

                $.ajax({
                    url: `/coordinator/remove-endorsement/${internsHteId}`,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#' + rowId).fadeOut(400, function() {
                                $(this).remove();
                            });
                            toastr.success(response.message || 'Endorsement removed successfully.');
                        } else {
                            toastr.error(response.message || 'Failed to remove endorsement.');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Failed to remove endorsement. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        toastr.error(errorMsg);
                    },
                    complete: function() {
                        $('.btn-remove-endorsement').prop('disabled', false).text('Remove Endorsement');
                        $modal.modal('hide');
                    }
                });
            });
        });
    </script>


@endsection