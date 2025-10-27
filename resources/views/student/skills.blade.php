<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skill Selection - OJT-CMS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/colors.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts.css') }}">

    <style>
        .ph-fill {
            position: relative;
            top: 2px;
            font-size: 1.3rem;
        }
    </style>
</head>

<body class="bg-light mt-4">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <!-- Header Section -->
                <div class="text-center mb-4">
                    <h2 class="h3 text-primary mb-2">Select Your Skills</h2>
                    <p class="text-muted">Choose at least 3 skills relevant to your program</p>
                </div>

                <!-- Main Card -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white py-3 text-center">
                        <h5 class="card-title mb-0">
                            <i class="ph-fill ph-certificate me-2"></i>Skill Selection
                        </h5>
                    </div>
                    
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('intern.skills.store') }}" id="skillsForm">
                            @csrf
                            
                            <!-- Skills Selection Area -->
                            <div class="skills-container mb-4" style="max-height: 300px; overflow-y: auto;">
                                @foreach($skills as $skill)
                                <div class="skill-item p-3 border rounded mb-2">
                                    <div class="form-check mb-0">
                                        <input type="checkbox" 
                                               name="skills[]" 
                                               value="{{ $skill->skill_id }}"
                                               class="form-check-input skill-checkbox"
                                               id="skill_{{ $skill->skill_id }}">
                                        <label class="form-check-label fw-medium w-100" for="skill_{{ $skill->skill_id }}">
                                            {{ $skill->name }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            
                            <!-- Selection Counter -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Minimum 3 skills required
                                </small>
                                <span class="badge bg-light text-dark border">
                                    <i class="fas fa-check text-primary me-1"></i>
                                    Selected: <span id="selectedCount" class="fw-bold">0</span>/3
                                </span>
                            </div>
                            
                            <!-- Submit Button -->
                            <button type="submit" id="submitBtn" class="btn btn-secondary w-100 py-2" disabled>
                                <i class="fas fa-save me-2"></i>Save Skills
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

    #submitBtn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.skill-checkbox');
        const selectedCount = document.getElementById('selectedCount');
        const submitBtn = document.getElementById('submitBtn');
        const skillsForm = document.getElementById('skillsForm');
        
        function updateSelectionCount() {
            const selected = document.querySelectorAll('.skill-checkbox:checked').length;
            selectedCount.textContent = selected;
            
            // Enable/disable submit button based on minimum requirement
            submitBtn.disabled = selected < 3;
            
            // Update button appearance and text
            if (submitBtn.disabled) {
                submitBtn.classList.remove('btn-success');
                submitBtn.classList.add('btn-secondary');
                const remaining = 3 - selected;
                submitBtn.innerHTML = `<i class="ph-fill ph-warning-circle me-2"></i>Select ${remaining} more skill${remaining !== 1 ? 's' : ''}`;
            } else {
                submitBtn.classList.remove('btn-secondary');
                submitBtn.classList.add('btn-success');
                submitBtn.innerHTML = `<i class="ph-fill ph-floppy-disk-back me-2"></i>Save Skills (${selected})`;
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
        
        // Form validation
        skillsForm.addEventListener('submit', function(e) {
            const selected = document.querySelectorAll('.skill-checkbox:checked').length;
            if (selected < 3) {
                e.preventDefault();
                alert(`Please select at least 3 skills (${selected}/3 selected)`);
            }
        });
        
        // Initialize count and add event listeners
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectionCount);
        });
        
        // Set initial state
        updateSelectionCount();
    });
    </script>

    <!-- Phosphour Icons -->
    <script src="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2"></script>
</body>
</html>