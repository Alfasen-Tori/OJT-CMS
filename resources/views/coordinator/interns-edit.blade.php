{{-- resources/views/coordinator/interns-edit.blade.php --}}
@extends('layouts.coordinator')

@section('title', 'Edit Intern')

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="page-header">EDIT INTERN</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item fw-medium">Coordinator</li>
          <li class="breadcrumb-item"><a href="{{ route('coordinator.interns') }}">Interns</a></li>
          <li class="breadcrumb-item active text-muted">Edit Intern</li>
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
            
            <form action="{{ route('coordinator.update_i', $intern->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="coordinator_id" value="{{ auth()->user()->coordinator->id }}">

                <!-- PERSONAL INFORMATION -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label">First Name*</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" 
                                    value="{{ old('first_name', $intern->user->fname) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="form-label">Last Name*</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" 
                                    value="{{ old('last_name', $intern->user->lname) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="birthdate" class="form-label">Birthdate*</label>
                        <input type="date" class="form-control" id="birthdate" name="birthdate" 
                            value="{{ old('birthdate', $intern->birthdate) }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="sex" class="form-label">Sex*</label>
                        <select class="form-select" id="sex" name="sex" required>
                            <option value="male" {{ old('sex', $intern->user->sex) == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('sex', $intern->user->sex) == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                </div>
                
                <!-- CONTACT INFORMATION -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email*</label>
                        <input type="email" class="form-control" id="email" name="email" 
                            value="{{ old('email', $intern->user->email) }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="contact" class="form-label">Contact Number*</label>
                        <input type="text" class="form-control" id="contact" name="contact" 
                            value="{{ old('contact', $intern->user->contact) }}" required>
                    </div>
                </div>
                
                <!-- STUDENT INFORMATION -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="student_id" class="form-label">Student ID*</label>
                        <input type="text" class="form-control" id="student_id" name="student_id" 
                            pattern="\d{4}-\d{5}" title="Format: XXXX-XXXXX (e.g. 2022-09709)" 
                            value="{{ old('student_id', $intern->student_id) }}" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="academic_year" class="form-label">Academic Year*</label>
                        <input type="text" class="form-control" id="academic_year" name="academic_year" 
                            pattern="\d{4}-\d{4}" title="Format: XXXX-XXXX (e.g. 2024-2025)" 
                            value="{{ old('academic_year', $intern->academic_year) }}" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="semester" class="form-label">Semester*</label>
                        <select class="form-select" id="semester" name="semester" required>
                            <option value="" disabled>Select Semester</option>
                            <option value="1st" {{ old('semester', $intern->semester) == '1st' ? 'selected' : '' }}>1st Semester</option>
                            <option value="2nd" {{ old('semester', $intern->semester) == '2nd' ? 'selected' : '' }}>2nd Semester</option>
                            <option value="midyear" {{ old('semester', $intern->semester) == 'midyear' ? 'selected' : '' }}>Mid-Year</option>
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
                        <label for="year_level" class="form-label">Year Level*</label>
                        <select class="form-select" id="year_level" name="year_level" required>
                            <option value="" disabled>Select Year</option>
                            <option value="1" {{ old('year_level', $intern->year_level) == 1 ? 'selected' : '' }}>1st Year</option>
                            <option value="2" {{ old('year_level', $intern->year_level) == 2 ? 'selected' : '' }}>2nd Year</option>
                            <option value="3" {{ old('year_level', $intern->year_level) == 3 ? 'selected' : '' }}>3rd Year</option>
                            <option value="4" {{ old('year_level', $intern->year_level) == 4 ? 'selected' : '' }}>4th Year</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="section" class="form-label">Section*</label>
                        <select class="form-select" id="section" name="section" required>
                            <option value="" disabled>Select Section</option>
                            <option value="a" {{ old('section', $intern->section) == 'a' ? 'selected' : '' }}>A</option>
                            <option value="b" {{ old('section', $intern->section) == 'b' ? 'selected' : '' }}>B</option>
                            <option value="c" {{ old('section', $intern->section) == 'c' ? 'selected' : '' }}>C</option>
                            <option value="d" {{ old('section', $intern->section) == 'd' ? 'selected' : '' }}>D</option>
                            <option value="e" {{ old('section', $intern->section) == 'e' ? 'selected' : '' }}>E</option>
                            <option value="f" {{ old('section', $intern->section) == 'f' ? 'selected' : '' }}>F</option>
                        </select>
                    </div>
                </div>

                <!-- Footer -->
                <div class="card-footer d-flex justify-content-between">
                    <div>
                        <a href="{{ route('coordinator.interns') }}" class="btn btn-secondary mr-2">
                            Cancel
                        </a>
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
@endsection