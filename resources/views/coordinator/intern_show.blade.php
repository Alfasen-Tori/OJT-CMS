@extends('layouts.coordinator')

@section('title', 'Intern Details')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-white text-dark">
                    <h3 class="card-title mb-0 d-flex justify-content-between align-items-center w-100">
                        <div>
                            <i class="ph ph-graduation-cap details-icons-i mr-2"></i>
                            Intern Details
                        </div>
                        <div class="title-right">
                            <a href="{{ route('coordinator.edit_i', $intern->id) }}" class="btn btn-outline-light border-0 rounded-4 text-muted"><i class="ph ph-wrench details-icons-i p-0"></i></a>
                            <a class="btn btn-outline-light border-0 rounded-4 text-muted" data-toggle="modal" data-target="#removeModal"><i class="ph ph-trash details-icons-i p-0"></i></a>
                        </div>

                    </h3>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <!-- Intern Profile Section -->
                        <div class="col-md-4 d-flex flex-column">
                            <div class="text-center mb-4">
                                <img src="{{ asset('storage/' . $intern->user->pic) }}" 
                                     class="img-thumbnail rounded-circle" 
                                     style="width: 200px; height: 200px; object-fit: cover;"
                                     alt="Intern Profile Picture">
                            </div>
                            
                            <div class="border p-3 rounded bg-light flex-grow-1 mt-0">
                                <h5 class="mb-3"><i class="ph-fill ph-info details-icons-i mr-2"></i>Academic Information</h5>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <strong>Status:</strong> 
                                        <span class="badge py-2 px-3 rounded-pill status-badge bg-{{ 
                                            $intern->status == 'endorsed' ? 'primary-subtle text-primary' : 
                                            ($intern->status == 'ready for deployment' ? 'warning-subtle text-warning' : 
                                            ($intern->status == 'pending requirements' ? 'danger-subtle text-danger' :
                                            ($intern->status == 'processing' ? 'info-subtle text-info' :
                                            ($intern->status == 'deployed' ? 'success-subtle text-success' : 'secondary'))))
                                        }}">
                                            {{ ucfirst($intern->status) }}
                                        </span>
                                    </li>
                                    <li class="mb-2"><strong>Student ID:</strong> {{ $intern->student_id }}</li>
                                    <li class="mb-2"><strong>Program:</strong> {{ $intern->department->dept_name }}</li>
                                    <li class="mb-2"><strong>Year Level:</strong> {{ $intern->year_level }}</li>
                                    <li class="mb-2"><strong>Section:</strong> {{ strtoupper($intern->section) }}</li>
                                    <li class="mb-2"><strong>Academic Year:</strong> {{ $intern->academic_year }}</li>
                                    <li class="mb-2"><strong>Semester:</strong> {{ $intern->semester }}</li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Contact & Details Section -->
                        <div class="col-md-8">
                            <div class="border p-3 rounded my-3 mt-lg-0 bg-light">
                                <h5 class="mb-3"><i class="ph-fill ph-person details-icons-i mr-2"></i>Personal Information</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Full Name:</strong><br>
                                        {{ $intern->user->fname }} {{ $intern->user->lname }}</p>
                                        
                                        <p><strong>Birthdate:</strong><br>
                                        {{ $intern->birthdate ? \Carbon\Carbon::parse($intern->birthdate)->format('F j, Y') : 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Age:</strong><br>
                                        {{ $intern->birthdate ? \Carbon\Carbon::parse($intern->birthdate)->age . ' years old' : 'N/A' }}</p>

                                        <p><strong>Sex:</strong><br>
                                        <span style="text-transform: capitalize;">{{ $intern->user->sex }}</span></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="border p-3 rounded my-3 bg-light">
                                <h5 class="mb-3"><i class="ph-fill ph-identification-card details-icons-i mr-2"></i>Contact Information</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Email:</strong><br>
                                        {{ $intern->user->email }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Contact Number:</strong><br>
                                        {{ $intern->user->contact ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Skills Section -->
                            <div class="border p-3 rounded mb-3 bg-light">
                                <h5 class="mb-3"><i class="ph-fill ph-certificate details-icons-i mr-2"></i>Skills</h5>
                                @if($intern->skills->count() > 0)
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($intern->skills as $skill)
                                            <span class="badge bg-primary-subtle text-dark py-2 px-3 rounded-pill">{{ $skill->name }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted">No skills recorded</p>
                                @endif
                            </div>
                            
                            <!-- Coordinator Info -->
                            <div class="border p-3 rounded bg-light">
                                <h5 class="mb-3"><i class="ph-fill ph-chalkboard-teacher details-icons-i mr-2"></i>Coordinator</h5>
                                @if($intern->coordinator)
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <img src="{{ asset('storage/' . $intern->coordinator->user->pic) }}" 
                                                 class="img-thumbnail rounded-circle mr-3" 
                                                 style="width: 50px; height: 50px; object-fit: cover;"
                                                 alt="Coordinator Profile Picture">
                                        </div>
                                        <div class="flex-grow-1 gap-1">
                                            <h6 class="mb-0">{{ $intern->coordinator->user->fname }} {{ $intern->coordinator->user->lname }}</h6>
                                            <p class="mb-0 text-muted small m-0">College of {{ $intern->coordinator->department->college->name }} • {{ $intern->coordinator->department->dept_name }} Department</p>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-muted">No coordinator assigned</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <!-- <div class="row mt-4">
                        <div class="col-md-12 text-right bg-light p-3 rounded">
                            <a href="{{ route('coordinator.edit_i', $intern->id) }}" class="btn btn-primary fw-medium mr-2">
                               <span class="text-white"><i class="ph-fill ph-wrench custom-icons-i mr-2"></i></i>Update Info</span> 
                            </a>
                            <button class="btn btn-danger fw-medium" data-toggle="modal" data-target="#removeModal">
                                <i class="ph-fill ph-trash custom-icons-i mr-2"></i>Unregister Intern
                            </button>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
    

</div>

<!-- Remove Modal -->
<div class="modal fade" id="removeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light text-white">
                <h5 class="modal-title">
                    <i class="ph-bold ph-warning details-icons-i mr-1"></i>
                    Confirm Account Deletion
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to unregister <strong>{{ $intern->user->fname }} {{ $intern->user->lname }}</strong>? This action cannot be undone.</p>
                <p class="text-danger small"><strong>WARNING:</strong> All associated internship records will also be removed.</p>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form action="{{ route('coordinator.intern.destroy', $intern->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger fw-medium">Unregister</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection