@extends('layouts.coordinator')

@section('title', 'Intern Details')

@section('content')
<div class="container-fluid">
    <!-- Intern Details Card -->
    <div class="row mb-1">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white text-dark">
                    <h3 class="card-title mb-0 d-flex justify-content-between align-items-center w-100">
                        <div>
                            <i class="ph ph-graduation-cap details-icons-i mr-2"></i>
                            Intern Details
                        </div>
                        <div class="title-right">
                            <a href="{{ route('coordinator.edit_i', $intern->id) }}" class="btn btn-outline-light border-0 rounded-4 text-muted"><i class="ph ph-wrench details-icons-i p-0"></i></a>
                            <a class="btn btn-outline-light border-0 rounded-4 text-muted" data-toggle="modal" data-target="#removeModal"><i class="ph ph-trash details-icons-i p-0"></i></a>
                        </div>
                    </h3>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <!-- Intern Profile Section -->
                        <div class="col-md-4 d-flex flex-column">
                            <div class="text-center mb-4">
                                <img src="{{ asset('storage/' . $intern->user->pic) }}" 
                                     class="img-thumbnail rounded-circle" 
                                     style="width: 200px; height: 200px; object-fit: cover;"
                                     alt="Intern Profile Picture">
                            </div>
                            
                            <div class="border p-3 rounded bg-light flex-grow-1 mt-0">
                                <h5 class="mb-3"><i class="ph-fill ph-info details-icons-i mr-2"></i>Academic Information</h5>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <strong>Status:</strong> 
                                        <span class="badge py-2 px-3 rounded-pill status-badge bg-{{ 
                                            $intern->status == 'endorsed' ? 'primary-subtle text-primary' : 
                                            ($intern->status == 'ready for deployment' ? 'warning-subtle text-warning' : 
                                            ($intern->status == 'pending requirements' ? 'danger-subtle text-danger' :
                                            ($intern->status == 'processing' ? 'info-subtle text-info' :
                                            ($intern->status == 'deployed' ? 'success-subtle text-success' : 
                                            ($intern->status == 'completed' ? 'success-subtle text-success' : 'secondary')))))
                                        }}">
                                            {{ ucfirst($intern->status) }}
                                        </span>
                                    </li>
                                    <li class="mb-2"><strong>Student ID:</strong> {{ $intern->student_id }}</li>
                                    <li class="mb-2"><strong>Program:</strong> {{ $intern->department->dept_name }}</li>
                                    <li class="mb-2"><strong>Year Level:</strong> {{ $intern->year_level }}</li>
                                    <li class="mb-2"><strong>Section:</strong> {{ strtoupper($intern->section) }}</li>
                                    <li class="mb-2"><strong>Academic Year:</strong> {{ $intern->academic_year }}</li>
                                    <li class="mb-2"><strong>Semester:</strong> {{ $intern->semester }}</li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Contact & Details Section -->
                        <div class="col-md-8">
                            <div class="border p-3 rounded my-3 mt-lg-0 bg-light">
                                <h5 class="mb-3"><i class="ph-fill ph-person details-icons-i mr-2"></i>Personal Information</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Full Name:</strong><br>
                                        {{ $intern->user->fname }} {{ $intern->user->lname }}</p>
                                        
                                        <p><strong>Birthdate:</strong><br>
                                        {{ $intern->birthdate ? \Carbon\Carbon::parse($intern->birthdate)->format('F j, Y') : 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Age:</strong><br>
                                        {{ $intern->birthdate ? \Carbon\Carbon::parse($intern->birthdate)->age . ' years old' : 'N/A' }}</p>

                                        <p><strong>Sex:</strong><br>
                                        <span style="text-transform: capitalize;">{{ $intern->user->sex }}</span></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="border p-3 rounded my-3 bg-light">
                                <h5 class="mb-3"><i class="ph-fill ph-identification-card details-icons-i mr-2"></i>Contact Information</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Email:</strong><br>
                                        {{ $intern->user->email }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Contact Number:</strong><br>
                                        {{ $intern->user->contact ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Skills Section -->
                            <div class="border p-3 rounded mb-3 bg-light">
                                <h5 class="mb-3"><i class="ph-fill ph-certificate details-icons-i mr-2"></i>Skills</h5>
                                @if($intern->skills->count() > 0)
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($intern->skills as $skill)
                                            <span class="badge bg-primary-subtle text-dark py-2 px-3 rounded-pill">{{ $skill->name }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted">No skills recorded</p>
                                @endif
                            </div>
                            
                            <!-- Coordinator Info -->
                            <div class="border p-3 rounded bg-light">
                                <h5 class="mb-3"><i class="ph-fill ph-chalkboard-teacher details-icons-i mr-2"></i>Coordinator</h5>
                                @if($intern->coordinator)
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <img src="{{ asset('storage/' . $intern->coordinator->user->pic) }}" 
                                                 class="img-thumbnail rounded-circle mr-3" 
                                                 style="width: 50px; height: 50px; object-fit: cover;"
                                                 alt="Coordinator Profile Picture">
                                        </div>
                                        <div class="flex-grow-1 gap-1">
                                            <h6 class="mb-0">{{ $intern->coordinator->user->fname }} {{ $intern->coordinator->user->lname }}</h6>
                                            <p class="mb-0 text-muted small m-0">College of {{ $intern->coordinator->department->college->name }} • {{ $intern->coordinator->department->dept_name }} Department</p>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-muted">No coordinator assigned</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Internship Progress, Reports & Evaluation Cards -->
    @if($intern->status === 'deployed' || $intern->status === 'completed')
    <div class="row">
        <!-- 1. Internship Progress -->
        @if($currentDeployment)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow-sm h-100 d-flex flex-column">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="ph-fill ph-chart-donut custom-icons-i me-1 text-primary"></i>
                        Progress
                    </h5>
                </div>
                <div class="card-body d-flex flex-column justify-content-center">
                    {{-- CSS Knob Chart --}}
                    <div class="flex-grow-1 mb-3">
                        <div class="d-flex justify-content-center align-items-center h-100">
                            <div class="knob-container">
                                <div class="knob-display">
                                    @php
                                        $percentage = $progress['percentage'] ?? 0;
                                        $angle = min(360, $percentage * 3.6);
                                        $color = $percentage >= 100 ? '#28a745' : '#007bff';
                                    @endphp
                                    <div class="knob-bg" id="progressKnob" 
                                        style="background: conic-gradient({{ $color }} {{ $angle }}deg, #e9ecef 0);">
                                        <div class="knob-center">
                                            <h3 class="mb-0 fw-bold" id="progressPercent">{{ $percentage }}%</h3>
                                            <small class="text-muted" id="progressLabel">
                                                {{ $percentage >= 100 ? 'Completed!' : 'Complete' }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Hours Display --}}
                    <div class="mt-auto pt-3">
                        <div class="row text-center">
                            <div class="col-6 border-end">
                                <h4 class="fw-bold text-primary mb-1" id="totalRendered">{{ $progress['total_rendered'] ?? 0 }}</h4>
                                <small class="text-muted">Hours Rendered</small>
                            </div>
                            <div class="col-6">
                                <h4 class="fw-bold text-success mb-1" id="requiredHours">{{ $progress['required_hours'] ?? 0 }}</h4>
                                <small class="text-muted">Total Required</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- 2. Weekly Reports Section -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow-sm h-100 d-flex flex-column">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="ph-fill ph-file-text custom-icons-i me-1 text-info"></i>
                        Weekly Reports
                    </h5>
                </div>
                <div class="card-body p-0 d-flex flex-column">
                    @if(isset($weeklyReports) && $weeklyReports->count() > 0)
                        <div class="list-group list-group-flush flex-grow-1" style="max-height: 500px; overflow-y: auto;">
                            @foreach($weeklyReports as $report)
                            <div class="list-group-item d-flex justify-content-between align-items-center px-3 py-2">
                                <div>
                                    <h6 class="mb-1">Week {{ $report->week_no }}</h6>
                                    <small class="text-muted">
                                        Submitted: {{ $report->submitted_at ? \Carbon\Carbon::parse($report->submitted_at)->format('M j, Y') : 'N/A' }}
                                    </small>
                                </div>
                                <div>
                                    @if($report->report_path)
                                    <a href="{{ asset('storage/' . $report->report_path) }}" 
                                       target="_blank" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="ph ph-arrow-circle-up-right custom-icons-i mr-1"></i>Open
                                    </a>
                                    @else
                                    <span class="badge bg-danger-subtle text-danger">Missing</span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-4 flex-grow-1 d-flex align-items-center justify-content-center">
                            <div>
                                <i class="ph ph-file-text fs-1 mb-2"></i>
                                <p class="mb-0">No weekly reports submitted yet.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- 3. Evaluation Section - ALWAYS SHOW -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow-sm h-100 d-flex flex-column">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="ph-fill ph-clipboard-text custom-icons-i me-1 text-success"></i>
                        HTE Evaluation
                    </h5>
                </div>
                <div class="card-body d-flex flex-column justify-content-center">
                    @if($evaluation)
                        <!-- Show evaluation when it exists -->
                        <div class="text-center mb-4">
                            @php
                                $gpa = $evaluation->calculateGPA();
                                $gpaColor = $evaluation->getGPAColor();
                                $gpaDescription = $evaluation->getGPADescription();
                            @endphp
                            <div class="d-flex justify-content-center align-items-center mb-3">
                                <div style="width: 100px; height: 100px; background: {{ $gpaColor }}; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <h3 class="text-white mb-0 fw-bold">{{ number_format($gpa, 2) }}</h3>
                                </div>
                            </div>
                            <h4 class="fw-bold mb-1" style="color: {{ $gpaColor }}">GPA: {{ number_format($gpa, 2) }}</h4>
                            <p class="text-muted mb-0">{{ $gpaDescription }}</p>
                        </div>
                        
                        <div class="border-top pt-3 mt-auto">
                            <div class="row text-center mb-3">
                                <div class="col-6">
                                    <h5 class="fw-bold text-primary mb-1">{{ $evaluation->grade }}/100</h5>
                                    <small class="text-muted">Rating</small>
                                </div>
                                <div class="col-6">
                                    <h5 class="fw-bold mb-1" style="color: {{ $gpaColor }}">{{ $evaluation->grade_with_letter }}</h5>
                                    <small class="text-muted">Letter Grade</small>
                                </div>
                            </div>
                            
                            @if($evaluation->comments)
                            <div class="mt-3">
                                <h6 class="text-muted mb-2">Comments:</h6>
                                <div class="border rounded p-3 bg-light">
                                    <p class="mb-0 small">{{ $evaluation->comments }}</p>
                                </div>
                            </div>
                            @endif
                            
                            <div class="mt-3 text-center">
                                <small class="text-muted">Evaluated on: {{ \Carbon\Carbon::parse($evaluation->evaluation_date)->format('F j, Y') }}</small>
                            </div>
                        </div>
                    @else
                        <!-- Show "No Evaluation" state -->
                        <div class="text-center text-muted py-4 flex-grow-1 d-flex align-items-center justify-content-center">
                            <div>
                                <i class="ph ph-clock fs-1 mb-2"></i>
                                <h5 class="text-muted mb-2">Pending</h5>
                                <p class="mb-0 small">
                                    @if($intern->status === 'deployed')
                                        Evaluation will be available after the intern completes their internship.
                                    @else
                                        No evaluation has been submitted by the HTE yet.
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Remove Modal -->
<div class="modal fade" id="removeModal" tabindex="-1" role="dialog">
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
                <p>Are you sure you want to unregister <strong>{{ $intern->user->fname }} {{ $intern->user->lname }}</strong>? This action cannot be undone.</p>
                <p class="text-danger small"><strong>WARNING:</strong> All associated internship records will also be removed.</p>
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

<style>
.knob-container {
    position: relative;
    width: 250px;
    height: 250px;
}

.knob-display {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    position: relative;
}

.knob-bg {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.knob-center {
    width: 190px;
    height: 190px;
    background: white;
    border-radius: 50%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

/* Custom scrollbar for weekly reports */
.list-group-flush::-webkit-scrollbar {
    width: 6px;
}

.list-group-flush::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.list-group-flush::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.list-group-flush::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>
@endsection