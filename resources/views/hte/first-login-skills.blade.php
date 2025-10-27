@extends('layouts.plain')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h3 mb-1 text-primary">Select Required Skills</h2>
                    <p class="text-muted mb-0">Choose the skills you require from interns</p>
                </div>
                <div class="badge bg-light text-dark fs-6 px-3 py-2 border">
                    <i class="fas fa-check-circle text-primary me-1"></i>
                    Selected: <span id="selectedCount" class="fw-bold">0</span>/5 minimum
                </div>
            </div>

            <!-- Main Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="alert bg-info-subtle text-info border-0 mb-0 py-2">
                        <i class="ph-fill ph-info me-2"></i>
                        Choose at least 5 domains you offer to your interns. 
                    </div>
                </div>
                
                <div class="card-body p-0">
                    <form id="skillsForm" method="POST" action="{{ route('hte.save-skills') }}">
                        @csrf
                        @method('POST')
                        
                        <div class="skills-container" style="max-height: 60vh; overflow-y: auto;">
                            @foreach($departments as $department)
                                @if($department->skills->count() > 0)
                                    <div class="department-group p-4 border-bottom">
                                        <h5 class="mb-3 text-primary">
                                            <i class="ph-fill ph-graduation-cap custom-icons-i fs-3 me-2"></i>
                                            {{ $department->dept_name }} 
                                            <small class="text-muted">({{ $department->short_name }})</small>
                                        </h5>
                                        
                                        <div class="row g-3">
                                            @foreach($department->skills as $skill)
                                                <div class="col-md-6 col-lg-4">
                                                    <div class="skill-item p-3 border rounded h-100">
                                                        <div class="form-check mb-0">
                                                            <input type="checkbox" 
                                                                   name="skills[]" 
                                                                   value="{{ $skill->skill_id }}"
                                                                   class="form-check-input skill-checkbox"
                                                                   id="skill_{{ $skill->skill_id }}"
                                                                   {{ in_array($skill->skill_id, $selectedSkills) ? 'checked' : '' }}>
                                                            <label class="form-check-label fw-medium w-100" for="skill_{{ $skill->skill_id }}">
                                                                {{ $skill->name }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        
                        <div class="card-footer bg-white py-3 border-top-0">
                            <div class="text-end">
                                <button type="submit" id="submitBtn" class="btn btn-secondary px-4" disabled>
                                    <i class="ph-fill ph-floppy-disk-back custom-icons-i me-2"></i>Save Skills
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
.skill-item {
    transition: all 0.2s ease;
    cursor: pointer;
}

.skill-item:hover {
    background-color: #f8f9fa;
    border-color: #0d6efd !important;
}

.skill-item .form-check-input:checked ~ .form-check-label {
    color: #0d6efd;
    font-weight: 600;
}

.skills-container::-webkit-scrollbar {
    width: 6px;
}

.skills-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.skills-container::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.skills-container::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.skill-checkbox');
    const selectedCount = document.getElementById('selectedCount');
    const submitBtn = document.getElementById('submitBtn');
    
    function updateSelectionCount() {
        const selected = document.querySelectorAll('.skill-checkbox:checked').length;
        selectedCount.textContent = selected;
        
        // Enable/disable submit button based on minimum requirement
        submitBtn.disabled = selected < 5;
        
        // Update button appearance
        if (submitBtn.disabled) {
            submitBtn.classList.remove('btn-success');
            submitBtn.classList.add('btn-secondary');
        } else {
            submitBtn.classList.remove('btn-secondary');
            submitBtn.classList.add('btn-success');
        }
    }
    
    // Add click event to entire skill item for better UX
    document.querySelectorAll('.skill-item').forEach(item => {
        item.addEventListener('click', function(e) {
            if (e.target.type !== 'checkbox') {
                const checkbox = this.querySelector('.skill-checkbox');
                checkbox.checked = !checkbox.checked;
                checkbox.dispatchEvent(new Event('change'));
            }
        });
    });
    
    // Initialize count and add event listeners
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectionCount);
    });
    
    updateSelectionCount();
});
</script>
@endsection