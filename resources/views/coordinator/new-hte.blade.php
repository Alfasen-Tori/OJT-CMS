{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.coordinator')

@section('title', 'Register HTE')

@section('content')
<section class="content-header">
    @include('layouts.partials.scripts-main')

  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-8">
        <h1 class="page-header">HTE REGISTRATION</h1>
      </div>
      <div class="col-sm-4">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item fw-medium">Coordinator</li>
          <li class="breadcrumb-item active text-muted">Register HTE</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Register Host Training Establishment</h3>
            </div>

            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{route('coordinator.register_h')}}" method="POST" enctype="multipart/form-data" id="hteRegistrationForm">
                    @csrf
                    <input type="hidden" name="coordinator_id" value="{{ auth()->user()->coordinator->id }}">

                    <!-- CONTACT PERSON INFORMATION -->
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="contact_first_name" class="form-label">First Name<span class="text-danger"> *</span></label>
                            <input type="text" class="form-control" id="contact_first_name" name="contact_first_name" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="contact_last_name" class="form-label">Last Name<span class="text-danger"> *</span></label>
                            <input type="text" class="form-control" id="contact_last_name" name="contact_last_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="contact_email" class="form-label">Email<span class="text-danger"> *</span></label>
                            <input type="email" class="form-control" id="contact_email" name="contact_email" required>
                        </div>
                    </div>
                    
                    <!-- CONTACT DETAILS -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contact_number" class="form-label">Contact Number<span class="text-danger"> *</span></label>
                            <input type="text" class="form-control" id="contact_number" name="contact_number" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="address" class="form-label">Address<span class="text-danger"> *</span></label>
                            <input type="text" class="form-control" id="address" name="address" required>
                        </div>
                    </div>
                    
                    <!-- ORGANIZATION INFORMATION -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="organization_name" class="form-label">Organization Name<span class="text-danger"> *</span></label>
                            <input type="text" class="form-control" id="organization_name" name="organization_name" required>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="organization_type" class="form-label">Organization Type<span class="text-danger"> *</span></label>
                            <select class="form-select" id="organization_type" name="organization_type" required>
                                <option value="" disabled selected>Select Type</option>
                                <option value="private">Private</option>
                                <option value="government">Government</option>
                                <option value="ngo">NGO</option>
                                <option value="educational">Educational</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="status" class="form-label">Status<span class="text-danger"> *</span></label>
                            <select class="form-select" id="hte_status" name="hte_status" required>
                                <option value="" disabled selected>Select Status</option>
                                <option value="active">Active</option>
                                <option value="new">New</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- DESCRIPTION -->
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                        </div>
                    </div>

                    <!-- STUDENT INTERNSHIP PLAN UPLOAD -->
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="internship_plan" class="form-label">Student Internship Plan<span class="text-danger"> *</span></label>
                            <input type="file" class="form-control" id="internship_plan" name="internship_plan" accept=".pdf" required>
                            <small class="form-text text-muted">Upload the Student Internship Plan in PDF format. This will be attached to the HTE's account setup email.</small>
                        </div>
                    </div>

                    <!-- MOA UPLOAD (Conditional for Active HTEs) -->
                    <div class="row" id="moaUploadSection" style="display: none;">
                        <div class="col-md-12 mb-3">
                            <label for="moa_document" class="form-label">Memorandum of Agreement (MOA)<span class="text-danger"> *</span></label>
                            <input type="file" class="form-control" id="moa_document" name="moa_document" accept=".pdf">
                            <small class="form-text text-muted">Upload the signed MOA document in PDF format. Required for active HTEs.</small>
                        </div>
                    </div>
                    
                    <!-- Footer -->
                    <div class="card-footer d-flex justify-content-between py-3 rounded-3">
                        <div>
                            <button type="button" class="btn btn-secondary mr-2" onclick="window.history.back()">
                                Cancel
                            </button>
                            <button type="reset" class="btn btn-outline-secondary" id="resetButton">
                                Reset
                            </button>
                        </div>
                        <button type="submit" class="ml-auto btn btn-primary" id="submitButton">
                            Register HTE
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const hteStatusSelect = document.getElementById('hte_status');
    const moaUploadSection = document.getElementById('moaUploadSection');
    const moaDocumentInput = document.getElementById('moa_document');
    const internshipPlanInput = document.getElementById('internship_plan');
    const submitButton = document.getElementById('submitButton');
    const resetButton = document.getElementById('resetButton');
    const form = document.getElementById('hteRegistrationForm');

    // Toggle MOA upload section based on HTE status
    hteStatusSelect.addEventListener('change', function() {
        if (this.value === 'active') {
            moaUploadSection.style.display = 'block';
            moaDocumentInput.setAttribute('required', 'required');
        } else {
            moaUploadSection.style.display = 'none';
            moaDocumentInput.removeAttribute('required');
            moaDocumentInput.value = ''; // Clear the file input
        }
        validateForm();
    });

    // Form validation
    function validateForm() {
        const isActive = hteStatusSelect.value === 'active';
        const hasInternshipPlan = internshipPlanInput.files.length > 0;
        const hasMoa = !isActive || (isActive && moaDocumentInput.files.length > 0);
        
        submitButton.disabled = !hasInternshipPlan || !hasMoa;
    }

    // Event listeners for file inputs
    internshipPlanInput.addEventListener('change', validateForm);
    moaDocumentInput.addEventListener('change', validateForm);

    // Reset button functionality
    resetButton.addEventListener('click', function() {
        moaUploadSection.style.display = 'none';
        moaDocumentInput.removeAttribute('required');
        setTimeout(validateForm, 100); // Validate after reset
    });

    // Form submission validation
    form.addEventListener('submit', function(e) {
        const isActive = hteStatusSelect.value === 'active';
        const hasInternshipPlan = internshipPlanInput.files.length > 0;
        const hasMoa = !isActive || (isActive && moaDocumentInput.files.length > 0);

        if (!hasInternshipPlan || !hasMoa) {
            e.preventDefault();
            alert('Please complete all required file uploads before submitting.');
            return false;
        }

        // Validate file types
        const internshipPlanFile = internshipPlanInput.files[0];
        if (internshipPlanFile && internshipPlanFile.type !== 'application/pdf') {
            e.preventDefault();
            alert('Student Internship Plan must be a PDF file.');
            return false;
        }

        if (isActive) {
            const moaFile = moaDocumentInput.files[0];
            if (moaFile && moaFile.type !== 'application/pdf') {
                e.preventDefault();
                alert('MOA document must be a PDF file.');
                return false;
            }
        }

        // Show loading state
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Registering HTE...';
    });

    // Initial validation
    validateForm();
});
</script>

@endsection