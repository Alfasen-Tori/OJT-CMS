{{-- resources/views/coordinator/htes-edit.blade.php --}}
@extends('layouts.coordinator')

@section('title', 'Edit HTE')

@section('content')
<section class="content-header">
@include('layouts.partials.scripts-main')

  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-8">
        <h1 class="page-header">EDIT HTE</h1>
      </div>
      <div class="col-sm-4">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item fw-medium">Coordinator</li>
          <li class="breadcrumb-item"><a href="{{ route('coordinator.htes') }}">HTEs</a></li>
          <li class="breadcrumb-item active text-muted">Edit HTE</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Host Training Establishment</h3>
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
                
                <form action="{{ route('coordinator.update_h', $hte->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="coordinator_id" value="{{ auth()->user()->coordinator->id }}">

                    <!-- CONTACT PERSON INFORMATION -->
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="contact_first_name" class="form-label">First Name*</label>
                            <input type="text" class="form-control" id="contact_first_name" name="contact_first_name" 
                                value="{{ old('contact_first_name', $hte->user->fname) }}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="contact_last_name" class="form-label">Last Name*</label>
                            <input type="text" class="form-control" id="contact_last_name" name="contact_last_name" 
                                value="{{ old('contact_last_name', $hte->user->lname) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="contact_email" class="form-label">Email*</label>
                            <input type="email" class="form-control" id="contact_email" name="contact_email" 
                                value="{{ old('contact_email', $hte->user->email) }}" required>
                        </div>
                    </div>
                    
                    <!-- CONTACT DETAILS -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contact_number" class="form-label">Contact Number*</label>
                            <input type="text" class="form-control" id="contact_number" name="contact_number" 
                                value="{{ old('contact_number', $hte->user->contact) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="address" class="form-label">Address*</label>
                            <input type="text" class="form-control" id="address" name="address" 
                                value="{{ old('address', $hte->address) }}" required>
                        </div>
                    </div>
                    
                    <!-- ORGANIZATION INFORMATION -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="organization_name" class="form-label">Organization Name*</label>
                            <input type="text" class="form-control" id="organization_name" name="organization_name" 
                                value="{{ old('organization_name', $hte->organization_name) }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="organization_type" class="form-label">Organization Type*</label>
                            <select class="form-select" id="organization_type" name="organization_type" required>
                                <option value="" disabled>Select Type</option>
                                <option value="private" {{ old('organization_type', $hte->type) == 'private' ? 'selected' : '' }}>Private</option>
                                <option value="government" {{ old('organization_type', $hte->type) == 'government' ? 'selected' : '' }}>Government</option>
                                <option value="ngo" {{ old('organization_type', $hte->type) == 'ngo' ? 'selected' : '' }}>NGO</option>
                                <option value="educational" {{ old('organization_type', $hte->type) == 'educational' ? 'selected' : '' }}>Educational</option>
                                <option value="other" {{ old('organization_type', $hte->type) == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <!-- <div class="col-md-3 mb-3">
                            <label for="hte_status" class="form-label">Status*</label>
                            <select class="form-select" id="hte_status" name="hte_status" required>
                                <option value="" disabled>Select Status</option>
                                <option value="active" {{ old('hte_status', $hte->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="new" {{ old('hte_status', $hte->status) == 'new' ? 'selected' : '' }}>New</option>
                            </select>
                        </div> -->
                    </div>
                    
                    <!-- DESCRIPTION -->
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $hte->description) }}</textarea>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="card-footer d-flex justify-content-between">
                        <div>
                            <a href="{{ route('coordinator.htes') }}" class="btn btn-secondary mr-2">
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