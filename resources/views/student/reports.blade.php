{{-- resources/views/student/reports.blade.php --}}
@extends('layouts.intern')

@section('title', 'Intern | Weekly Reports')

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="page-header">WEEKLY JOURNALS</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item fw-medium">Intern</li>
          <li class="breadcrumb-item active text-muted">Reports</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
    @if(isset($error))
      <div class="alert alert-warning text-center">
        {{ $error }}
      </div>
    @else
      <!-- Internship Info Card -->
      <!-- <div class="card mb-4">
        <div class="card-body">
          <div class="row">
            <div class="col-12 col-md-6">
              <h6 class="card-title text-muted mb-1">Internship Period</h6>
              <p class="card-text mb-2">
                {{ \Carbon\Carbon::parse($internship->start_date)->format('M d, Y') }} - 
                {{ \Carbon\Carbon::parse($internship->end_date)->format('M d, Y') }}
              </p>
            </div>
            <div class="col-12 col-md-6">
              <h6 class="card-title text-muted mb-1">Total Hours</h6>
              <p class="card-text mb-0">{{ $internship->no_of_hours }} hours</p>
            </div>
          </div>
        </div>
      </div> -->

      <!-- Weekly Reports Section -->
      <div class="row">
        <div class="col-12">          
          @if($weeklyReports->isEmpty())
            <div class="alert alert-info text-center">
              <i class="fas fa-info-circle mr-2"></i>
              No weekly reports available yet. Reports will appear as your internship progresses.
            </div>
          @else
            <div class="row">
              @foreach($weeklyReports as $report)
                @php
                  $weekData = $weekInfo[$report->week_no] ?? [];
                  $status = $weekData['status'] ?? 'upcoming';
                @endphp
                
                <div class="col-12 col-sm-6 col-lg-4 mb-3">
                  <div class="card report-card 
                    @if($status === 'submitted') border-success 
                    @elseif($status === 'pending') border-warning 
                    @elseif($status === 'current') border-primary 
                    @else border-secondary @endif">
                    
                    <div class="card-body pb-1">
                      <!-- Week Header -->
                      <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="card-title mb-0">Week {{ $report->week_no }}</h6>
                        <span class="badge rounded-pill
                          @if($status === 'submitted') bg-success text-success 
                          @elseif($status === 'pending') bg-danger-subtle text-danger 
                          @elseif($status === 'current') bg-info-subtle text-info 
                          @else bg-secondary-subtle text-secondary @endif">
                          
                          @if($status === 'submitted') Submitted
                          @elseif($status === 'pending') Missing
                          @elseif($status === 'current') Ongoing
                          @else Upcoming
                          @endif
                        </span>
                      </div>

                      <!-- Week Dates -->
                      <p class="card-text small text-muted mb-2">
                        {{ $weekData['start_date'] ?? 'TBD' }} - {{ $weekData['end_date'] ?? 'TBD' }}
                      </p>

                      <!-- Action Buttons -->
                      <div class="mt-3">
                        @if($status === 'submitted')
                          <button class="btn btn-outline-success btn-sm btn-block py-2" disabled>
                            <i class="fas fa-check mr-1"></i> Report Submitted
                          </button>
                          <small class="text-muted d-block mt-1">
                            Submitted: {{ $report->submitted_at ? \Carbon\Carbon::parse($report->submitted_at)->format('M d, Y') : 'N/A' }}
                          </small>
                        @elseif($status === 'pending')
                          <button class="btn btn-primary btn-sm btn-block upload-report-btn py-2" 
                                  data-week="{{ $report->week_no }}"
                                  data-dates="{{ $weekData['start_date'] ?? '' }} - {{ $weekData['end_date'] ?? '' }}">
                            <i class="ph-fill ph-upload custom-icons-i mr-1"></i> Upload Journal
                          </button>
                        @elseif($status === 'current')
                          <button class="btn btn-primary btn-sm btn-block py-2" disabled>
                            <i class="ph-fill ph-clock custom-icons-i mr-1"></i> Opens Saturday
                          </button>
                        @else
                          <button class="btn btn-secondary btn-sm btn-block py-2" disabled>
                            <i class="ph-fill ph-calendar custom-icons-i mr-1"></i> Opens Next Week
                          </button>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          @endif
        </div>
      </div>
    @endif
  </div>
</section>

<!-- Upload Report Modal (Desktop) -->
<div class="modal fade" id="uploadReportModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Upload Weekly Report</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="alert bg-primary-subtle mb-3">
          <strong>Week <span id="modalWeekNumber"></span></strong><br>
          <small id="modalWeekDates"></small>
        </div>
        
        <form id="uploadReportForm" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="week_no" id="week_no">

          <div class="form-group">
            <label for="report_file" class="font-weight-bold">Select PDF File</label>
            <div class="custom-file">
              <input 
                type="file" 
                class="custom-file-input" 
                id="report_file" 
                name="report_file" 
                accept=".pdf" 
                required
              >
              <label class="custom-file-label" for="report_file">Choose file...</label>
            </div>
            <small class="form-text text-muted mt-1">
              Maximum file size: 5MB.
            </small>
          </div>
        </form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="submitReportBtn">Submit</button>
      </div>
    </div>
  </div>
</div>

<!-- Custom Offcanvas for Mobile -->
<div class="offcanvas-backdrop" id="offcanvasBackdrop"></div>
<div class="offcanvas" id="uploadReportOffcanvas">
  <div class="offcanvas-header d-flex align-items-center">
    <h5 class="offcanvas-title">Upload Weekly Report</h5>
    <!-- <button type="button" class="close ml-auto" onclick="closeOffcanvas()">
      <span>&times;</span>
    </button> -->
  </div>
  <div class="offcanvas-body">
    <div class="alert bg-primary-subtle mb-3">
      <strong>Week <span id="offcanvasWeekNumber"></span></strong><br>
      <small id="offcanvasWeekDates"></small>
    </div>
    
    <form id="uploadReportFormMobile" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="week_no_mobile" id="week_no_mobile" required>

      <div class="form-group">
        <label for="report_file_mobile" class="font-weight-bold">Select PDF File</label>
        <div class="custom-file">
          <input 
            type="file" 
            class="custom-file-input" 
            id="report_file_mobile" 
            name="report_file_mobile" 
            accept=".pdf" 
            required
          >
          <label class="custom-file-label" for="report_file_mobile">Choose file...</label>
        </div>
        <small class="form-text text-muted mt-1">
         Maximum file size: 5MB.
        </small>
      </div>
    </form>

    <script>
      // Update file label text on file select (Bootstrap 4)
      document.querySelector('.custom-file-input#report_file_mobile')
        .addEventListener('change', function (e) {
          const fileName = e.target.files[0]?.name || 'Choose file...';
          e.target.nextElementSibling.textContent = fileName;
        });
    </script>

  </div>
  <div class="offcanvas-footer">
    <button type="button" class="btn btn-secondary btn-block mb-2" onclick="closeOffcanvas()">Cancel</button>
    <button type="button" class="btn btn-primary btn-block" id="submitReportBtnMobile">Submit</button>
  </div>
</div>

<style>
.report-card {
  transition: transform 0.2s ease-in-out;
  height: 100%;
}

.report-card:hover {
  transform: translateY(-2px);
}

/* Custom Offcanvas Styles */
.offcanvas-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 1040;
  opacity: 0;
  visibility: hidden;
  transition: opacity 0.3s ease;
}

.offcanvas-backdrop.show {
  opacity: 1;
  visibility: visible;
}

.offcanvas {
  position: fixed;
  bottom: 0;
  left: 0;
  width: 100%;
  background: white;
  border-radius: 1rem 1rem 0 0;
  z-index: 1050;
  transform: translateY(100%);
  transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  max-height: 85vh;
  display: flex;
  flex-direction: column;
  box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.15);
}

.offcanvas.show {
  transform: translateY(0);
}

.offcanvas-header {
  padding: 1.25rem 1.25rem 0.5rem;
  border-bottom: 1px solid #dee2e6;
  flex-shrink: 0;
}

.offcanvas-header .close {
  font-size: 1.5rem;
  line-height: 1;
}

.offcanvas-title {
  margin-bottom: 0;
  font-weight: 600;
}

.offcanvas-body {
  padding: 1.25rem;
  flex: 1;
  overflow-y: auto;
}

.offcanvas-footer {
  padding: 1rem 1.25rem;
  border-top: 1px solid #dee2e6;
  flex-shrink: 0;
}

/* Mobile-specific improvements */
@media (max-width: 768px) {
  .page-header {
    font-size: 1.5rem;
  }
  
  .btn-block {
    padding: 0.75rem 1rem;
  }
  
  /* Prevent body scroll when offcanvas is open */
  body.offcanvas-open {
    overflow: hidden;
  }
}

/* Desktop - hide offcanvas */
@media (min-width: 769px) {
  .offcanvas,
  .offcanvas-backdrop {
    display: none !important;
  }
}

/* Smooth animations */
.offcanvas-body,
.offcanvas-header,
.offcanvas-footer {
  animation: slideUpContent 0.3s ease-out 0.1s both;
}

@keyframes slideUpContent {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>

<script>
function isMobile() {
  return window.innerWidth <= 768;
}

function openOffcanvas() {
  const offcanvas = document.getElementById('uploadReportOffcanvas');
  const backdrop = document.getElementById('offcanvasBackdrop');
  const body = document.body;
  
  offcanvas.classList.add('show');
  backdrop.classList.add('show');
  body.classList.add('offcanvas-open');
  
  // Prevent background scroll
  document.body.style.overflow = 'hidden';
}

function closeOffcanvas() {
  const offcanvas = document.getElementById('uploadReportOffcanvas');
  const backdrop = document.getElementById('offcanvasBackdrop');
  const body = document.body;
  
  offcanvas.classList.remove('show');
  backdrop.classList.remove('show');
  body.classList.remove('offcanvas-open');
  
  // Restore background scroll
  document.body.style.overflow = '';
}

// Close offcanvas when clicking backdrop
document.getElementById('offcanvasBackdrop').addEventListener('click', closeOffcanvas);

// Handle upload report button click
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.upload-report-btn').forEach(button => {
    button.addEventListener('click', function() {
      const weekNo = this.getAttribute('data-week');
      const weekDates = this.getAttribute('data-dates');
      
      // Update week information
      document.getElementById('modalWeekNumber').textContent = weekNo;
      document.getElementById('modalWeekDates').textContent = weekDates;
      document.getElementById('offcanvasWeekNumber').textContent = weekNo;
      document.getElementById('offcanvasWeekDates').textContent = weekDates;
      
      document.getElementById('week_no').value = weekNo;
      document.getElementById('week_no_mobile').value = weekNo;
      
      if (isMobile()) {
        // Use custom offcanvas for mobile
        openOffcanvas();
      } else {
        // Use regular modal for desktop
        $('#uploadReportModal').modal('show');
      }
    });
  });

  // Placeholder for upload functionality
  document.getElementById('submitReportBtn')?.addEventListener('click', function() {
    alert('Upload functionality will be implemented in the next phase.');
  });

  document.getElementById('submitReportBtnMobile')?.addEventListener('click', function() {
    alert('Upload functionality will be implemented in the next phase.');
    closeOffcanvas();
  });

  // Close offcanvas with escape key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closeOffcanvas();
    }
  });
});
</script>
@endsection