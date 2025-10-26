{{-- resources/views/hte/interns.blade.php --}}
@extends('layouts.hte')

@section('title', 'HTE | Interns')

@section('content')
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
                <img src="{{ asset('storage/' . $intern->user->pic) }}" 
                  alt="Profile Picture" 
                  class="rounded-circle me-2 table-pfp" 
                  width="30" height="30">
                {{ $intern->user->lname }}, {{ $intern->user->fname }} 
              </td>         
              <td class="align-middle">{{ $intern->department->dept_name ?? 'N/A' }}</td>
              <td class="align-middle">
                <div class="d-flex align-items-center">
                  <img src="{{ asset('storage/' . $coordinator->user->pic) }}" 
                    alt="Coordinator Picture" 
                    class="rounded-circle me-2 table-pfp" 
                    width="25" height="25">
                  <div>
                    <strong>{{ $coordinator->user->fname }} {{ $coordinator->user->lname }}</strong>
                    <br>
                    <small class="text-muted">{{ $coordinator->faculty_id }}</small>
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
                  <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="actionDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="ph-fill ph-gear custom-icons-i"></i>
                  </button>
                  <div class="dropdown-menu dropdown-menu-right py-0" aria-labelledby="actionDropdown">
                    <!-- View Option -->
                    <a class="dropdown-item btn btn-outline-light text-dark" href="{{ route('hte.intern.show', $intern->id) }}">
                      <i class="ph ph-eye custom-icons-i mr-2"></i>View
                    </a>
                    
                    <!-- Evaluate Option - Only show if completed and not evaluated -->
                    @if($isCompleted && !$isEvaluated)
                      <a class="dropdown-item border-top border-bottom border-lightgray btn btn-outline-light text-dark" href="#" data-toggle="modal" data-target="#evaluateModal{{ $deployment->id }}">
                        <i class="ph ph-clipboard-text custom-icons-i mr-2"></i>Evaluate
                      </a>
                    @endif
                  </div>
                </div>
              </td>
            </tr>

            <!-- Evaluation Modal -->
            @if($isCompleted && !$isEvaluated)
            <div class="modal fade" id="evaluateModal{{ $deployment->id }}" tabindex="-1" role="dialog" aria-labelledby="evaluateModalLabel{{ $deployment->id }}" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header bg-light">
                    <h5 class="modal-title" id="evaluateModalLabel{{ $deployment->id }}">
                      <i class="ph ph-clipboard-text custom-icons-i mr-2"></i>
                      Evaluate Intern
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <form id="evaluateForm{{ $deployment->id }}" class="evaluate-form">
                    @csrf
                    <div class="modal-body">
                      <div class="text-center mb-4">
                        <img src="{{ asset('storage/' . $intern->user->pic) }}" 
                          alt="Profile Picture" 
                          class="rounded-circle mb-3" 
                          width="80" height="80">
                        <h5>{{ $intern->user->fname }} {{ $intern->user->lname }}</h5>
                        <p class="text-muted">{{ $intern->student_id }} • {{ $intern->department->dept_name ?? 'N/A' }}</p>
                      </div>
                      
                      <div class="alert bg-info-subtle text-info">
                        <i class="ph ph-info custom-icons-i mr-2"></i>
                        Provide a grade evaluation for this intern's performance during their internship period.
                      </div>
                      
                      <!-- Evaluation Form -->
                      <div class="form-group">
                        <label for="grade{{ $deployment->id }}" class="form-label">Grade (0-100) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="grade{{ $deployment->id }}" name="grade" min="0" max="100" step="0.01" placeholder="Enter grade from 0 to 100" required>
                        <small class="form-text text-muted">100 = Excellent, 0 = Poor</small>
                        <div class="invalid-feedback" id="gradeError{{ $deployment->id }}"></div>
                      </div>
                      <div class="form-group">
                        <label for="comments{{ $deployment->id }}" class="form-label">Comments (Optional)</label>
                        <textarea class="form-control" id="comments{{ $deployment->id }}" name="comments" rows="3" placeholder="Provide feedback on the intern's performance..."></textarea>
                      </div>
                    </div>
                    <div class="modal-footer bg-light">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                      <button type="submit" class="btn btn-primary submit-evaluate-btn">
                        <span class="submit-text">Submit Evaluation</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
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

@endsection

@section('scripts')

@endsection