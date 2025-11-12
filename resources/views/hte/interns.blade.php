{{-- resources/views/hte/interns.blade.php --}}
@extends('layouts.hte')

@section('title', 'HTE | Interns')

@section('content')
<style>
  .dropdown-toggle .position-absolute {
      width: 8px;
      height: 8px;
      font-size: 0;
  }

  .dropdown-toggle:hover .position-absolute {
      border-color: #fff !important;
  }

</style>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="page-header">TRAINEES</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item fw-medium">HTE</li>
          <li class="breadcrumb-item active text-muted">Interns</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
    @if(count($deployedInterns) > 0)
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Deployed Students</h3>
      </div>
      
      <div class="card-body table-responsive py-0 px-3" style="position: relative;">
        <!-- Loading Overlay -->
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
          <span class="text-primary">Loading Interns . . .</span>
        </div>
        
        <table id="internsTableHTE" class="table table-bordered mb-0">
          <thead class="table-light">
            <tr>
              <th width="12%">Student ID</th>
              <th>Name</th>
              <th>Department</th>
              <th width="15%">Coordinator</th>
              <th width="10%">Status</th>
              <th width="10%">Grade</th>
              <th width="3%">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($deployedInterns as $deployment)
            @php
                $intern = $deployment->intern;
                $coordinator = $deployment->coordinator;
                
                // Get individual intern status
                $status = $intern->status;
                $statusClass = [
                  'pending requirements' => 'bg-warning-subtle text-warning',
                  'ready for deployment' => 'bg-info-subtle text-info',
                  'endorsed' => 'bg-primary-subtle text-primary',
                  'processing' => 'bg-secondary-subtle text-secondary',
                  'deployed' => 'bg-success-subtle text-success',
                  'completed' => 'bg-success-subtle text-success'
                ][$status] ?? 'bg-light text-dark';
                
                // Check if completed for evaluate action
                $isCompleted = $status === 'completed';
                
                // Check if evaluated and get grade
                $isEvaluated = $deployment->evaluation !== null;
                $grade = $isEvaluated ? $deployment->evaluation->grade : null;
                $gpa = $grade ? number_format((100 - $grade) / 20, 1) : null;
            @endphp
            <tr>
                <td class="align-middle">{{ $intern->student_id }}</td>
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
                <td class="align-middle">{{ $intern->department->dept_name ?? 'N/A' }}</td>
                <td class="align-middle">
                    <div class="d-flex align-items-center">
                        @if($coordinator->user->pic)
                            <img src="{{ asset('storage/' . $coordinator->user->pic) }}" 
                                alt="Coordinator Picture" 
                                class="rounded-circle me-2 table-pfp" 
                                width="25" height="25">
                        @else
                            @php
                                // Generate a consistent random color based on user's name
                                $name = $coordinator->user->fname . $coordinator->user->lname;
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
                            
                            <div class="rounded-circle me-2 d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0" 
                                style="width: 25px; height: 25px; font-size: 10px; background: {{ $randomGradient }};">
                                {{ strtoupper(substr($coordinator->user->fname, 0, 1) . substr($coordinator->user->lname, 0, 1)) }}
                            </div>
                        @endif
                        <div class="small">
                            {{ $coordinator->user->fname }} {{ $coordinator->user->lname }}
                        </div>
                    </div>
                </td>
                <td class="align-middle">
                    <span class="small badge py-2 px-3 rounded-pill {{ $statusClass }}" style="font-size: 14px">
                        {{ ucwords($status) }}
                    </span>
                </td>
                @php
                    // Check if evaluated and get grade
                    $isEvaluated = $deployment->evaluation !== null;
                    $grade = $isEvaluated ? $deployment->evaluation->grade : null;
                    $gpa = $isEvaluated ? $deployment->evaluation->calculateGPA() : null;
                    
                    // Get GPA color for the badge
                    $gpaColorClass = 'bg-success-subtle text-success'; // Default green
                    if ($gpa) {
                        if ($gpa >= 4.00) $gpaColorClass = 'bg-danger-subtle text-danger';
                        elseif ($gpa >= 3.00) $gpaColorClass = 'bg-warning-subtle text-warning';
                        elseif ($gpa >= 2.00) $gpaColorClass = 'bg-info-subtle text-info';
                    }
                    
                    // Check if needs evaluation (completed status but not evaluated)
                    $needsEvaluation = $isCompleted && !$isEvaluated;
                @endphp

                <td class="align-middle text-center">
                    @if($isEvaluated)
                        <span class="small badge {{ $gpaColorClass }} py-2 px-3 rounded-pill" style="font-size: 14px">
                            {{ number_format($gpa, 2) }}
                        </span>
                    @else
                        <span class="small badge bg-danger-subtle text-danger py-2 px-3 rounded-pill" style="font-size: 14px">
                            No Evaluation
                        </span>
                    @endif
                </td>
                <td class="text-center px-2 align-middle">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-dark dropdown-toggle rounded-pill position-relative" 
                                type="button" 
                                id="actionDropdown" 
                                data-toggle="dropdown" 
                                aria-haspopup="true" 
                                aria-expanded="false">
                            <i class="ph-fill ph-gear custom-icons-i"></i>
                            
                            <!-- Notification Badge -->
                            @if($needsEvaluation)
                                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                                    <span class="visually-hidden">Evaluation needed</span>
                                </span>
                            @endif
                        </button>
                        <div class="dropdown-menu dropdown-menu-right py-0" aria-labelledby="actionDropdown">
                            <!-- View Option -->
                            <a class="dropdown-item btn btn-outline-light text-dark" href="{{ route('hte.intern.show', $intern->id) }}">
                                <i class="ph ph-eye custom-icons-i mr-2"></i>View
                            </a>
                            
                            <!-- Evaluate Option - Only show if completed and not evaluated -->
                            @if($needsEvaluation)
                                <a class="dropdown-item border-top border-bottom border-lightgray btn btn-outline-light text-primary" 
                                  href="#" 
                                  data-toggle="modal" 
                                  data-target="#evaluateModal{{ $deployment->id }}">
                                    <i class="ph ph-clipboard-text custom-icons-i mr-2"></i>
                                    <span>Evaluate</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </td>
            </tr>

<!-- Evaluation Modal -->
@if($isCompleted && !$isEvaluated)
<div class="modal fade" id="evaluateModal{{ $deployment->id }}" tabindex="-1" role="dialog" aria-labelledby="evaluateModalLabel{{ $deployment->id }}" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header bg-light text-white py-3">
        <h5 class="modal-title mb-0" id="evaluateModalLabel{{ $deployment->id }}">
          <i class="ph ph-clipboard-text custom-icons-i mr-2"></i>
          Evaluate Intern Performance
        </h5>
        <button type="button" class="close text-secondary" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="evaluateForm{{ $deployment->id }}" class="evaluate-form">
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
                {{ \Carbon\Carbon::parse($deployment->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($deployment->end_date)->format('M d, Y') }}
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
                            <label for="{{ $factor }}{{ $deployment->id }}" class="form-label fw-semibold mb-0 small">
                              {{ $details['label'] }} <span class="text-success">({{ $details['percentage'] }})</span>
                            </label>
                          </div>
                          <p class="text-muted small mb-1">{{ $details['description'] }}</p>
                        </div>
                        <div class="col-md-4">
                          <div class="input-group input-group-sm">
                            <input type="number" 
                                   class="form-control factor-input border" 
                                   id="{{ $factor }}{{ $deployment->id }}" 
                                   name="{{ $factor }}" 
                                   min="0" 
                                   max="100" 
                                   step="1" 
                                   placeholder="0-100"
                                   required>
                            <span class="input-group-text bg-white">/100</span>
                          </div>
                        </div>
                      </div>
                      <div class="invalid-feedback small" id="{{ $factor }}Error{{ $deployment->id }}"></div>
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
                    Comments & Reccomendations on Student's Performance
                  </h6>
                </div>
                <div class="card-body p-3 d-flex flex-column">
                  <textarea class="form-control flex-grow-1" 
                            id="comments{{ $deployment->id }}" 
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
                    <div id="totalGradePreview{{ $deployment->id }}" class="h2 fw-bold text-primary mb-0">
                      0.00
                    </div>
                    <small class="text-muted">out of 95</small>
                    
                    <!-- Progress Bar -->
                    <div class="progress mt-2 rounded-pill" style="height: 6px;">
                      <div id="gradeProgress{{ $deployment->id }}" 
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
                        <div id="gpaPreview{{ $deployment->id }}" class="h5 fw-bold text-success mb-0">
                          0.00
                        </div>
                      </div>
                      <div class="col-4">
                        <small class="text-muted d-block">Letter</small>
                        <div id="letterGradePreview{{ $deployment->id }}" class="h5 fw-bold text-info mb-0">
                          -
                        </div>
                      </div>
                      <div class="col-4">
                        <small class="text-muted d-block">Remark</small>
                        <div id="gradeStatus{{ $deployment->id }}" class="h5 fw-bold text-warning mb-0">
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
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @else
    <div class="d-flex flex-column align-items-center justify-content-center text-muted" style="height: 70vh;">
      <i class="ph ph-users fs-1 mb-3" style="font-size: 4rem !important;"></i>
      <h4 class="text-muted mb-2">Wow, such empty.</h4>
      <p class="text-muted mb-0">Check back later for deployed students.</p>
    </div>
    @endif
  </div>
</section>

<style>
  .factors-list .factor-item:last-child {
  border-bottom: none !important;
  margin-bottom: 0 !important;
  padding-bottom: 0 !important;
}

.fs-12 { 
  font-size: 0.75rem; 
}

.input-group-sm .form-control { 
  height: calc(1.5em + 0.5rem + 2px); 
  font-size: 0.875rem;
}

.progress-bar { 
  transition: width 0.3s ease; 
}

.factor-item {
  transition: background-color 0.2s ease;
  padding: 8px 4px;
}

.factor-item:hover {
  background-color: #f8f9fa;
  border-radius: 4px;
}

/* Ensure equal height distribution on left side */
.flex-fill {
  flex: 1 1 auto;
}

.d-flex.flex-column > .flex-fill {
  min-height: 0; /* Allow flex items to shrink */
}

/* Compact textarea */
.form-control.flex-grow-1 {
  min-height: 120px;
  resize: vertical;
}
  
</style>

@endsection

@section('scripts')

@endsection