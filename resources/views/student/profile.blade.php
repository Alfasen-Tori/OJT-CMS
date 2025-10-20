@extends('layouts.intern')

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
                    <li class="breadcrumb-item fw-medium">Intern</li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid px-0 px-sm-2">
        <div class="card shadow-sm ">
            <div class="card-body">
                <!-- Profile Picture Upload -->
                <div class="text-center mb-5">
                    <div class="position-relative d-inline-block">
                        <img id="profileImage" 
                            src="{{ auth()->user()->pic ? asset('storage/' . auth()->user()->pic) : asset('profile_pics/profile.jpg') }}" 
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
                <form action="{{ route('intern.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
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

                        <div class="col-md-6 mb-3">
                            <label for="birthdate" class="form-label">Birthdate*</label>
                            <input type="date" class="form-control" id="birthdate" name="birthdate" 
                                   value="{{ auth()->user()->intern->birthdate }}" disabled>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email*</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ auth()->user()->email }}" disabled>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="contact" class="form-label">Contact Number*</label>
                            <input type="text" class="form-control" id="contact" name="contact" 
                                   value="{{ auth()->user()->contact }}" required>
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

                    <!-- Student Info (Display Only) -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Student ID</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->intern->student_id }}" disabled>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Department</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->intern->department->short_name }}" disabled>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Section</label>
                            <input type="text" class="form-control" value="{{ strtoupper(auth()->user()->intern->section) }}" disabled>
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

                    <!-- Skills Section -->
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">My Skills</h5>
                            <button type="button" class="btn btn-sm btn-outline-primary ml-auto" data-toggle="modal" data-target="#skillsModal">
                                <i class="ph-fill ph-gear-six custom-icons-i mr-1"></i>Manage Skills
                            </button>
                        </div>
                        <div class="card-body">
                            @if(auth()->user()->intern->skills->count() > 0)
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach(auth()->user()->intern->skills as $skill)
                                        <span class="badge bg-secondary-subtle text-secondary py-2 px-3 rounded-pill">
                                            <i class="fas fa-check-circle mr-1"></i> {{ $skill->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-warning mb-0">
                                    <i class="fas fa-exclamation-circle mr-2"></i> No skills selected yet
                                </div>
                            @endif
                        </div>
                    </div>
    </div>
</section>

<!-- Skills Modal -->
<div class="modal fade" id="skillsModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update My Skills</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('intern.skills.update') }}">
                @csrf
                @method('PUT')
                
                <div class="modal-body p-0">
                    <div class="container-fluid px-0">
                        <div class="row mx-0 pt-3" style="max-height: 60vh; overflow-y: auto;">
                            @foreach($skills as $skill)
                            <div class="col-12 px-3">
                                <label class="d-flex align-items-center p-4 m-0 mb-3 rounded-3 border hover-bg-light cursor-pointer">
                                    <input type="checkbox" name="skills[]" value="{{ $skill->skill_id }}" 
                                        class="mr-3 h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500"
                                        {{ auth()->user()->intern->skills->contains($skill->skill_id) ? 'checked' : '' }}>
                                    <span class="text-gray-700 font-medium flex-grow-1">
                                        {{ $skill->name }}
                                    </span>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="ph-fill ph-floppy-disk-back custom-icons-i mr-1"></i> Save Skills
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')

@endpush