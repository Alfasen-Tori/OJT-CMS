{{-- resources/views/student/reports.blade.php --}}
@extends('layouts.intern')

@section('title', 'Intern | Weekly Journal')

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="page-header">WEEKLY JOURNAL</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item fw-medium">Intern</li>
          <li class="breadcrumb-item active text-muted">Journal</li>
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
      <!-- Success/Error Messages -->
      <div id="alertContainer"></div>

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
                          @if($status === 'submitted') bg-success-subtle text-success 
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
                          <div class="d-flex gap-1">
                            <button class="btn btn-outline-primary btn-sm flex-fill preview-report-btn" 
                                    data-report-id="{{ $report->id }}"
                                    data-week="{{ $report->week_no }}">
                              <i class="ph-fill ph-eye custom-icons-i mr-1"></i> View
                            </button>
                            <button class="btn btn-outline-danger btn-sm delete-report-btn"
                                    data-report-id="{{ $report->id }}"
                                    data-week="{{ $report->week_no }}">
                              <i class="ph-bold ph-prohibit-inset custom-icons-i"></i>
                            </button>
                          </div>
                          <small class="text-muted d-block mt-1">
                            Submitted: {{ $report->submitted_at ? \Carbon\Carbon::parse($report->submitted_at)->format('M d, Y') : 'N/A' }}
                          </small>
                        @elseif($status === 'pending')
                          <button class="btn btn-success btn-sm btn-block upload-report-btn py-2" 
                                  data-week="{{ $report->week_no }}"
                                  data-dates="{{ $weekData['start_date'] ?? '' }} - {{ $weekData['end_date'] ?? '' }}">
                            <i class="ph-fill ph-upload custom-icons-i mr-1"></i> Upload Journal
                          </button>
                        @elseif($status === 'current')
                          <button class="btn btn-success btn-sm btn-block py-2" disabled>
                            <i class="ph-fill ph-clock custom-icons-i mr-1"></i> Opens on Saturday
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
        <h5 class="modal-title">Upload Weekly Journal</h5>
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
            <div class="mb-3">
                <label for="report_file" class="fw-bold">Select PDF File</label>
                <input 
                    type="file" 
                    class="form-control" 
                    id="report_file" 
                    name="report_file" 
                    accept=".pdf" 
                    required
                >
                <div class="form-text mt-1">
                    Maximum file size: 5MB.
                </div>
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

<!-- Preview Report Modal -->
<div class="modal fade" id="previewReportModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Weekly Journal - Week <span id="previewWeekNumber"></span></h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="previewContainer" class="text-center">
          <iframe id="pdfPreview" src="" width="100%" height="500px" style="border: none;"></iframe>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger" id="deleteReportBtn">Delete Journal</button>
      </div>
    </div>
  </div>
</div>

<!-- Custom Offcanvas for Mobile -->
<div class="offcanvas-backdrop" id="offcanvasBackdrop"></div>
<div class="offcanvas" id="uploadReportOffcanvas">
  <div class="offcanvas-header d-flex align-items-center">
    <h5 class="offcanvas-title">Upload Weekly Journal</h5>
  </div>
  <div class="offcanvas-body">
    <div class="alert bg-primary-subtle mb-3">
      <strong>Week <span id="offcanvasWeekNumber"></span></strong><br>
      <small id="offcanvasWeekDates"></small>
    </div>
    
    <form id="uploadReportFormMobile" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="week_no" id="week_no_mobile">

      <div class="form-group">
        <label for="report_file_mobile" class="font-weight-bold">Select PDF File</label>
        <input 
            type="file" 
            class="form-control" 
            id="report_file_mobile" 
            name="report_file" 
            accept=".pdf" 
            required
        >
        <small class="form-text text-muted mt-1">
         Maximum file size: 5MB.
        </small>
      </div>
    </form>
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
  border-radius: 0.7rem 0.7rem 0 0;
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
  padding: 1rem 1.25rem 0.5rem;
  border-bottom: 1px solid #dee2e6;
  flex-shrink: 0;
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
  
  body.offcanvas-open {
    overflow: hidden;
  }
}

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
    document.body.style.overflow = 'hidden';
}

function closeOffcanvas() {
    const offcanvas = document.getElementById('uploadReportOffcanvas');
    const backdrop = document.getElementById('offcanvasBackdrop');
    const body = document.body;
    
    offcanvas.classList.remove('show');
    backdrop.classList.remove('show');
    body.classList.remove('offcanvas-open');
    document.body.style.overflow = '';
}

function updateFileLabel(inputElement) {
    const fileName = inputElement.files[0]?.name || 'Choose file...';
    inputElement.nextElementSibling.textContent = fileName;
}

// Show toast notification
function showToast(message, type = 'success') {
    const toastType = type === 'success' ? 'success' : 'error';
    toastr[toastType](message);
}

document.addEventListener('DOMContentLoaded', function() {
    let currentReportId = null;
    let currentWeekNumber = null;

    // File input handlers
    document.getElementById('report_file')?.addEventListener('change', function() {
        updateFileLabel(this);
    });
    
    document.getElementById('report_file_mobile')?.addEventListener('change', function() {
        updateFileLabel(this);
    });

    // Upload report button handlers
    document.querySelectorAll('.upload-report-btn').forEach(button => {
        button.addEventListener('click', function() {
            const weekNo = this.getAttribute('data-week');
            const weekDates = this.getAttribute('data-dates');
            
            document.getElementById('modalWeekNumber').textContent = weekNo;
            document.getElementById('modalWeekDates').textContent = weekDates;
            document.getElementById('offcanvasWeekNumber').textContent = weekNo;
            document.getElementById('offcanvasWeekDates').textContent = weekDates;
            
            document.getElementById('week_no').value = weekNo;
            document.getElementById('week_no_mobile').value = weekNo;
            
            // Reset forms
            document.getElementById('uploadReportForm').reset();
            document.getElementById('uploadReportFormMobile').reset();
            updateFileLabel(document.getElementById('report_file'));
            updateFileLabel(document.getElementById('report_file_mobile'));
            
            if (isMobile()) {
                openOffcanvas();
            } else {
                $('#uploadReportModal').modal('show');
            }
        });
    });

    // Preview report button handlers
    document.querySelectorAll('.preview-report-btn').forEach(button => {
        button.addEventListener('click', function() {
            currentReportId = this.getAttribute('data-report-id');
            currentWeekNumber = this.getAttribute('data-week');
            
            document.getElementById('previewWeekNumber').textContent = currentWeekNumber;
            document.getElementById('pdfPreview').src = "{{ route('intern.weekly-reports.preview', '') }}/" + currentReportId;
            
            $('#previewReportModal').modal('show');
        });
    });

    // Delete report button handlers (card buttons)
    document.querySelectorAll('.delete-report-btn').forEach(button => {
        button.addEventListener('click', function() {
            const reportId = this.getAttribute('data-report-id');
            const weekNo = this.getAttribute('data-week');
            
            if (confirm(`Are you sure you want to delete your Week ${weekNo} journal?`)) {
                deleteReport(reportId);
            }
        });
    });

    // Delete button in preview modal
    document.getElementById('deleteReportBtn')?.addEventListener('click', function() {
        if (currentReportId && confirm(`Are you sure you want to delete your Week ${currentWeekNumber} journal?`)) {
            deleteReport(currentReportId);
            $('#previewReportModal').modal('hide');
        }
    });

    // UPLOAD - SIMPLIFIED VERSION
    document.getElementById('submitReportBtn')?.addEventListener('click', function() {
        handleUpload('desktop');
    });

    document.getElementById('submitReportBtnMobile')?.addEventListener('click', function() {
        handleUpload('mobile');
    });

    // Close offcanvas handlers
    document.getElementById('offcanvasBackdrop').addEventListener('click', closeOffcanvas);
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeOffcanvas();
        }
    });

    // SIMPLIFIED UPLOAD FUNCTION - ONE FUNCTION FOR BOTH
    function handleUpload(platform) {
        console.log('Upload started for platform:', platform);
        
        let weekNo, fileInput, submitBtn;
        
        if (platform === 'desktop') {
            weekNo = document.getElementById('week_no').value;
            fileInput = document.getElementById('report_file');
            submitBtn = document.getElementById('submitReportBtn');
        } else {
            weekNo = document.getElementById('week_no_mobile').value;
            fileInput = document.getElementById('report_file_mobile');
            submitBtn = document.getElementById('submitReportBtnMobile');
        }

        console.log('Week No:', weekNo);
        console.log('File:', fileInput.files[0]);

        // Validate file
        if (!fileInput.files[0]) {
            showToast('Please select a PDF file.', 'error');
            return;
        }

        // Validate file size (5MB)
        if (fileInput.files[0].size > 5 * 1024 * 1024) {
            showToast('File size must be less than 5MB.', 'error');
            return;
        }

        // Validate file type
        const fileExtension = fileInput.files[0].name.split('.').pop().toLowerCase();
        if (fileExtension !== 'pdf') {
            showToast('Only PDF files are allowed.', 'error');
            return;
        }

        // Create FormData - SIMPLE AND CLEAN
        const formData = new FormData();
        formData.append('week_no', weekNo);
        formData.append('report_file', fileInput.files[0]);
        formData.append('_token', "{{ csrf_token() }}");

        console.log('FormData created, sending request...');

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="ph-bold ph-circle-notch custom-icons-i spin mr-1"></i> Uploading...';

        // SIMPLE FETCH REQUEST
        fetch("{{ route('intern.weekly-reports.upload') }}", {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Upload response data:', data);
            if (data.success) {
                showToast(data.message, 'success');
                
                // Close modals/offcanvas
                if (platform === 'desktop') {
                    $('#uploadReportModal').modal('hide');
                } else {
                    closeOffcanvas();
                }
                
                // Refresh the page after a short delay
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Upload error details:', error);
            showToast('Error uploading file. Please check console for details and try again.', 'error');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Submit';
        });
    }

    // Delete report function
    function deleteReport(reportId) {
        const deleteButtons = document.querySelectorAll(`.delete-report-btn[data-report-id="${reportId}"]`);
        deleteButtons.forEach(btn => {
            btn.disabled = true;
            btn.innerHTML = '<i class="ph-bold ph-circle-notch custom-icons-i spin"></i>';
        });

        fetch("{{ route('intern.weekly-reports.delete', '') }}/" + reportId, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Delete error:', error);
            showToast('Error deleting journal. Please try again.', 'error');
        })
        .finally(() => {
            deleteButtons.forEach(btn => {
                btn.disabled = false;
                btn.innerHTML = '<i class="ph-bold ph-prohibit-inset custom-icons-i"></i>';
            });
        });
    }

    // Add spin class for loading animations
    const style = document.createElement('style');
    style.textContent = `
        .spin {
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(style);
});
</script>
@endsection