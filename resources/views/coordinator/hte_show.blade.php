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

                <!-- All Endorsed Students Table - Grouped by Coordinator -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">All Endorsed Students</h3>
                    </div>
                    <div class="card-body table-responsive p-3 position-relative">
                        <!-- Loading Overlay -->
                        <div id="allStudentsLoadingOverlay" 
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
                            <span class="text-primary">Loading students . . .</span>
                        </div>
                        
                        <table id="allStudentsTable" class="table table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="20%">Coordinator</th>
                                    <th width="15%">Student ID</th>
                                    <th width="20%">Student Name</th>
                                    <th width="15%">Department</th>
                                    <th width="15%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($groupedByCoordinator as $coordinatorId => $endorsements)
                                    @php
                                        $firstEndorsement = $endorsements->first();
                                        $coordinator = $firstEndorsement->coordinator;
                                    @endphp
                                    
                                    @foreach($endorsements as $endorsement)
                                        @php
                                            $intern = $endorsement->intern;
                                            $statusClass = $endorsement->status === 'deployed' ? 
                                                'bg-success-subtle text-success' : 
                                                'bg-primary-subtle text-primary';
                                        @endphp
                                        <tr>
                                            <!-- Coordinator Column (only show for first row of each group) -->
                                            @if($loop->first)
                                                <td class="align-middle" rowspan="{{ $endorsements->count() }}">
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ asset('storage/' . $coordinator->user->pic) }}" 
                                                            alt="Coordinator Picture" 
                                                            class="rounded-circle me-2 table-pfp" 
                                                            width="30" height="30">
                                                        <div>
                                                            <strong>{{ $coordinator->user->fname }} {{ $coordinator->user->lname }}</strong>
                                                            <br>
                                                            <small class="text-muted">{{ $coordinator->faculty_id }}</small>
                                                            <br>
                                                            <small class="text-muted">BS{{ $coordinator->department->short_name ?? 'N/A' }}</small>
                                                            <br>
                                                            <small class="text-info">{{ $endorsements->count() }} student(s)</small>
                                                        </div>
                                                    </div>
                                                </td>
                                            @endif
                                            
                                            <!-- Student Details -->
                                            <td class="align-middle">
                                                <strong>{{ $intern->student_id }}</strong>
                                            </td>
                                            <td class="align-middle">
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset('storage/' . $intern->user->pic) }}" 
                                                        alt="Student Picture" 
                                                        class="rounded-circle me-2 table-pfp" 
                                                        width="30" height="30">
                                                    <div>
                                                        <strong>{{ $intern->user->lname }}, {{ $intern->user->fname }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $intern->year_level }}{{ strtoupper($intern->section) }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <span class="text-dark">BS{{ $intern->department->short_name ?? 'N/A' }}</span>
                                            </td>
                                            <td class="align-middle">
                                                <span class="badge {{ $statusClass }} px-3 py-2 rounded-pill text-capitalize">
                                                    {{ $endorsement->intern->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center text-muted">
                                                <i class="ph ph-users fs-1 mb-2"></i>
                                                <span>No students endorsed to this HTE yet.</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
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

