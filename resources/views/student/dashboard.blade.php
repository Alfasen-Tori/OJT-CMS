{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.intern')

@section('title', 'Intern | Home')

@section('content')
<section class="content-header">
  <div class="container-fluid">
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

<section class="content">
  <div class="container-fluid">
    <!-- Status Header Card -->
    <div class="card p-3 d-flex flex-column justify-content-center">
      <h5 class="align-middle w-100 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-md-center gap-2" style="position: relative; top: 4px;">
        <span class="text-muted align-middle">AY: {{$academic_year}} • {{$semester}} Semester</span>
        @php
          $status = strtolower($status);
          $badgeClass = match($status) {
            'pending requirements' => 'bg-danger-subtle text-danger',
            'ready for deployment' => 'bg-warning-subtle text-warning',
            'endorsed' => 'bg-primary-subtle text-primary border-primary',
            default => 'bg-secondary'
          };
        @endphp
        <span class="badge {{ $badgeClass }} px-4 py-2 rounded-pill m-0">{{ strtoupper($status) }}</span>
      </h5>
    </div>

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
                  <strong>Current HTE:</strong> Tech Solutions Inc.
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

    <!-- Original Cards Row -->
    <div class="row mt-4">
      <!-- Docs -->
      <div class="col-lg-4 col-md-6 col-12">
        <div class="small-box bg-info"> 
          <div class="inner p-3 d-flex flex-column justify-content-center align-items-start">
            <h2 class="fw-medium">{{ $documentCount }} out of 9</h2>
            <p>Requirements</p>
          </div>
          <div class="infobox-icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="box-icon" viewBox="0 0 256 256"><path d="M213.66,82.34l-56-56A8,8,0,0,0,152,24H56A16,16,0,0,0,40,40V216a16,16,0,0,0,16,16H200a16,16,0,0,0,16-16V88A8,8,0,0,0,213.66,82.34ZM160,176H96a8,8,0,0,1,0-16h64a8,8,0,0,1,0,16Zm0-32H96a8,8,0,0,1,0-16h64a8,8,0,0,1,0,16Zm-8-56V44l44,44Z"></path></svg>
          </div>
          <a href="{{route('intern.docs')}}" class="small-box-footer">
            More info <i class="fas fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>

      <!-- My Internship -->
      <div class="col-lg-4 col-md-6 col-12">
        <div class="small-box bg-success"> 
          <div class="inner p-3 d-flex flex-column justify-content-center align-items-start">
            <h2 class="fw-medium">0 out of 22</h2>
            <p>Weekly Reports</p>
          </div>
          <div class="infobox-icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="box-icon" viewBox="0 0 256 256"><path d="M208,32H48A16,16,0,0,0,32,48V208a16,16,0,0,0,16,16H208a16,16,0,0,0,16-16V48A16,16,0,0,0,208,32ZM117.66,149.66l-32,32a8,8,0,0,1-11.32,0l-16-16a8,8,0,0,1,11.32-11.32L80,164.69l26.34-26.35a8,8,0,0,1,11.32,11.32Zm0-64-32,32a8,8,0,0,1-11.32,0l-16-16A8,8,0,0,1,69.66,90.34L80,100.69l26.34-26.35a8,8,0,0,1,11.32,11.32ZM192,168H144a8,8,0,0,1,0-16h48a8,8,0,0,1,0,16Zm0-64H144a8,8,0,0,1,0-16h48a8,8,0,0,1,0,16Z"></path></svg>
          </div>
          <a href="#" class="small-box-footer">
            More info <i class="fas fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>

      <!-- Attendance Summary -->
      <div class="col-lg-4 col-md-6 col-12">
        <div class="small-box bg-warning"> 
          <div class="inner p-3 d-flex flex-column justify-content-center align-items-start">
            <h2 class="fw-medium">84%</h2>
            <p>Attendance Rate</p>
          </div>
          <div class="infobox-icon">
            <i class="fas fa-user-check box-icon"></i>
          </div>
          <a href="#" class="small-box-footer">
            View Details <i class="fas fa-arrow-circle-right"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Initialize Progress Chart
  const progressCtx = document.getElementById('progressChart').getContext('2d');
  const progressChart = new Chart(progressCtx, {
    type: 'doughnut',
    data: {
      datasets: [{
        data: [65, 35],
        backgroundColor: ['#007bff', '#e9ecef'],
        borderWidth: 0,
        cutout: '75%'
      }]
    },
    options: {
      responsive: false,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false
        },
        tooltip: {
          enabled: false
        }
      }
    }
  });

  // Real-time Clock
  function updateClock() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('en-US', { 
      hour12: false,
      hour: '2-digit',
      minute: '2-digit',
      second: '2-digit'
    });
    const dateString = now.toLocaleDateString('en-US', { 
      weekday: 'long',
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    });
    
    document.getElementById('currentTime').textContent = timeString;
    document.getElementById('currentDate').textContent = dateString;
  }

  // Update clock every second
  updateClock();
  setInterval(updateClock, 1000);

  // Punch In/Out Button Handlers (Static Demo)
  document.getElementById('punchInBtn').addEventListener('click', function() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('en-US', { 
      hour12: false,
      hour: '2-digit',
      minute: '2-digit'
    });
    
    this.innerHTML = `
      <i class="fas fa-check mr-2"></i>
      <div>Punched In</div>
      <small class="d-block">${timeString}</small>
    `;
    this.classList.remove('btn-success');
    this.classList.add('btn-outline-success');
    
    document.getElementById('punchOutBtn').disabled = false;
    document.getElementById('todayIn').textContent = timeString;
    document.getElementById('todayHours').textContent = '0.0';
  });

  document.getElementById('punchOutBtn').addEventListener('click', function() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('en-US', { 
      hour12: false,
      hour: '2-digit',
      minute: '2-digit'
    });
    
    this.innerHTML = `
      <i class="fas fa-check mr-2"></i>
      <div>Punched Out</div>
      <small class="d-block">${timeString}</small>
    `;
    this.classList.remove('btn-danger');
    this.classList.add('btn-outline-danger');
    
    document.getElementById('todayOut').textContent = timeString;
    document.getElementById('todayHours').textContent = '8.5'; // Static demo value
  });

  // Enable buttons for demo (in real app, this would be based on conditions)
  document.getElementById('punchInBtn').disabled = false;
});
</script>

<style>
.box-icon {
  width: 60px;
  height: 60px;
  fill: rgba(255, 255, 255, 0.8);
}

.infobox-icon {
  position: absolute;
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
  opacity: 0.8;
}

.small-box {
  position: relative;
  overflow: hidden;
}

.small-box .inner {
  min-height: 120px;
}

#progressChart {
  display: block;
  margin: 0 auto;
}

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.card {
  box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
  border: 1px solid rgba(0, 0, 0, 0.125);
}

.small-box {
  transition: transform 0.2s;
}

.small-box:hover {
  transform: translateY(-2px);
}
</style>
@endsection