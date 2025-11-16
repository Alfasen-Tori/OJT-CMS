@extends('layouts.hte')

@section('title', 'Intern Details')

@section('content')
<div class="container-fluid">
    <!-- Intern Details Card -->
    <div class="row mb-1">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        <i class="ph ph-graduation-cap details-icons-i mr-2"></i>
                        Intern Details
                    </h3>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <!-- Intern Profile Section -->
                        <div class="col-md-4 d-flex flex-column">
                            <div class="text-center mb-4">
                                @if($intern->user->pic)
                                    <img src="{{ asset('storage/' . $intern->user->pic) }}" 
                                        class="img-thumbnail rounded-circle" 
                                        style="width: 200px; height: 200px; object-fit: cover;"
                                        alt="Intern Profile Picture">
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
                                    
                                    <div class="img-thumbnail rounded-circle mx-auto d-flex align-items-center justify-content-center text-white fw-bold" 
                                        style="width: 200px; height: 200px; font-size: 60px; background: {{ $randomGradient }};">
                                        {{ strtoupper(substr($intern->user->fname, 0, 1) . substr($intern->user->lname, 0, 1)) }}
                                    </div>
                                @endif
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
                                            @if($intern->coordinator->user->pic)
                                                <img src="{{ asset('storage/' . $intern->coordinator->user->pic) }}" 
                                                    class="img-thumbnail rounded-circle mr-3" 
                                                    style="width: 50px; height: 50px; object-fit: cover;"
                                                    alt="Coordinator Profile Picture">
                                            @else
                                                @php
                                                    // Generate a consistent random color based on user's name
                                                    $name = $intern->coordinator->user->fname . $intern->coordinator->user->lname;
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
                                                
                                                <div class="img-thumbnail rounded-circle mr-3 d-flex align-items-center justify-content-center text-white fw-bold" 
                                                    style="width: 50px; height: 50px; font-size: 16px; background: {{ $randomGradient }};">
                                                    {{ strtoupper(substr($intern->coordinator->user->fname, 0, 1) . substr($intern->coordinator->user->lname, 0, 1)) }}
                                                </div>
                                            @endif
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

    <!-- Internship Progress & Evaluation Cards -->
    @if($intern->status === 'deployed' || $intern->status === 'completed')
    <div class="row">
        <!-- 1. Internship Progress -->
        @if($currentDeployment)
        <div class="col-lg-6 col-md-6 mb-4">
            <div class="card shadow-sm h-100 d-flex flex-column">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ph ph-chart-donut custom-icons-i me-1"></i>
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

        <!-- 2. Evaluation Section -->
        <div class="col-lg-6 col-md-6 mb-4">
            <div class="card shadow-sm h-100 d-flex flex-column">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ph ph-clipboard-text custom-icons-i me-1"></i>
                        Evaluation
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
                                $letterGrade = $evaluation->grade_with_letter; // Use the accessor
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
                                    <h5 class="fw-bold text-primary mb-1">{{ number_format($evaluation->total_grade, 2) }}</h5>
                                    <small class="text-muted">Weighted Score</small>
                                </div>
                                <div class="col-6">
                                    <h5 class="fw-bold mb-1" style="color: {{ $gpaColor }}">{{ $letterGrade }}</h5>
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
                        <!-- Show evaluation button or pending message -->
                        @if($intern->status === 'completed' && !$evaluation)
                            <!-- Show Evaluate Button for completed interns without evaluation -->
                            <div class="text-center text-muted py-4 flex-grow-1 d-flex align-items-center justify-content-center">
                                <div>
                                    <i class="ph ph-clipboard-text fs-1 mb-3 text-success"></i>
                                    <h5 class="text-muted mb-3">Ready for Evaluation</h5>
                                    <p class="mb-3 small">This intern has completed their internship and is ready for evaluation.</p>
                                    @if(isset($currentDeployment) && $currentDeployment)
                                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#evaluateModal{{ $currentDeployment->id }}">
                                            <i class="ph ph-clipboard-text mr-2"></i>
                                            Evaluate Intern
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-success" disabled>
                                            <i class="ph ph-clipboard-text mr-2"></i>
                                            Evaluate Intern
                                        </button>
                                        <small class="d-block text-muted mt-2">Deployment information missing</small>
                                    @endif
                                </div>
                            </div>
                        @else
                            <!-- Show pending message for deployed interns -->
                            <div class="text-center text-muted py-4 flex-grow-1 d-flex align-items-center justify-content-center">
                                <div>
                                    <i class="ph ph-clock fs-1 mb-2"></i>
                                    <h5 class="text-muted mb-2">Evaluation Pending</h5>
                                    <p class="mb-0 small">
                                        Evaluation will be available after the intern completes their internship.
                                    </p>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Evaluation Modal -->
@if($intern->status === 'completed' && !$evaluation && $currentDeployment)
<div class="modal fade" id="evaluateModal{{ $currentDeployment->id }}" tabindex="-1" role="dialog" aria-labelledby="evaluateModalLabel{{ $currentDeployment->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light text-white py-3">
                <h5 class="modal-title mb-0" id="evaluateModalLabel{{ $currentDeployment->id }}">
                    <i class="ph ph-clipboard-text custom-icons-i mr-2"></i>
                    Evaluate Intern Performance
                </h5>
                <button type="button" class="close text-secondary" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="evaluateForm{{ $currentDeployment->id }}" class="evaluate-form">
                @csrf
                <div class="modal-body p-4">
                    <!-- Compact Header -->
                    <div class="d-flex align-items-center mb-4 p-3 bg-light rounded">
                        @if($intern->user->pic)
                            <img src="{{ asset('storage/' . $intern->user->pic) }}" 
                                alt="Profile Picture" 
                                class="rounded-circle me-3" 
                                width="60" height="60">
                        @else
                            @php
                                $name = $intern->user->fname . $intern->user->lname;
                                $colors = [
                                    'linear-gradient(135deg, #007bff, #6610f2)',
                                    'linear-gradient(135deg, #28a745, #20c997)',
                                    'linear-gradient(135deg, #dc3545, #fd7e14)',
                                    'linear-gradient(135deg, #6f42c1, #e83e8c)',
                                    'linear-gradient(135deg, #17a2b8, #6f42c1)',
                                    'linear-gradient(135deg, #fd7e14, #e83e8c)',
                                ];
                                $colorIndex = crc32($name) % count($colors);
                                $randomGradient = $colors[$colorIndex];
                            @endphp
                            
                            <div class="rounded-circle me-3 d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0" 
                                style="width: 60px; height: 60px; font-size: 18px; background: {{ $randomGradient }};">
                                {{ strtoupper(substr($intern->user->fname, 0, 1) . substr($intern->user->lname, 0, 1)) }}
                            </div>
                        @endif
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $intern->user->fname }} {{ $intern->user->lname }}</h6>
                            <p class="text-muted mb-1 small">{{ $intern->student_id }} • {{ $intern->department->dept_name ?? 'N/A' }}</p>
                            <p class="text-muted mb-0 small">
                                <i class="ph ph-calendar custom-icons-i mr-1"></i>
                                {{ \Carbon\Carbon::parse($currentDeployment->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($currentDeployment->end_date)->format('M d, Y') }}
                            </p>
                        </div>
                    </div>

                    <!-- Info Alert -->
                    <div class="alert bg-info-subtle border-info text-info mb-4 py-2">
                        <div class="d-flex align-items-center">
                            <i class="ph ph-info custom-icons-i mr-2"></i>
                            <small class="flex-grow-1">
                                <strong>Note:</strong> Rate each factor (0-100). Total weight: 95% - Maximum possible score is 95.
                            </small>
                        </div>
                    </div>

                    <div class="row g-5" style="min-height: 500px;">
                        <!-- Left Column: Compact Job Factors List -->
                        <div class="col-lg-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-light py-2">
                                    <h6 class="mb-0 fw-semibold small">
                                        <i class="ph ph-chart-line custom-icons-i mr-2"></i>
                                        Performance Factors
                                    </h6>
                                </div>
                                <div class="card-body p-3">
                                    <div class="factors-list">
                                        @foreach(App\Models\InternEvaluation::getFactorDescriptions() as $factor => $details)
                                        <div class="factor-item mb-2 pb-2 border-bottom">
                                            <div class="row align-items-center g-2">
                                                <div class="col-md-8">
                                                    <div class="mb-1">
                                                        <label for="{{ $factor }}{{ $currentDeployment->id }}" class="form-label fw-semibold mb-0 small">
                                                            {{ $details['label'] }} <span class="text-success">({{ $details['percentage'] }})</span>
                                                        </label>
                                                    </div>
                                                    <p class="text-muted small mb-1">{{ $details['description'] }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" 
                                                            class="form-control factor-input" 
                                                            id="{{ $factor }}{{ $currentDeployment->id }}" 
                                                            name="{{ $factor }}" 
                                                            min="0" 
                                                            max="100" 
                                                            step="1" 
                                                            placeholder="0-100"
                                                            required>
                                                        <span class="input-group-text">/100</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="invalid-feedback small" id="{{ $factor }}Error{{ $currentDeployment->id }}"></div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Comments & Grade Summary -->
                        <div class="col-lg-6 d-flex flex-column justify-content-between">
                            <!-- Comments Section -->
                            <div class="card border-0 shadow-sm flex-fill mb-3">
                                <div class="card-header bg-light py-2">
                                    <h6 class="mb-0 fw-semibold small">
                                        <i class="ph ph-chat-text custom-icons-i mr-2"></i>
                                        Comments & Recommendations on Student's Performance
                                    </h6>
                                </div>
                                <div class="card-body p-3 d-flex flex-column">
                                    <textarea class="form-control flex-grow-1" 
                                            id="comments{{ $currentDeployment->id }}" 
                                            name="comments" 
                                            placeholder="Overall feedback, strengths, areas for improvement, notable achievements..."></textarea>
                                    <small class="form-text text-muted mt-2">
                                        Provide specific examples and constructive feedback.
                                    </small>
                                </div>
                            </div>

                            <!-- Grade Summary -->
                            <div class="card border-primary flex-fill mb-0">
                                <div class="card-header bg-light text-white py-2">
                                    <h6 class="mb-0 fw-semibold small">
                                        <i class="ph ph-calculator custom-icons-i mr-2"></i>
                                        Grade Summary
                                    </h6>
                                </div>
                                <div class="card-body p-3 d-flex flex-column">
                                    <!-- Total Grade -->
                                    <div class="text-center mb-3 flex-fill d-flex flex-column justify-content-center">
                                        <small class="text-muted d-block mb-1">Weighted Total</small>
                                        <div id="totalGradePreview{{ $currentDeployment->id }}" class="h2 fw-bold text-primary mb-0">
                                            0.00
                                        </div>
                                        <small class="text-muted">out of 95</small>
                                        
                                        <!-- Progress Bar -->
                                        <div class="progress mt-2 rounded-pill" style="height: 6px;">
                                            <div id="gradeProgress{{ $currentDeployment->id }}" 
                                                class="progress-bar bg-success" 
                                                role="progressbar" 
                                                style="width: 0%"
                                                aria-valuenow="0" 
                                                aria-valuemin="0" 
                                                aria-valuemax="95">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Grade Breakdown -->
                                    <div class="border-top pt-3">
                                        <div class="row text-center">
                                            <div class="col-4">
                                                <small class="text-muted d-block">GPA</small>
                                                <div id="gpaPreview{{ $currentDeployment->id }}" class="h5 fw-bold text-success mb-0">
                                                    0.00
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <small class="text-muted d-block">Letter</small>
                                                <div id="letterGradePreview{{ $currentDeployment->id }}" class="h5 fw-bold text-info mb-0">
                                                    -
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <small class="text-muted d-block">Remark</small>
                                                <div id="gradeStatus{{ $currentDeployment->id }}" class="h5 fw-bold text-warning mb-0">
                                                    -
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="modal-footer bg-light py-3">
                    <button type="button" class="btn btn-outline-secondary btn-md" data-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary btn-md submit-evaluate-btn">
                        <span class="submit-text">
                            Submit Evaluation
                        </span>
                        <span class="spinner-border spinner-border-sm d-none mr-2" role="status"></span>
                        <span class="loading-text d-none">Processing...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

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
</style>

@section('scripts')
<script>
    $(document).ready(function() {
        // Evaluation form submission for all evaluate forms
        $('.evaluate-form').on('submit', function(e) {
            e.preventDefault();
            
            const form = $(this);
            const formId = form.attr('id');
            const deploymentId = formId.replace('evaluateForm', '');
            const submitBtn = form.find('.submit-evaluate-btn');
            const submitText = form.find('.submit-text');
            const spinner = form.find('.spinner-border');
            
            // Show loading state
            submitBtn.prop('disabled', true);
            submitText.addClass('d-none');
            spinner.removeClass('d-none');
            
            // Clear previous errors
            form.find('.is-invalid').removeClass('is-invalid');
            form.find('.invalid-feedback').text('');
            
            $.ajax({
                url: '{{ route("hte.interns.evaluate", "") }}/' + deploymentId,
                type: 'POST',
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        // Show success toast
                        toastr.success(response.message, 'Success');
                        
                        // Close modal
                        $('#evaluateModal' + deploymentId).modal('hide');
                        
                        // Reload page after short delay to show updated evaluation
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        toastr.error(response.message, 'Error');
                        submitBtn.prop('disabled', false);
                        submitText.removeClass('d-none');
                        spinner.addClass('d-none');
                    }
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    
                    if (xhr.status === 422 && response.errors) {
                        // Show validation errors
                        $.each(response.errors, function(field, errors) {
                            const input = form.find('[name="' + field + '"]');
                            const errorDiv = form.find('#' + field + 'Error' + deploymentId);
                            
                            input.addClass('is-invalid');
                            if (errorDiv.length) {
                                errorDiv.text(errors[0]);
                            }
                        });
                        toastr.error('Please fix the validation errors.', 'Validation Error');
                    } else {
                        toastr.error(response?.message || 'An error occurred while submitting evaluation.', 'Error');
                    }
                    
                    submitBtn.prop('disabled', false);
                    submitText.removeClass('d-none');
                    spinner.addClass('d-none');
                }
            });
        });

        // Reset form when any evaluate modal is closed
        $('[id^="evaluateModal"]').on('hidden.bs.modal', function () {
            const modalId = $(this).attr('id');
            const deploymentId = modalId.replace('evaluateModal', '');
            const form = $(this).find('form');
            
            form[0].reset();
            form.find('.is-invalid').removeClass('is-invalid');
            form.find('.invalid-feedback').text('');
            
            const submitBtn = form.find('.submit-evaluate-btn');
            const submitText = form.find('.submit-text');
            const spinner = form.find('.spinner-border');
            
            submitBtn.prop('disabled', false);
            submitText.removeClass('d-none');
            spinner.addClass('d-none');
        });
    });
</script>
@endsection
@endsection