@extends('layouts.plain')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h3 mb-1 text-primary">Verify Organization Details</h2>
                    <p class="text-muted mb-0">Review and confirm your organization information</p>
                </div>
                @auth
                <div>
                    <a href="{{ route('logout') }}" class="btn btn-outline-danger btn-sm" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="ph ph-sign-out custom-icons-i me-1"></i>Sign out
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
                @endauth
            </div>

            <!-- Main Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex align-items-center">
                        <i class="ph-fill ph-building-apartment me-2 fs-5"></i>
                        <h5 class="card-title mb-0 fw-semibold">Organization Information</h5>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('hte.confirm-details') }}">
                        @csrf
                        @method('PUT')

                        <!-- Contact Information -->
                        <div class="mb-4">
                            <h6 class="text-primary mb-3 border-bottom pb-2">
                                Contact Information
                            </h6>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" 
                                           name="contact_first_name" value="{{ $hte->user->fname }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" 
                                           name="contact_last_name" value="{{ $hte->user->lname }}" required>
                                </div>
                            </div>

                            <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Contact Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" 
                                           name="contact_number" value="{{ $hte->user->contact }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Email</label>
                                    <input type="email" class="form-control" 
                                           value="{{ $hte->user->email }}" disabled>
                                    <div class="form-text">Email cannot be modified</div>
                                </div>
                            </div>
                        </div>

                        <!-- Organization Details -->
                        <div class="mb-4">
                            <h6 class="text-primary mb-3 border-bottom pb-2">
                                Organization Details
                            </h6>

                            <div class="mb-3">
                                <label class="form-label fw-medium">Organization Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" 
                                       name="organization_name" value="{{ $hte->organization_name }}" required>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Organization Type <span class="text-danger">*</span></label>
                                    <select class="form-select" name="organization_type" required>
                                        @foreach(['private', 'government', 'ngo', 'educational', 'other'] as $type)
                                            <option value="{{ $type }}" {{ $hte->type == $type ? 'selected' : '' }}>
                                                {{ ucfirst($type) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Available Slots <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" 
                                           name="slots" value="{{ $hte->slots }}" min="1" required>
                                </div>
                            </div>

                            <div class="mt-3">
                                <label class="form-label fw-medium">Organization Address <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" 
                                       name="address" value="{{ $hte->address }}" required>
                            </div>

                            <div class="mt-3">
                                <label class="form-label fw-medium">Description</label>
                                <textarea class="form-control" name="description" rows="3" 
                                          placeholder="Brief description of your organization...">{{ $hte->description }}</textarea>
                                <div class="form-text">Optional: Provide additional information about your organization</div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                All fields marked with <span class="text-danger">*</span> are required
                            </small>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-check-circle me-2"></i>
                                Confirm & Continue
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- SSL Secured Card -->
            <div class="card border mt-4">
                <div class="card-body py-3">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                <i class="ph-fill ph-shield-checkered text-success fs-3 me-3"></i>
                                <div>
                                    <h6 class="mb-1 fw-medium">Your Information is Secure</h6>
                                    <p class="text-muted mb-0 small">
                                        We protect your data with industry-standard security measures.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <span class="badge bg-success-subtle text-success bg-opacity-10 text-success border border-success border-opacity-25">
                                <i class="ph-fill ph-lock-key custom-icons-i fs-6 me-1"></i>SSL Secured
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* Minimal custom CSS for Bootstrap compatibility */
.card-header.bg-primary {
    border-radius: 0 !important;
}

.btn-primary {
    font-weight: 500;
}

.form-label.fw-medium {
    font-weight: 500;
}
</style>
@endsection