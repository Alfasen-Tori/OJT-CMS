@extends('layouts.admin')

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
                    <li class="breadcrumb-item fw-medium">Admin</li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid px-0 px-sm-2">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ph ph-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ph ph-warning-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ph ph-warning-circle me-2"></i>
                Please fix the following errors:
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header">
                <span class="fw-medium text-primary">
                    <i class="ph ph-user custom-icons-i me-2"></i>Personal Information
                </span>
            </div>
            <div class="card-body">
                <!-- Profile Picture Upload -->
                <div class="text-center mb-5">
                    <div class="position-relative d-inline-block">
                        <img id="profileImage" 
                            src="{{ auth()->user()->pic ? asset('storage/'.auth()->user()->pic) : asset('profile_pics/profile.jpg') }}" 
                            class="rounded-circle shadow" 
                            width="150" 
                            height="150" 
                            alt="Profile Picture"
                            style="object-fit: cover;">
                        <label for="profileUpload" class="btn btn-sm btn-primary rounded-circle position-absolute bottom-0 end-0 shadow">
                            <i class="ph ph-camera"></i>
                            <input id="profileUpload" type="file" accept="image/*" class="d-none">
                        </label>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">JPG, PNG (Max 2MB)</small>
                    </div>
                </div>

                <!-- Account Information Form -->
                <form action="{{ route('admin.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="fname" class="form-label">First Name*</label>
                                    <input type="text" class="form-control" id="fname" name="fname" 
                                           value="{{ old('fname', auth()->user()->fname) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="lname" class="form-label">Last Name*</label>
                                    <input type="text" class="form-control" id="lname" name="lname" 
                                           value="{{ old('lname', auth()->user()->lname) }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contact" class="form-label">Contact Number*</label>
                            <input type="text" class="form-control" id="contact" name="contact" 
                                   value="{{ old('contact', auth()->user()->contact) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email*</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ auth()->user()->email }}" disabled>
                            <small class="text-muted">Email cannot be changed</small>
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

                    <!-- Admin Info (Display Only) -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Faculty ID</label>
                            <input type="text" class="form-control" value="{{ $admin->faculty_id ?? 'N/A' }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <input type="text" class="form-control" value="Administrator" disabled>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-end py-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="ph-fill ph-floppy-disk-back custom-icons-i mr-1"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@include('layouts.partials.scripts-main')

<script>
$(document).ready(function() {
    // Profile picture upload
    $('#profileUpload').change(function(e) {
        const file = e.target.files[0];
        if (!file) return;

        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB');
            return;
        }

        // Validate file type
        if (!file.type.match('image.*')) {
            alert('Please select an image file');
            return;
        }

        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            $('#profileImage').attr('src', e.target.result);
        }
        reader.readAsDataURL(file);

        // Upload to server
        const formData = new FormData();
        formData.append('profile_picture', file);
        formData.append('_token', '{{ csrf_token() }}');

        $.ajax({
            url: '{{ route("admin.profile.upload-picture") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message, 'Success');
                    // Update image URL in case it changed
                    $('#profileImage').attr('src', response.image_url + '?t=' + new Date().getTime());
                }
            },
            error: function(xhr) {
                let errorMessage = 'Error uploading profile picture';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                toastr.error(errorMessage, 'Error');
                // Revert to original image on error
                $('#profileImage').attr('src', '{{ auth()->user()->pic ? asset("storage/".auth()->user()->pic) : asset("profile_pics/profile.jpg") }}');
            }
        });
    });

    // Contact number formatting (optional)
    $('#contact').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length > 11) {
            value = value.substring(0, 11);
        }
        $(this).val(value);
    });
});
</script>

<style>
.ph {
    width: 16px;
    height: 16px;
}

.custom-icons-i {
    width: 18px;
    height: 18px;
}

.position-relative .btn {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

#profileImage {
    border: 3px solid #dee2e6;
    transition: border-color 0.3s ease;
}

#profileImage:hover {
    border-color: #007bff;
}
</style>
@endsection
