@extends('layouts.hte')

@section('title', 'My Profile')

@section('content')

<style>
.skill-modal-item {
    transition: all 0.2s ease;
}

.skill-modal-item:hover {
    background-color: #f8f9fa;
}

.cursor-pointer {
    cursor: pointer;
}
</style>

<section class="content-header">
    <div class="container-fluid px-0 px-sm-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="page-header">PROFILE MANAGEMENT</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item fw-medium">HTE</li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid px-0 px-sm-2">
        <div class="card shadow-sm ">
            <div class="card-header"><span class="fw-medium text-primary"><i class="ph ph-building-apartment custom-icons-i me-2"></i>Account Details</span></div>
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
                <form action="{{ route('hte.profile.update') }}" method="POST">
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
                            <label for="email" class="form-label">Email*</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ auth()->user()->email }}" disabled>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contact" class="form-label">Contact Number*</label>
                            <input type="text" class="form-control" id="contact" name="contact" 
                                   value="{{ auth()->user()->contact }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="organization_name" class="form-label">Organization Name*</label>
                            <input type="text" class="form-control" id="organization_name" name="organization_name" 
                                   value="{{ auth()->user()->hte->organization_name }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="address" class="form-label">Address*</label>
                            <input type="text" class="form-control" id="address" name="address" 
                                   value="{{ auth()->user()->hte->address }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="organization_type" class="form-label">Organization Type*</label>
                            <select class="form-select" id="organization_type" name="organization_type" disabled>
                                <option value="private" {{ auth()->user()->hte->type == 'private' ? 'selected' : '' }}>Private</option>
                                <option value="government" {{ auth()->user()->hte->type == 'government' ? 'selected' : '' }}>Government</option>
                                <option value="ngo" {{ auth()->user()->hte->type == 'ngo' ? 'selected' : '' }}>NGO</option>
                                <option value="educational" {{ auth()->user()->hte->type == 'educational' ? 'selected' : '' }}>Educational</option>
                                <option value="other" {{ auth()->user()->hte->type == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" placeholder="Tell us more about your organization..." rows="3">{{ auth()->user()->hte->description }}</textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <small class="text-muted">Leave blank to keep current password</small>
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>
                    </div>

                    <!-- HTE Info (Display Only) -->
                    <!-- <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Available Slots</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->hte->slots }}" disabled>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">MOA Status</label>
                            <input type="text" class="form-control" value="{{ auth()->user()->hte->moa_is_signed == 'yes' ? 'Signed' : 'Not Signed' }}" disabled>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <input type="text" class="form-control" value="{{ ucfirst(auth()->user()->hte->status) }}" disabled>
                        </div>
                    </div> -->

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
                <h5 class="mb-0">Required Skills</h5>
                <button type="button" class="btn btn-sm btn-outline-light text-muted border-0 ml-auto" data-toggle="modal" data-target="#skillsModal">
                    <i class="ph-fill ph-gear-six custom-icons-i mr-1"></i>Manage Skills
                </button>
            </div>
            <div class="card-body">
                @if(auth()->user()->hte->skills->count() > 0)
                    <div class="d-flex flex-wrap gap-2">
                        @foreach(auth()->user()->hte->skills as $skill)
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
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Required Skills</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('hte.skills.update') }}" id="skillsForm">
                @csrf
                @method('PUT')
                
                <div class="modal-body p-0">
                    <div class="container-fluid px-0">
                        <!-- Selection Counter -->
                        <div class="px-4 pt-3 pb-2 border-bottom bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Minimum 5 skills required
                                </small>
                                <span class="badge bg-light text-dark border">
                                    <i class="fas fa-check text-primary me-1"></i>
                                    Selected: <span id="selectedCount" class="fw-bold">0</span>/5
                                </span>
                            </div>
                        </div>

                        <!-- Skills List -->
                        <div class="row mx-0" style="max-height: 50vh; overflow-y: auto;">
                            @foreach($skills as $skill)
                            <div class="col-12 px-3">
                                <div class="skill-modal-item p-3 border-bottom cursor-pointer">
                                    <div class="form-check mb-0">
                                        <input type="checkbox" name="skills[]" value="{{ $skill->skill_id }}" 
                                            class="form-check-input skill-checkbox"
                                            id="skill_{{ $skill->skill_id }}"
                                            {{ in_array($skill->skill_id, $selectedSkills) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-medium w-100 cursor-pointer" for="skill_{{ $skill->skill_id }}">
                                            {{ $skill->name }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="submit" id="submitSkillsBtn" class="btn btn-primary" disabled>
                        <i class="ph-fill ph-floppy-disk-back custom-icons-i mr-1"></i> Save Skills
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


