@php use App\Models\InternDocument; @endphp
@extends('layouts.intern')

@section('title', 'Dashboard')

@section('content')
<section class="content-header">
  <div class="container-fluid px-sm-2 px-0">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="page-header">DASHBOARD</h1>
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

<section class="content px-sm-2 px-0">
  <div class="container-fluid">

    {{-- ENHANCED INTERN STATUS CARD --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            {{-- Main Content Row --}}
            <div class="row align-items-center">
                {{-- Profile Picture and Basic Info --}}
                <div class="col-lg-8 col-md-7">
                    <div class="d-flex align-items-start">
                        {{-- Intern Information --}}
                        <div class="flex-grow-1">
                            <h4 class="mb-1 fw-bold text-break">{{ auth()->user()->fname }} {{ auth()->user()->lname }}</h4>
                            
                            {{-- Student ID --}}
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-id-card text-muted me-2" style="min-width: 16px;"></i>
                                <span class="text-muted small">{{ auth()->user()->intern->student_id ?? 'N/A' }}</span>
                            </div>

                            {{-- Section Info --}}
                            <div class="d-flex align-items-center mb-2 flex-wrap">
                                <i class="fas fa-users text-muted me-2" style="min-width: 16px;"></i>
                                @php
                                    $intern = auth()->user()->intern;
                                    $section = $intern->section ? strtoupper($intern->section) : '';
                                    $departmentShort = $intern->department->short_name ?? 'BSIT';
                                @endphp
                                <span class="text-primary fw-medium small">
                                    {{ $departmentShort }}-{{ $intern->year_level }}{{ $section }}
                                </span>
                            </div>

                            {{-- Academic Info --}}
                            <div class="d-flex align-items-center">
                                <i class="fas fa-calendar-alt text-muted me-2" style="min-width: 16px;"></i>
                                <small class="text-muted">
                                    {{ ucfirst($semester) }} Semester, A.Y. {{ $academic_year }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Status Badge --}}
                <div class="col-lg-4 col-md-5 mt-3 mt-md-0">
                    <div class="text-lg-end text-md-end text-start">
                        @php
                            $intern = auth()->user()->intern;
                            $status = strtolower($intern->status);
                            $badgeClass = match($status) {
                                'pending requirements' => 'bg-danger-subtle text-danger border-danger',
                                'ready for deployment' => 'bg-warning-subtle text-warning border-warning',
                                'processing' => 'bg-info-subtle text-info border-info',
                                'endorsed' => 'bg-primary-subtle text-primary border-primary',
                                'deployed' => 'bg-success-subtle text-success border-success',
                                'completed' => 'bg-dark-subtle text-dark border-dark',
                                default => 'bg-secondary border-secondary'
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
                        
                        <div class="d-inline-block border rounded-pill px-3 py-2 {{ $badgeClass }}">
                            <i class="{{ $statusIcon }} me-2"></i>
                            <span class="fw-bold">{{ ucfirst($intern->status) }}</span>
                        </div>
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
                        mb-0 py-3">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-info-circle me-3 mt-1 flex-shrink-0"></i>
                            <div class="flex-grow-1">
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
            <div class="card-header bg-white border-bottom-0 pb-2 fw-bold">
                <div class="d-flex align-items-center">
                    <i class="ph-fill ph-building-apartment text-primary me-2 fs-5"></i>
                    <span>HTE Assignment</span>
                </div>
            </div>
            <div class="card-body pt-0">
                {{-- Organization Name --}}
                <h5 class="fw-bold text-primary mb-1 text-break">{{ $hteDetails->organization_name }}</h5>
                
                {{-- Address --}}
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-map-marker-alt text-muted flex-shrink-0 me-1" style="min-width: 16px;"></i>
                    <span class="text-muted small">{{ $hteDetails->address }}</span>
                </div>

                {{-- Type and Status --}}
                <div class="row g-2">
                    <div class="col-sm-6">
                        <div class="border rounded p-3 h-100">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-tag text-primary me-2 fs-6"></i>
                                <small class="text-muted fw-medium">TYPE</small>
                            </div>
                            <span class="fw-bold text-dark text-capitalize">
                                {{ ucfirst($hteDetails->type) }}
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="border rounded p-3 h-100">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-circle me-2 fs-6 
                                    {{ $hteDetails->status == 'active' ? 'text-success' : 'text-warning' }}"
                                    style="font-size: 0.5rem;"></i>
                                <small class="text-muted fw-medium">STATUS</small>
                            </div>
                            <span class="fw-bold text-capitalize
                                {{ $hteDetails->status == 'active' ? 'text-success' : 'text-warning' }}">
                                {{ ucfirst($hteDetails->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Additional Info (if available) --}}
                @if($hteDetails->description)
                    <div class="mt-3 pt-3 border-top">
                        <small class="text-muted fw-medium d-block mb-2">ABOUT</small>
                        <p class="small text-muted mb-0">{{ Str::limit($hteDetails->description, 120) }}</p>
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- DOCUMENT REQUIREMENTS CHECKLIST --}}
    @if(in_array($status, ['pending requirements', 'ready for deployment']))
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0">
          <h6 class="mb-0 fw-bold">
            <i class="fas fa-clipboard-list text-primary me-2"></i>
            Pre-Deployment Requirements
          </h6>
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
              <i class="fas fa-folder-open me-2"></i>Submit Requirements
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

        <!-- Time Tracking Section -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 d-flex flex-column">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock me-2 text-success"></i>
                        Time Tracking
                    </h5>
                </div>
                <div class="card-body text-center d-flex flex-column">
                    <div class="mb-3 flex-shrink-0">
                        <h4 class="fw-bold" id="currentTime">--:--:--</h4>
                        <p class="text-muted mb-3" id="currentDate">Loading date...</p>
                    </div>

                    <!-- Student ID Input -->
                    <div id="studentIdWrapper" class="mb-3">
                        <input type="text" id="studentIdInput" class="form-control text-center"
                            placeholder="Enter Student ID" maxlength="20" autocomplete="off">
                    </div>

                    <!-- Punch Controls -->
                    <div id="attendanceControls">
                        <button class="btn btn-success w-100 py-3 fw-bold" id="punchInBtn">
                            <i class="fas fa-sign-in-alt me-2"></i> Punch In
                        </button>
                    </div>

                    <!-- Summary -->
                    <div id="attendanceSummary" class="mt-auto pt-4 d-none">
                        <div class="p-3 bg-light rounded">
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

                    <input type="hidden" id="internStartDate" value="{{ $internHte->start_date ?? '' }}">
                </div>
            </div>
        </div>

        <!-- Progress Section -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 d-flex flex-column">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2 text-primary"></i>
                        Internship Progress
                    </h5>
                </div>
                <div class="card-body text-center d-flex flex-column">
                    <div class="position-relative d-inline-block flex-shrink-0">
                        <canvas id="progressChart" width="200" height="200"></canvas>
                        <div class="position-absolute top-50 start-50 translate-middle text-center">
                            <h3 class="mb-0 fw-bold">65%</h3>
                            <small class="text-muted">Complete</small>
                        </div>
                    </div>
                    <div class="mt-auto pt-4">
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

        <!-- Quick Stats -->
        <div class="col-lg-4 col-md-12 mb-4">
            <div class="card h-100 d-flex flex-column">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-tachometer-alt me-2 text-warning"></i>
                        Quick Stats
                    </h5>
                </div>
                <div class="card-body d-flex flex-column">
                    <div class="row text-center flex-grow-1">
                        <div class="col-6 mb-3 d-flex">
                            <div class="border rounded p-3 w-100 d-flex flex-column justify-content-center align-items-center">
                                <i class="fas fa-calendar-week fa-2x text-primary mb-2"></i>
                                <h5 class="fw-bold mb-1">Week 8</h5>
                                <small class="text-muted">Current Week</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3 d-flex">
                            <div class="border rounded p-3 w-100 d-flex flex-column justify-content-center align-items-center">
                                <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                <h5 class="fw-bold mb-1">7/22</h5>
                                <small class="text-muted">Reports Done</small>
                            </div>
                        </div>
                        <div class="col-6 d-flex">
                            <div class="border rounded p-3 w-100 d-flex flex-column justify-content-center align-items-center">
                                <i class="fas fa-bullseye fa-2x text-info mb-2"></i>
                                <h5 class="fw-bold mb-1">84%</h5>
                                <small class="text-muted">Attendance</small>
                            </div>
                        </div>
                        <div class="col-6 d-flex">
                            <div class="border rounded p-3 w-100 d-flex flex-column justify-content-center align-items-center">
                                <i class="fas fa-hourglass-half fa-2x text-warning mb-2"></i>
                                <h5 class="fw-bold mb-1">6</h5>
                                <small class="text-muted">Weeks Left</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const timeDisplay = document.getElementById('currentTime');
    const dateDisplay = document.getElementById('currentDate');
    const punchBtnContainer = document.getElementById('attendanceControls');
    const summaryCard = document.getElementById('attendanceSummary');
    const studentIdInput = document.getElementById('studentIdInput');
    const todayIn = document.getElementById('todayIn');
    const todayOut = document.getElementById('todayOut');
    const todayHours = document.getElementById('todayHours');
    const startDate = new Date(document.getElementById('internStartDate').value);

    // Real-time clock
    setInterval(() => {
        const now = new Date();
        timeDisplay.textContent = now.toLocaleTimeString();
        dateDisplay.textContent = now.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });
    }, 1000);

    fetchAttendanceStatus();

    function fetchAttendanceStatus() {
        fetch("{{ route('intern.getAttendanceStatus') }}")
            .then(res => res.json())
            .then(data => {
                const now = new Date();
                if (now < startDate) {
                    startCountdown();
                    return;
                }

                if (!data.attendance) {
                    renderPunchIn();
                } else if (data.attendance.time_in && !data.attendance.time_out) {
                    renderPunchOut(data.attendance.time_in, data.attendance.time_in_raw);
                } else {
                    renderSummary(data.attendance);
                }
            });
    }

    function renderPunchIn() {
        punchBtnContainer.innerHTML = `
            <button class="btn btn-success w-100 py-3 fw-bold" id="punchInBtn">
                <i class="fas fa-sign-in-alt me-2"></i> Punch In
            </button>`;
        summaryCard.classList.add('d-none');

        document.getElementById('punchInBtn').addEventListener('click', function () {
            const sid = studentIdInput.value.trim();
            if (!sid) return toastr.warning('Please enter your Student ID.');

            fetch("{{ route('intern.punchIn') }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
                body: JSON.stringify({ student_id: sid })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    toastr.success(data.message);
                    // Clear student ID input after successful punch in
                    studentIdInput.value = '';
                    renderPunchOut(data.time_in, new Date());
                } else {
                    toastr.error(data.error || 'Error punching in.');
                }
            });
        });
    }

    function renderPunchOut(timeIn, timeInRaw) {
        punchBtnContainer.innerHTML = `
            <button class="btn btn-danger w-100 py-3 fw-bold" id="punchOutBtn">
                <i class="fas fa-sign-out-alt me-2"></i> Punch Out
                <small class="d-block" id="runningTime">Running: 00:00:00</small>
            </button>`;
        summaryCard.classList.add('d-none');

        // Clear student ID input when switching to punch out state
        studentIdInput.value = '';

        // Persisted runtime since DB punch-in
        const start = new Date(timeInRaw);
        setInterval(() => {
            const diff = new Date() - start;
            const hrs = String(Math.floor(diff / 3600000)).padStart(2, '0');
            const mins = String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0');
            const secs = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0');
            document.getElementById('runningTime').textContent = `Running: ${hrs}:${mins}:${secs}`;
        }, 1000);

        document.getElementById('punchOutBtn').addEventListener('click', function () {
            const sid = studentIdInput.value.trim();
            if (!sid) return toastr.warning('Please enter your Student ID.');

            fetch("{{ route('intern.punchOut') }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
                body: JSON.stringify({ student_id: sid })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    toastr.success(data.message);
                    renderSummary(data);
                } else {
                    toastr.error(data.error || 'Error punching out.');
                }
            });
        });
    }

    function renderSummary(data) {
        punchBtnContainer.innerHTML = `
            <button class="btn btn-secondary w-100 py-3 fw-bold" disabled>
                Attendance Saved
            </button>`;
        document.getElementById('studentIdWrapper').classList.add('d-none');
        todayIn.textContent = data.time_in;
        todayOut.textContent = data.time_out;
        todayHours.textContent = data.hours;
        summaryCard.classList.remove('d-none');
    }

    function startCountdown() {
        punchBtnContainer.innerHTML = `<button class="btn btn-secondary w-100 py-3 fw-bold" disabled id="countdownBtn"></button>`;
        const btn = document.getElementById('countdownBtn');
        const interval = setInterval(() => {
            const now = new Date();
            const diff = startDate - now;
            if (diff <= 0) { clearInterval(interval); fetchAttendanceStatus(); return; }
            const d = Math.floor(diff / (1000*60*60*24));
            const h = Math.floor((diff / (1000*60*60)) % 24);
            const m = Math.floor((diff / (1000*60)) % 60);
            const s = Math.floor((diff / 1000) % 60);
            btn.innerHTML = `Internship starts in<br><strong>${d}d ${h}h ${m}m ${s}s</strong>`;
        }, 1000);
    }
});
</script>
@endsection



