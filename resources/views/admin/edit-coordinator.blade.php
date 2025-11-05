@extends('layouts.admin')

@section('title', 'Edit Coordinator')

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="page-header">EDIT COORDINATOR</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item fw-medium">Admin</li>
          <li class="breadcrumb-item"><a href="{{ route('admin.coordinators') }}">Coordinators</a></li>
          <li class="breadcrumb-item active text-muted">Edit</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Edit Coordinator</h3>
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

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form id="coordinatorForm" method="POST" action="{{ route('admin.coordinators.update', $coordinator->id) }}">
            @csrf
            @method('PUT')
            
            <!-- Row 1: First Name (col-6), Last Name (col-6) -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="fname">First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="fname" name="fname" 
                            value="{{ old('fname', $coordinator->user->fname) }}"
                            placeholder="Enter first name" required>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="lname">Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="lname" name="lname" 
                            value="{{ old('lname', $coordinator->user->lname) }}"
                            placeholder="Enter last name" required>
                    </div>
                </div>
            </div>

            <!-- Row 2: Email (col-6), Contact (col-6) -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" 
                            value="{{ old('email', $coordinator->user->email) }}"
                            placeholder="juan.delacruz@evsu.edu.ph" required readonly>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="contact">Contact Number <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control" id="contact" name="contact" 
                            value="{{ old('contact', $coordinator->user->contact) }}"
                            placeholder="09123456789" required pattern="[0-9]{11}">
                    </div>
                </div>
            </div>

            <!-- Row 3: Faculty ID (col-3), HTE Privilege (col-3), Department (col-6) -->
            <div class="row mt-3">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="faculty_id">Faculty ID <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="faculty_id" name="faculty_id" 
                            value="{{ old('faculty_id', $coordinator->faculty_id) }}"
                            placeholder="Enter Employee ID" required
                            pattern="[A-Za-z]\d{2}\d{2}\d{2}[A-Za-z][A-Za-z]"
                            maxlength="9">
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="can_add_hte">HTE Privilege <span class="text-danger">*</span></label>
                        <select class="form-control" id="can_add_hte" name="can_add_hte" required>
                            <option value="0" {{ old('can_add_hte', $coordinator->can_add_hte) == 0 ? 'selected' : '' }}>Not Allowed</option>
                            <option value="1" {{ old('can_add_hte', $coordinator->can_add_hte) == 1 ? 'selected' : '' }}>Allowed</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="dept_id">Department <span class="text-danger">*</span></label>
                        <select class="form-control" id="dept_id" name="dept_id" required>
                            <option value="">Select Department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->dept_id }}" 
                                    {{ old('dept_id', $coordinator->dept_id) == $department->dept_id ? 'selected' : '' }}>
                                    {{ $department->dept_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Current Status Display -->
            <!-- <div class="row mt-3">
                <div class="col-md-12">
                    <div class="alert alert-info">
                        <strong>Current Status:</strong> 
                        <span class="badge 
                            @if($coordinator->status === 'pending documents') bg-warning
                            @elseif($coordinator->status === 'for validation') bg-info
                            @elseif($coordinator->status === 'eligible for claim') bg-success
                            @elseif($coordinator->status === 'claimed') bg-secondary
                            @endif">
                            {{ ucfirst($coordinator->status) }}
                        </span>
                        <br>
                        <small class="text-muted">
                            Status can only be changed from the coordinator's documents page.
                        </small>
                    </div>
                </div>
            </div> -->

            <!-- Footer with buttons -->
            <div class="card-footer d-flex justify-content-between">
                <div>
                    <button type="button" class="btn btn-secondary mr-2" onclick="window.history.back()">
                        Cancel
                    </button>
                    <button type="reset" class="btn btn-outline-secondary">
                        Reset
                    </button>
                </div>
                <button type="submit" class="ml-auto btn btn-primary">
                    Save Changes
                </button>
            </div>
        </form>

      </div>
    </div>
  </div>
</section>

@include('layouts.partials.scripts-main')

<script>
// Optional: Add real-time validation and formatting for Faculty ID
document.getElementById('faculty_id').addEventListener('input', function(e) {
    let value = e.target.value.toUpperCase();
    
    // Remove any non-alphanumeric characters
    value = value.replace(/[^A-Z0-9]/g, '');
    
    // Limit to 9 characters
    if (value.length > 9) {
        value = value.substring(0, 9);
    }
    
    // Auto-format as user types (optional visual aid)
    if (value.length >= 1) {
        // First character should be letter
        if (!/^[A-Z]/.test(value.charAt(0))) {
            value = value.substring(1);
        }
    }
    
    if (value.length >= 3) {
        // Next 2 characters should be numbers (month)
        let monthPart = value.substring(1, 3);
        if (!/^\d{2}$/.test(monthPart)) {
            value = value.substring(0, 1) + monthPart.replace(/\D/g, '') + value.substring(3);
        }
    }
    
    if (value.length >= 5) {
        // Next 2 characters should be numbers (date)
        let datePart = value.substring(3, 5);
        if (!/^\d{2}$/.test(datePart)) {
            value = value.substring(0, 3) + datePart.replace(/\D/g, '') + value.substring(5);
        }
    }
    
    if (value.length >= 7) {
        // Next 2 characters should be numbers (year)
        let yearPart = value.substring(5, 7);
        if (!/^\d{2}$/.test(yearPart)) {
            value = value.substring(0, 5) + yearPart.replace(/\D/g, '') + value.substring(7);
        }
    }
    
    if (value.length >= 8) {
        // Next character should be letter (last initial)
        let lastInitial = value.substring(7, 8);
        if (!/^[A-Z]$/.test(lastInitial)) {
            value = value.substring(0, 7) + lastInitial.replace(/[^A-Z]/g, '') + value.substring(8);
        }
    }
    
    if (value.length >= 9) {
        // Last character should be letter (middle initial)
        let middleInitial = value.substring(8, 9);
        if (!/^[A-Z]$/.test(middleInitial)) {
            value = value.substring(0, 8) + middleInitial.replace(/[^A-Z]/g, '');
        }
    }
    
    e.target.value = value;
});
</script>
@endsection