@php use App\Models\InternDocument; @endphp
@extends('layouts.intern')

@section('title', 'Dashboard')

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="page-header">Dashboard</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item fw-medium">Intern</li>
          <li class="breadcrumb-item active text-muted">Dashboard</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">

    {{-- ENHANCED INTERN STATUS CARD --}}
    <div class="card shadow-sm border-0 mb-4">
      <div class="card-body">
        <div class="row align-items-center">
          {{-- Profile Picture and Basic Info --}}
          <div class="col-md-8">
            <div class="d-flex align-items-center">
              {{-- Profile Picture --}}
              <div class="me-4">
                @if(auth()->user()->profile_picture)
                  <img src="{{ asset('storage/' . auth()->user()->pic) }}" 
                       alt="Profile Picture" 
                       class="rounded-circle" 
                       style="width: 80px; height: 80px; object-fit: cover;">
                @else
                  <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" 
                       style="width: 80px; height: 80px;">
                    <i class="fas fa-user text-muted" style="font-size: 2rem;"></i>
                  </div>
                @endif
              </div>
              
              {{-- Intern Information --}}
              <div class="flex-grow-1">
                <h4 class="mb-1 fw-bold">{{ auth()->user()->fname }} {{ auth()->user()->lname }}</h4>
                <p class="mb-2 text-muted">
                  <i class="fas fa-id-card me-1"></i>
                  {{ auth()->user()->intern->student_id ?? 'N/A' }}
                </p>
                <p class="mb-2">
                  @php
                      $intern = auth()->user()->intern;
                      $section = $intern->section ? strtoupper($intern->section) : '';
                      $departmentShort = $intern->department->short_name ?? 'BSIT';
                  @endphp
                  <span class="badge bg-primary-subtle text-primary px-3 py-2 fw-medium">
                    <i class="fas fa-users me-1"></i>
                    {{ $departmentShort }}-{{ $intern->year_level }}{{ $section }}
                  </span>
                </p>
                <small class="text-muted">
                  <i class="fas fa-calendar-alt me-1"></i>
                  {{ ucfirst($semester) }} Semester, A.Y. {{ $academic_year }}
                </small>
              </div>
            </div>
          </div>

          {{-- Status and Badge --}}
          <div class="col-md-4 text-md-end">
            @php
                $intern = auth()->user()->intern;
                $status = strtolower($intern->status);
                $badgeClass = match($status) {
                    'pending requirements' => 'bg-danger-subtle text-danger',
                    'ready for deployment' => 'bg-warning-subtle text-warning',
                    'processing' => 'bg-info-subtle text-info',
                    'endorsed' => 'bg-primary-subtle text-primary',
                    'deployed' => 'bg-success-subtle text-success',
                    'completed' => 'bg-dark-subtle text-dark',
                    default => 'bg-secondary'
                };
                
                $statusIcon = match($status) {
                    'pending requirements' => 'fas fa-clock',
                    'ready for deployment' => 'fas fa-check-circle',
                    'processing' => 'fas fa-cogs',
                    'endorsed' => 'fas fa-paper-plane',
                    'deployed' => 'fas fa-briefcase',
                    'completed' => 'fas fa-graduation-cap',
                    default => 'fas fa-info-circle'
                };
            @endphp
            
            <div class="text-sm-end text-start">
              <span class="badge {{ $badgeClass }} px-4 py-3 rounded-pill fw-bold fs-6">
                <i class="{{ $statusIcon }} me-2"></i>
                {{ ucfirst($intern->status) }}
              </span>
            </div>
          </div>
        </div>

        {{-- Dynamic Status Notes --}}
        <div class="row mt-4">
          <div class="col-12">
            <div class="alert 
              @switch($status)
                @case('pending requirements') alert-danger @break
                @case('ready for deployment') alert-warning @break
                @case('processing') alert-info @break
                @case('endorsed') alert-primary @break
                @case('deployed') alert-success @break
                @case('completed') alert-dark @break
                @default alert-secondary
              @endswitch
              mb-0">
              <div class="d-flex align-items-center">
                <i class="fas fa-info-circle me-3 fs-5"></i>
                <div>
                  <strong class="d-block mb-1">Status Update:</strong>
                  @switch($status)
                    @case('pending requirements')
                      Please submit all pre-deployment requirements to proceed with your internship application.
                    @break
                    @case('ready for deployment')
                      You have successfully submitted all pre-deployment requirements. Please await endorsement from your coordinator.
                    @break
                    @case('endorsed')
                      You have been endorsed to <strong>{{ $hteDetails->organization_name ?? 'the assigned organization' }}</strong>. Please wait for further instructions regarding your deployment.
                    @break
                    @case('processing')
                      Your endorsement is currently being processed. Please download the internship contract sent to your email, have it signed by your legal guardian, and submit the signed copy to your coordinator.
                    @break
                    @case('deployed')
                      Your internship is currently in progress at <strong>{{ $hteDetails->organization_name ?? 'your assigned organization' }}</strong>. Continue to maintain good attendance and complete all required tasks.
                    @break
                    @case('completed')
                      Congratulations! You have successfully completed your internship program. Thank you for your hard work and dedication.
                    @break
                    @default
                      Your internship status is being reviewed. Please check back later for updates.
                  @endswitch
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- HTE ASSIGNMENT CARD --}}
    @if(isset($hteDetails))
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light fw-bold">
          <i class="fas fa-building text-primary me-2"></i>Host Training Establishment (HTE) Assignment
        </div>
        <div class="card-body">
          <h5 class="fw-bold text-primary mb-1">{{ $hteDetails->organization_name }}</h5>
          <p class="mb-2 text-muted">
            <i class="fas fa-map-marker-alt me-1"></i>{{ $hteDetails->address }}
          </p>
          <p class="mb-0">
            <span class="badge 
              {{ $hteDetails->status == 'active' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }} px-3 py-2">
              <i class="fas fa-circle me-1" style="font-size: 0.6rem;"></i>
              {{ ucfirst($hteDetails->status) }}
            </span>
          </p>
        </div>
      </div>
    @endif

    {{-- DOCUMENT REQUIREMENTS CHECKLIST --}}
    @if(in_array($status, ['pending requirements', 'ready for deployment']))
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0">
          <h5 class="mb-0 fw-bold">
            <i class="fas fa-clipboard-list text-primary me-2"></i>
            Pre-Deployment Requirements
          </h5>
        </div>

        <div class="card-body">
          {{-- Completion Badge --}}
          <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="fw-medium text-muted">
              Submission Status:
              @if($documents->count() >= 9)
                <span class="badge bg-success-subtle text-success px-3 py-2">
                  <i class="fas fa-check-circle me-1"></i>Complete ({{ $documents->count() }}/9)
                </span>
              @else
                <span class="badge bg-warning-subtle text-warning px-3 py-2">
                  <i class="fas fa-exclamation-circle me-1"></i>Incomplete ({{ $documents->count() }}/9)
                </span>
              @endif
            </span>

            <a href="{{ route('intern.docs') }}" class="btn btn-sm btn-outline-primary fw-medium">
              <i class="fas fa-folder-open me-2"></i>Manage Documents
            </a>
          </div>

          {{-- Dynamic Document Checklist --}}
          <ul class="list-group list-group-flush">
            @foreach(InternDocument::typeLabels() as $type => $label)
              @php $document = $documents->where('type', $type)->first(); @endphp
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                  <strong>{{ $label }}</strong><br>
                  <small class="text-muted">
                    @switch($type)
                        @case('requirements_checklist') Signed checklist of all required documents @break
                        @case('certificate_of_registration') Current semester registration certificate @break
                        @case('report_of_grades') Latest official transcript with OJT qualification @break
                        @case('application_resume') Formal application letter with updated resume @break
                        @case('medical_certificate') Health clearance from university clinic @break
                        @case('parent_consent') Notarized consent form from parent/guardian @break
                        @case('insurance_certificate') Proof of valid insurance coverage @break
                        @case('pre_deployment_certification') Certification of orientation attendance @break
                        @case('ojt_fee_reciept') Official receipt of paid internship fee @break
                    @endswitch
                  </small>
                </div>
                @if($document)
                  <i class="fas fa-check-circle text-success fs-5" title="Submitted"></i>
                @else
                  <i class="fas fa-times-circle text-danger fs-5" title="Pending"></i>
                @endif
              </li>
            @endforeach
          </ul>
        </div>
      </div>
    @endif

    {{-- PROGRESS AND ATTENDANCE SECTION (Only for deployed interns) --}}
    @if($status == 'deployed')
    <div class="row">
      <!-- Progress Section -->
      <div class="col-lg-4 col-md-6">
        <div class="card">
          <div class="card-header bg-white">
            <h5 class="card-title mb-0">
              <i class="fas fa-chart-pie mr-2 text-primary"></i>
              Internship Progress
            </h5>
          </div>
          <div class="card-body text-center">
            <div class="position-relative d-inline-block">
              <canvas id="progressChart" width="200" height="200"></canvas>
              <div class="position-absolute top-50 start-50 translate-middle text-center">
                <h3 class="mb-0 fw-bold">65%</h3>
                <small class="text-muted">Complete</small>
              </div>
            </div>
            <div class="mt-4">
              <div class="row text-center">
                <div class="col-6">
                  <h4 class="fw-bold text-primary mb-1">156</h4>
                  <small class="text-muted">Hours Rendered</small>
                </div>
                <div class="col-6">
                  <h4 class="fw-bold text-success mb-1">240</h4>
                  <small class="text-muted">Total Required</small>
                </div>
              </div>
              <div class="progress mt-3" style="height: 8px;">
                <div class="progress-bar bg-primary" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Time Tracking Section -->
      <div class="col-lg-4 col-md-6">
        <div class="card">
          <div class="card-header bg-white">
            <h5 class="card-title mb-0">
              <i class="fas fa-clock mr-2 text-success"></i>
              Time Tracking
            </h5>
          </div>
          <div class="card-body text-center">
            <div class="mb-4">
              <h4 class="text-muted mb-2" id="currentTime">--:--:--</h4>
              <p class="text-muted mb-3" id="currentDate">Loading...</p>
              
              <div class="alert alert-info py-2 mb-3">
                <small>
                  <i class="fas fa-building mr-1"></i>
                  <strong>Current HTE:</strong> {{ $hteDetails->organization_name ?? 'Tech Solutions Inc.' }}
                </small>
              </div>
            </div>

            <!-- Punch In/Out Buttons -->
            <div class="row g-2">
              <div class="col-6">
                <button class="btn btn-success w-100 py-3" id="punchInBtn" disabled>
                  <i class="fas fa-sign-in-alt mr-2"></i>
                  <div>Punch In</div>
                  <small class="d-block">--:--</small>
                </button>
              </div>
              <div class="col-6">
                <button class="btn btn-danger w-100 py-3" id="punchOutBtn" disabled>
                  <i class="fas fa-sign-out-alt mr-2"></i>
                  <div>Punch Out</div>
                  <small class="d-block">--:--</small>
                </button>
              </div>
            </div>

            <!-- Today's Summary -->
            <div class="mt-4 p-3 bg-light rounded">
              <h6 class="mb-2">Today's Summary</h6>
              <div class="row text-center">
                <div class="col-4">
                  <small class="text-muted d-block">In</small>
                  <strong id="todayIn">--:--</strong>
                </div>
                <div class="col-4">
                  <small class="text-muted d-block">Out</small>
                  <strong id="todayOut">--:--</strong>
                </div>
                <div class="col-4">
                  <small class="text-muted d-block">Hours</small>
                  <strong id="todayHours">0.0</strong>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Stats -->
      <div class="col-lg-4 col-md-12">
        <div class="card">
          <div class="card-header bg-white">
            <h5 class="card-title mb-0">
              <i class="fas fa-tachometer-alt mr-2 text-warning"></i>
              Quick Stats
            </h5>
          </div>
          <div class="card-body">
            <div class="row text-center">
              <div class="col-6 mb-3">
                <div class="border rounded p-3">
                  <i class="fas fa-calendar-week fa-2x text-primary mb-2"></i>
                  <h5 class="fw-bold mb-1">Week 8</h5>
                  <small class="text-muted">Current Week</small>
                </div>
              </div>
              <div class="col-6 mb-3">
                <div class="border rounded p-3">
                  <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                  <h5 class="fw-bold mb-1">7/22</h5>
                  <small class="text-muted">Reports Done</small>
                </div>
              </div>
              <div class="col-6">
                <div class="border rounded p-3">
                  <i class="fas fa-bullseye fa-2x text-info mb-2"></i>
                  <h5 class="fw-bold mb-1">84%</h5>
                  <small class="text-muted">Attendance</small>
                </div>
              </div>
              <div class="col-6">
                <div class="border rounded p-3">
                  <i class="fas fa-hourglass-half fa-2x text-warning mb-2"></i>
                  <h5 class="fw-bold mb-1">6</h5>
                  <small class="text-muted">Weeks Left</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endif

  </div>
</section>
@endsection