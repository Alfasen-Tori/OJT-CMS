@extends('layouts.coordinator')

@section('title', 'My Profile')

@section('content')
<section class="content-header">
    <div class="container-fluid px-0 px-sm-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="page-header">PROFILE MANAGEMENT</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item fw-medium">Coordinator</li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid px-0 px-sm-2">
        <div class="card shadow-sm ">
            <div class="card-header"><span class="fw-medium text-primary"><i class="ph ph-user custom-icons-i me-2"></i>Personal Information</span></div>
            <div class="card-body">
                <!-- Profile Picture Upload -->
                <div class="text-center mb-5">
                    <div class="position-relative d-inline-block">
                        <img id="profileImage" 
                            src="{{ auth()->user()->pic ? asset('storage/'.auth()->user()->pic) : asset('profile_pics/profile.jpg') }}" 
                            class="rounded-circle shadow" 
                            width="150" 
                            height="150" 
                            alt="Profile Picture">
                        <label for="profileUpload" class="btn btn-sm btn-primary rounded-circle position-absolute bottom-0 end-0">
                            <i class="fas fa-camera"></i>
                            <input id="profileUpload" type="file" accept="image/*" class="d-none">
                        </label>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">JPG, PNG (Max 2MB)</small>
                    </div>
                </div>

                <!-- Account Information Form -->
                <form action="{{ route('coordinator.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="fname" class="form-label">First Name*</label>
                                    <input type="text" class="form-control" id="fname" name="fname" 
                                           value="{{ auth()->user()->fname }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="lname" class="form-label">Last Name*</label>
                                    <input type="text" class="form-control" id="lname" name="lname" 
                                           value="{{ auth()->user()->lname }}" required>
                                </div>
                            </div>
                        </div>

                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contact" class="form-label">Contact Number*</label>
                            <input type="text" class="form-control" id="contact" name="contact" 
                                   value="{{ auth()->user()->contact }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email*</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ auth()->user()->email }}" disabled>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <small class="text-muted">Leave blank to keep current password</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>
                    </div>

                    <!-- Coordinator Info (Display Only) -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Faculty ID</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->coordinator->faculty_id }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Department</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->coordinator->department->dept_name ?? 'N/A' }}" disabled>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-end bg-white px-0">
                        <button type="submit" class="btn btn-primary">
                            <i class="ph-fill ph-floppy-disk-back custom-icons-i mr-1"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
