{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.coordinator')

@section('title', 'New Intern')

@section('content')
<section class="content-header">
    @include('layouts.partials.scripts-main')

  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="page-header">INTERN REGISTRATION</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item fw-medium">Coordinator</li>
          <li class="breadcrumb-item active text-muted">New Intern</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card">
        <div class="card-header">
            <h3 class="card-title">Student Information</h3>
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
            <form action="{{ route('coordinator.register_i') }}" method="POST">
                @csrf
                <input type="hidden" name="coordinator_id" value="{{ auth()->user()->coordinator->id }}">

                <!-- PERSONAL INFORMATION -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label">First Name<span class="text-danger"> *</span></label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="form-label">Last Name<span class="text-danger"> *</span></label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="birthdate" class="form-label">Birthdate<span class="text-danger"> *</span></label>
                        <input type="date" class="form-control" id="birthdate" name="birthdate" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="sex" class="form-label">Sex<span class="text-danger"> *</span></label>
                        <select class="form-select" id="sex" name="sex" required>
                            <option class="d-none" value="" disabled selected>Select Sex</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                </div>
                
                <!-- CONTACT INFORMATION -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email<span class="text-danger"> *</span></label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="contact" class="form-label">Contact Number<span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="contact" name="contact" required>
                    </div>
                </div>
                
                <!-- STUDENT INFORMATION -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="student_id" class="form-label">Student ID<span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="student_id" name="student_id" 
                            pattern="\d{4}-\d{5}" title="Format: XXXX-XXXXX (e.g. 2022-09709)" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="academic_year" class="form-label">Academic Year<span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="academic_year" name="academic_year" 
                            pattern="\d{4}-\d{4}" title="Format: XXXX-XXXX (e.g. 2024-2025)" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="semester" class="form-label">Semester<span class="text-danger"> *</span></label>
                        <select class="form-select" id="semester" name="semester" required>
                            <option value="" disabled selected>Select Semester</option>
                            <option value="1st">1st Semester</option>
                            <option value="2nd">2nd Semester</option>
                            <option value="midyear">Mid-Year</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="department" class="form-label">Program</label>
                        <input type="text" class="form-control" 
                            value="BS {{ auth()->user()->coordinator->department->dept_name ?? 'Department not found' }}" 
                            disabled>
                        <input type="hidden" name="dept_id" 
                            value="{{ auth()->user()->coordinator->dept_id }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="year_level" class="form-label">Year Level<span class="text-danger"> *</span></label>
                        <select class="form-select" id="year_level" name="year_level" required>
                            <option value="" disabled selected>Select Year</option>
                            <option value="1">1st Year</option>
                            <option value="2">2nd Year</option>
                            <option value="3">3rd Year</option>
                            <option value="4">4th Year</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="section" class="form-label">Section<span class="text-danger"> *</span></label>
                        <select class="form-select" id="section" name="section" required>
                            <option value="" disabled selected>Select Section</option>
                            <option value="a">A</option>
                            <option value="b">B</option>
                            <option value="c">C</option>
                            <option value="d">D</option>
                            <option value="e">E</option>
                            <option value="f">F</option>
                        </select>
                    </div>
                </div>

                <!-- Footer -->
                <div class="card-footer d-flex justify-content-between py-3 rounded-3">
                    <div>
                        <button type="button" class="btn btn-secondary mr-2" onclick="window.history.back()">
                            Cancel
                        </button>
                        <button type="reset" class="btn btn-outline-secondary">
                            Reset
                        </button>
                    </div>
                    <button type="submit" class="ml-auto btn btn-primary">
                        Register Intern
                    </button>
                </div>
            </form>
        </div>

        </div>
    </div>
</section>

@endsection
