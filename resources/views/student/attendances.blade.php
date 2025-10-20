{{-- resources/views/intern/attendances.blade.php --}}
@extends('layouts.intern')

@section('title', 'Intern | Attendances')

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="page-header">DAILY TIME RECORD</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item fw-medium">Intern</li>
          <li class="breadcrumb-item active text-muted">Attendances</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
    @if(isset($error))
      <div class="d-flex align-items-center justify-content-center text-muted" style="height: 70vh">
        {{ $error }}
      </div>
    @else
      <!-- Progress Header Card -->
      <div class="card mb-4">
        <div class="card-body py-3">
          <div class="row align-items-center">
            <!-- Total Hours Rendered -->
            <div class="col-12 col-md-3 text-center text-md-left mb-2 mb-md-0">
              <div class="d-flex flex-column">
                <span class="text-muted small">Hours Rendered</span>
                <span class="h4 mb-0 text-primary font-weight-bold">{{ number_format($totalHoursRendered, 1) }}h</span>
              </div>
            </div>
            
            <!-- Progress Bar -->
            <div class="col-12 col-md-6 mb-2 mb-md-0">
              <div class="d-flex flex-column">
                <div class="d-flex justify-content-between mb-1">
                  <span class="text-muted small">Internship Progress</span>
                  <span class="text-muted small">{{ number_format($progressPercentage, 1) }}%</span>
                </div>
                <div class="progress" style="height: 12px; border-radius: 6px;">
                  <div class="progress-bar 
                    @if($progressPercentage >= 100) bg-success
                    @elseif($progressPercentage >= 75) bg-primary
                    @elseif($progressPercentage >= 50) bg-info
                    @elseif($progressPercentage >= 25) bg-warning
                    @else bg-danger
                    @endif"
                    role="progressbar" 
                    style="width: {{ $progressPercentage }}%; border-radius: 6px;
                    aria-valuenow="{{ $progressPercentage }}" 
                    aria-valuemin="0" 
                    aria-valuemax="100">
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Hours Required -->
            <div class="col-12 col-md-3 text-center text-md-right">
              <div class="d-flex flex-column">
                <span class="text-muted small">Hours Required</span>
                <span class="h4 mb-0 text-dark font-weight-bold">{{ $hoursRequired }}h</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Attendances Table -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Attendance History</h3>
        </div>
        <div class="card-body table-responsive py-0 px-3">
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
            <span class="text-primary">Loading attendances . . .</span>
          </div>
          
          <table id="internAttendanceTable" class="table table-bordered mb-0">
            <thead class="table-light">
              <tr>
                <th width="15%">Date</th>
                <th width="20%">Time In</th>
                <th width="20%">Time Out</th>
                <th width="15%">Hours Rendered</th>
                <th width="30%">Day</th>
              </tr>
            </thead>
            <tbody>
              @forelse($attendances as $attendance)
              @php
                  $date = \Carbon\Carbon::parse($attendance->date);
                  $timeIn = $attendance->time_in ? \Carbon\Carbon::parse($attendance->time_in) : null;
                  $timeOut = $attendance->time_out ? \Carbon\Carbon::parse($attendance->time_out) : null;
                  
                  // Determine status color
                  $statusClass = 'text-success';
                  if (!$attendance->time_out) {
                      $statusClass = 'text-warning';
                  } elseif ($attendance->hours_rendered < 8) {
                      $statusClass = 'text-danger';
                  }
              @endphp
              <tr>
                <td class="align-middle">
                  <strong>{{ $date->format('M d, Y') }}</strong>
                </td>
                <td class="align-middle">
                  @if($timeIn)
                    <span class="text-dark">{{ $timeIn->format('g:i A') }}</span>
                  @else
                    <span class="text-muted">-</span>
                  @endif
                </td>
                <td class="align-middle">
                  @if($timeOut)
                    <span class="text-dark">{{ $timeOut->format('g:i A') }}</span>
                  @else
                    <span class="text-warning">No time out</span>
                  @endif
                </td>
                <td class="align-middle {{ $statusClass }} font-weight-bold">
                  @if($attendance->hours_rendered)
                    {{ number_format($attendance->hours_rendered, 2) }}h
                  @else
                    <span class="text-muted">-</span>
                  @endif
                </td>
                <td class="align-middle text-muted">
                  {{ $date->format('l') }}
                  @if($date->isToday())
                    <span class="badge badge-info ml-2">Today</span>
                  @elseif($date->isYesterday())
                    <span class="badge badge-secondary ml-2">Yesterday</span>
                  @endif
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="5" class="text-center py-4">
                  <div class="d-flex flex-column align-items-center text-muted">
                    <i class="ph ph-clock fs-1 mb-2"></i>
                    <span>No attendance records found.</span>
                    <small class="mt-1">Your attendance records will appear here once you start logging in.</small>
                  </div>
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    @endif
  </div>
</section>
@endsection