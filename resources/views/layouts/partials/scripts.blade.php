    <!-- Phosphour Icons -->
    <script src="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2"></script>

    <!-- jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>

    <!-- JQueryKnobCharts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-knob/1.2.13/jquery.knob.min.js"></script>

    <!-- Bootstrap 4 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.min.js"></script>

    <!-- DataTable JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

    <!-- AdminLTE App -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

    <!-- Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


    @if(session('success'))
    <script>
        // Only show if this isn't a back/forward navigation
        if (performance.navigation.type !== 2) {
            $(function() {
                toastr.success("{{ session('success') }}");
            });
        }
    </script>
    @endif

    @if(Session::has('error'))
        <script>
            $(document).ready(function() {
                toastr.error("{{ Session::get('error') }}");
            });
        </script>
    @endif




    <!-- Custom Scripts -->

    <!-- Filter Skills By Department -->
    <script>
    jQuery(document).ready(function($) {
        // Department filter functionality
        $('#departmentFilter').on('change', function() {
            const deptId = $(this).val();
            const $rows = $('#skillsTable tbody tr');
            
            if (deptId === '') {
                $rows.show();
            } else {
                $rows.hide().filter('[data-dept="' + deptId + '"]').show();
            }
            
            // Update row numbers for visible rows only
            updateRowNumbers();
        });
        
        // Update row numbers based on visible rows
        function updateRowNumbers() {
            let visibleIndex = 1;
            $('#skillsTable tbody tr:visible').each(function() {
                $(this).find('td:first').text(visibleIndex++);
            });
        }
        
        // Delete skill functionality
        $(document).on('click', '.delete-skill', function() {
            if (confirm('Are you sure you want to delete this skill?')) {
                const skillId = $(this).data('id');
                // Add your AJAX delete logic here
            }
        });
    });
    </script>

    <script>
    // Department deletion handling
    $(document).on('submit', '.delete-form', function(e) {
        e.preventDefault();
        
        if (confirm('Are you sure you want to delete this department? This action cannot be undone.')) {
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: $(this).find('input[name="_token"]').val()
                },
                success: function(response) {
                    location.reload();
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseJSON.message);
                }
            });
        }
    });

    // Add department form submission
    $(document).on('submit', '#addDepartmentForm', function(e) {
        e.preventDefault();
        $(this).find('button[type="submit"]').prop('disabled', true);
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                location.reload();
            },
            error: function(xhr) {
                alert('Error: ' + xhr.responseJSON.message);
                $('#addDepartmentForm').find('button[type="submit"]').prop('disabled', false);
            }
        });
    });
    </script>

    <!-- HTE: Skill Selection -->
    <script>
    $(document).ready(function() {
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        
        const updateSelectionCount = () => {
            const selected = $('.skill-checkbox:checked').length;
            $('#selectedCount').text(selected);
            $('#submitBtn').prop('disabled', selected < 5);
        };

        $('.skill-checkbox').change(updateSelectionCount);
        updateSelectionCount();

        $('#skillsForm').submit(function(e) {
            e.preventDefault();
            
            if ($('.skill-checkbox:checked').length < 5) {
                alert('Please select at least 5 skills');
                return;
            }

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: $(this).serialize(),
                success: function(response) {
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    }
                },
                error: function(xhr) {
                    // Completely commented out error handling
                    // if (xhr.status !== 419) {
                    //     toastr.error(xhr.responseJSON?.message || 'Error saving skills');
                    // }
                    
                    // Optional: You might want to keep this for debugging
                    console.log('Error occurred:', xhr.status, xhr.responseText);
                }
            });
        });
    });
    </script>

    <!-- HTE: MOA Handling -->
    <script>
    $(document).ready(function() {
        // Initialize Bootstrap custom file input
        if (typeof bsCustomFileInput !== 'undefined') {
            bsCustomFileInput.init();
        }

        // Initialize MOA handlers once
        initializeMoaHandlers();

        function initializeMoaHandlers() {
            // Show selected file name - using event delegation
            $(document).off('change', '.custom-file-input').on('change', '.custom-file-input', function() {
                let fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').addClass("selected").html(fileName);
            });

            // MOA Upload Form Submission - using event delegation with proper cleanup
            $(document).off('submit', '#moaUploadForm').on('submit', '#moaUploadForm', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                let submitBtn = $('#uploadBtn');
                
                submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Uploading...');
                
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: { 
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        toastr.success(response.message);
                        // Update UI without refresh
                        $('.card-body').html(`
                            <div class="w-100">
                                <div class="alert bg-success-subtle text-success text-center mb-4 w-100 small">
                                    <i class="ph-fill ph-check-circle custom-icons-i  mr-2"></i>
                                    ${response.message}
                                </div>
                                
                                <div class="embed-responsive embed-responsive-16by9 mb-3">
                                    <iframe src="${response.file_url}" 
                                            class="embed-responsive-item"
                                            style="border: 1px solid #eee;"
                                            frameborder="0"></iframe>
                                </div>
                                
                                <div class="d-flex justify-content-center gap-3">
                                    <a href="${response.file_url}" 
                                    class="btn btn-primary" 
                                    target="_blank">
                                        <i class="fas fa-download mr-1"></i> Download MOA
                                    </a>
                                    
                                    <button class="btn btn-danger" 
                                            id="removeMoaBtn"
                                            data-url="{{ route('hte.moa.delete') }}">
                                        <i class="fas fa-trash-alt mr-1"></i> Remove MOA
                                    </button>
                                </div>
                            </div>
                        `);
                        initializeMoaHandlers();
                    },
                    error: function(xhr) {
                        let errorMsg = xhr.responseJSON?.message || 'Error uploading MOA';
                        toastr.error(errorMsg);
                        submitBtn.prop('disabled', false).html('<i class="fas fa-upload mr-1"></i> Upload MOA');
                    }
                });
            });

            // MOA Removal - using event delegation with proper cleanup
            $(document).off('click', '#removeMoaBtn').on('click', '#removeMoaBtn', function(e) {
                e.stopImmediatePropagation(); // Prevent multiple handlers from firing
                
                if (!confirm('Are you sure you want to remove your MOA? This action cannot be undone.')) {
                    return;
                }
                
                let btn = $(this);
                let originalText = btn.html();
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Removing...');
                
                $.ajax({
                    url: btn.data('url'),
                    method: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    headers: { 
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        toastr.success(response.message);
                        // Update UI without refresh
                        $('.card-body').html(`
                            <div class="d-flex flex-column align-items-center justify-content-center py-4">
                                <div class="alert bg-warning-subtle text-warning small text-center mb-4 w-100">
                                    <i class="ph-fill ph-warning-circle custom-icons-i small mr-2"></i>
                                    ${response.message}
                                </div>
                                
                                <form id="moaUploadForm" 
                                    action="{{ route('hte.moa.upload') }}" 
                                    method="POST" 
                                    enctype="multipart/form-data"
                                    class="w-100"
                                    style="max-width: 500px;">
                                    @csrf
                                    
                                    <div class="form-group">
                                        <div class="custom-file">
                                            <input type="file" 
                                                class="custom-file-input" 
                                                id="moaFile" 
                                                name="moa_file"
                                                accept=".pdf"
                                                required>
                                            <label class="custom-file-label" for="moaFile">Choose PDF file (max 5MB)</label>
                                        </div>
                                        <small class="form-text text-muted text-center">
                                            Please upload a signed copy of the Memorandum of Agreement in PDF format.
                                        </small>
                                    </div>
                                
                                    <div class="text-center mt-4">
                                        <button type="submit" 
                                                class="btn btn-success btn-lg"
                                                id="uploadBtn">
                                            <i class="fas fa-upload mr-1"></i> Upload MOA
                                        </button>
                                    </div>
                                </form>
                            </div>
                        `);
                        
                        // Reinitialize Bootstrap custom file input
                        if (typeof bsCustomFileInput !== 'undefined') {
                            bsCustomFileInput.init();
                        }
                        
                        // Reinitialize event handlers
                        initializeMoaHandlers();
                    },
                    error: function(xhr) {
                        let errorMsg = xhr.responseJSON?.message || 'Error removing MOA';
                        if (xhr.status === 419) {
                            errorMsg = 'Session expired. Please refresh the page and try again.';
                            location.reload();
                        }
                        toastr.error(errorMsg);
                        btn.prop('disabled', false).html(originalText);
                    }
                });
            });
        }
    });
    </script>

    <!-- HTE Interns Table -->
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#internsTableHTE').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "language": {
                    "emptyTable": "No students are currently deployed to your organization.",
                    "search": "_INPUT_",
                    "searchPlaceholder": "Search students...",
                    "lengthMenu": "Show _MENU_ entries",
                    "info": "Showing _START_ to _END_ of _TOTAL_ students",
                    "paginate": {
                        "previous": "«",
                        "next": "»"
                    }
                },
                "columnDefs": [
                    { "orderable": false, "targets": [6] } // Disable sorting for Actions column
                ],
                "initComplete": function(settings, json) {
                    // Hide loading overlay when table is ready
                    $('#tableLoadingOverlay').fadeOut(300);
                }
            });
            
            // Remove the manual search input if it exists
            $('.card-header input[type="search"]').parent().remove();
            
            // Fallback: hide loading overlay after 2 seconds
            setTimeout(function() {
                $('#tableLoadingOverlay').fadeOut(300);
            }, 2000);

            // Evaluation form submission
            $('.evaluate-form').on('submit', function(e) {
                e.preventDefault();
                
                const form = $(this);
                const deploymentId = form.attr('id').replace('evaluateForm', '');
                const submitBtn = form.find('.submit-evaluate-btn');
                const submitText = form.find('.submit-text');
                const spinner = form.find('.spinner-border');
                
                // Show loading state
                submitBtn.prop('disabled', true);
                submitText.addClass('d-none');
                spinner.removeClass('d-none');
                
                // Clear previous errors
                form.find('.is-invalid').removeClass('is-invalid');
                form.find('.invalid-feedback').text('');
                
                $.ajax({
                    url: '{{ route("hte.interns.evaluate", "") }}/' + deploymentId,
                    type: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        if (response.success) {
                            // Show success toast
                            toastr.success(response.message, 'Success');
                            
                            // Close modal
                            $('#evaluateModal' + deploymentId).modal('hide');
                            
                            // Reload page after short delay to show updated grade
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            toastr.error(response.message, 'Error');
                            submitBtn.prop('disabled', false);
                            submitText.removeClass('d-none');
                            spinner.addClass('d-none');
                        }
                    },
                    error: function(xhr) {
                        const response = xhr.responseJSON;
                        
                        if (xhr.status === 422 && response.errors) {
                            // Show validation errors
                            $.each(response.errors, function(field, errors) {
                                const input = form.find('[name="' + field + '"]');
                                const errorDiv = form.find('#' + field + 'Error' + deploymentId);
                                
                                input.addClass('is-invalid');
                                errorDiv.text(errors[0]);
                            });
                            toastr.error('Please fix the validation errors.', 'Validation Error');
                        } else {
                            toastr.error(response?.message || 'An error occurred while submitting evaluation.', 'Error');
                        }
                        
                        submitBtn.prop('disabled', false);
                        submitText.removeClass('d-none');
                        spinner.addClass('d-none');
                    }
                });
            });

            // Reset form when modal is closed
            $('.modal').on('hidden.bs.modal', function () {
                const form = $(this).find('form');
                form[0].reset();
                form.find('.is-invalid').removeClass('is-invalid');
                form.find('.invalid-feedback').text('');
                
                const submitBtn = form.find('.submit-evaluate-btn');
                const submitText = form.find('.submit-text');
                const spinner = form.find('.spinner-border');
                
                submitBtn.prop('disabled', false);
                submitText.removeClass('d-none');
                spinner.addClass('d-none');
            });
        });
    </script>

    <!-- HTE Profile Management --> 
     <script>
        $(document).ready(function() {
            // Profile Picture Upload
            $('#profileUpload').change(function(e) {
                const file = e.target.files[0];
                if (!file) return;
                
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size must be less than 2MB');
                    return;
                }

                const formData = new FormData();
                formData.append('profile_pic', file);
                formData.append('_token', '{{ csrf_token() }}');

                $.ajax({
                    url: '{{ route("hte.profile.picture") }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#profileImage').attr('src', response.url);
                        toastr.success('Profile picture updated successfully');
                    },
                    error: function(xhr) {
                        toastr.error(xhr.responseJSON.message || 'Error uploading picture');
                    }
                });
            });

            // Skills Modal Functionality
            function updateSelectionCount() {
                const selected = $('.skill-checkbox:checked').length;
                $('#selectedCount').text(selected);
                
                // Enable/disable submit button
                const submitBtn = $('#submitSkillsBtn');
                submitBtn.prop('disabled', selected < 5);
                
                // Update button text
                if (submitBtn.prop('disabled')) {
                    const remaining = 5 - selected;
                    submitBtn.html(`<i class="ph-fill ph-floppy-disk-back custom-icons-i mr-1"></i> Select ${remaining} more`);
                } else {
                    submitBtn.html(`<i class="ph-fill ph-floppy-disk-back custom-icons-i mr-1"></i> Save Skills (${selected})`);
                }
            }

            // Initialize selection count
            updateSelectionCount();

            // Update count on checkbox change
            $('.skill-checkbox').change(updateSelectionCount);

            // Make skill items clickable
            $('.skill-modal-item').click(function(e) {
                if (e.target.type !== 'checkbox') {
                    const checkbox = $(this).find('.skill-checkbox');
                    checkbox.prop('checked', !checkbox.prop('checked'));
                    checkbox.trigger('change');
                }
            });

            // Skills form submission
            $('#skillsForm').submit(function(e) {
                e.preventDefault();
                const form = $(this);
                
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        if (response.success) {
                            // Update the skills display
                            const skillsHtml = response.skills.map(skill => 
                                `<span class="badge bg-secondary-subtle text-secondary py-2 px-3 rounded-pill">
                                    <i class="fas fa-check-circle mr-1"></i> ${skill}
                                </span>`
                            ).join('');
                            
                            $('.card-body .d-flex.flex-wrap').html(skillsHtml);
                            $('#skillsModal').modal('hide');
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        toastr.error(xhr.responseJSON?.message || 'Error updating skills');
                    }
                });
            });
        });
    </script>

    <!-- HTE Grade Calculation -->
<script>
    // Real-time total grade calculation
$(document).on('input', '.factor-input', function() {
    const modal = $(this).closest('.modal');
    const deploymentId = modal.attr('id').replace('evaluateModal', '');
    calculateTotalGrade(deploymentId);
});

function calculateTotalGrade(deploymentId) {
    let total = 0;
    
    const weights = {
        'quality_of_work': 0.20,
        'dependability': 0.15,
        'timeliness': 0.20,
        'attendance': 0.15,
        'cooperation': 0.10,
        'judgment': 0.10,
        'personality': 0.05
    };
    
    Object.keys(weights).forEach(factor => {
        const input = $(`#${factor}${deploymentId}`);
        const value = parseFloat(input.val()) || 0;
        total += value * weights[factor];
    });
    
    // Update total grade display (max 95)
    const displayTotal = total.toFixed(2);
    $(`#totalGradePreview${deploymentId}`).text(displayTotal);
    
    // Update progress bar (0-95 scale)
    const progressPercent = (total / 95) * 100;
    $(`#gradeProgress${deploymentId}`).css('width', `${progressPercent}%`);
    
    // Update all grade previews
    updateGradePreviews(total, deploymentId);
}

function updateGradePreviews(totalGrade, deploymentId) {
    // IMPORTANT: Use the same GPA calculation as PHP model
    // The PHP model uses total_grade directly (0-95 scale) for GPA calculation
    let gpa = 0;
    
    // This should match exactly with the PHP calculateGPA() method
    if (totalGrade >= 95) gpa = 1.00;
    else if (totalGrade >= 90) gpa = 1.25;
    else if (totalGrade >= 85) gpa = 1.50;
    else if (totalGrade >= 80) gpa = 1.75;
    else if (totalGrade >= 75) gpa = 2.00;
    else if (totalGrade >= 70) gpa = 2.25;
    else if (totalGrade >= 65) gpa = 2.50;
    else if (totalGrade >= 60) gpa = 2.75;
    else if (totalGrade >= 55) gpa = 3.00;
    else if (totalGrade >= 50) gpa = 4.00;
    else gpa = 5.00;
    
    // Get letter grade
    const letterGrades = {
        1.00: 'A+', 1.25: 'A', 1.50: 'A-',
        1.75: 'B+', 2.00: 'B', 2.25: 'B-',
        2.50: 'C+', 2.75: 'C', 3.00: 'C-',
        4.00: 'D', 5.00: 'F'
    };
    
    // Update displays
    $(`#gpaPreview${deploymentId}`).text(gpa.toFixed(2));
    $(`#letterGradePreview${deploymentId}`).text(letterGrades[gpa] || 'F');
    
    // Update status
    let status = '-';
    let statusColor = 'text-warning';
    if (totalGrade >= 85) {
        status = 'Excellent';
        statusColor = 'text-success';
    } else if (totalGrade >= 75) {
        status = 'Good';
        statusColor = 'text-primary';
    } else if (totalGrade >= 65) {
        status = 'Fair';
        statusColor = 'text-warning';
    } else {
        status = 'Needs Improvement';
        statusColor = 'text-danger';
    }
    
    const statusElement = $(`#gradeStatus${deploymentId}`);
    statusElement.text(status).removeClass('text-success text-primary text-warning text-danger').addClass(statusColor);
    
    // Update total grade color
    const totalElement = $(`#totalGradePreview${deploymentId}`);
    totalElement.removeClass('text-primary text-success text-warning text-danger');
    
    if (totalGrade >= 85) totalElement.addClass('text-success');
    else if (totalGrade >= 75) totalElement.addClass('text-primary');
    else if (totalGrade >= 65) totalElement.addClass('text-warning');
    else totalElement.addClass('text-danger');
}

// Remove the updateWeightedScores function since we're not showing points anymore
</script>

    <!-- COORDINATOR PROFILE MANAGEMENT -->
    <script>
        $(document).ready(function() {
            // Profile Picture Upload
            $('#profileUpload').change(function(e) {
                const file = e.target.files[0];
                if (!file) return;
                
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size must be less than 2MB');
                    return;
                }

                const formData = new FormData();
                formData.append('profile_pic', file);
                formData.append('_token', '{{ csrf_token() }}');

                $.ajax({
                    url: '{{ route("coordinator.profile.picture") }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#profileImage').attr('src', response.url);
                        toastr.success('Profile picture updated successfully');
                    },
                    error: function(xhr) {
                        toastr.error(xhr.responseJSON.message || 'Error uploading picture');
                    }
                });
            });
        });
    </script>

    <!-- Coordinator: HTE Preview -->
    <script>
    $(document).ready(function() {
        // Toggle MOA status
        $('#toggleMoaStatusBtn').click(function() {
            const hteId = $(this).data('hte-id');
            const button = $(this);
            
            // Correct URL construction
            const url = '{{ route("coordinator.toggle_moa_status", ":id") }}'.replace(':id', hteId);
            
            $.ajax({
                url: url,
                type: 'PATCH',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        // Show success toast
                        toastr.success(response.message, 'Success');
                        
                        // Close modal
                        $('#evaluateModal' + deploymentId).modal('hide');
                        
                        // Reload page after short delay to show updated grade
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        toastr.error(response.message, 'Error');
                        submitBtn.prop('disabled', false);
                        submitText.removeClass('d-none');
                        spinner.addClass('d-none');
                    }
                },
                error: function(xhr) {
                    console.error('Error updating MOA status:', xhr.responseText);
                    toastr.error('Error updating MOA status: ' + xhr.responseText);
                }
            });
        });
    });
    </script>

    <!-- Coordinator: Recommended Interns -->
    <script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Select HTE',
        });

        $('#endorseSelectedBtn').hide();

        let availableSlots = 0;
        let maxSelectable = 0;
        let currentEndorsedCount = 0;
        let dataTableInstance = null;

        // Refresh slots info and recommended interns for given HTE
        function refreshDataForHTE(hteId) {
            if (!hteId) {
                resetTable();
                return;
            }

            const selectedOption = $('#hteSelect').find(`option[value="${hteId}"]`);
            const totalSlots = selectedOption.data('slots') || 0;
            const requiredSkills = selectedOption.data('skills');

            $.ajax({
                url: '{{ route("coordinator.getEndorsedCount") }}',
                method: 'POST',
                data: {
                    hte_id: hteId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(res) {
                    currentEndorsedCount = res.count || 0;
                    availableSlots = totalSlots - currentEndorsedCount;
                    maxSelectable = availableSlots > 0 ? availableSlots : 0;

                    updateSlotsInfo(availableSlots);

                    loadRecommendedInterns(hteId, requiredSkills);
                },
                error: function() {
                    $('#slots-info').html('<div class="text-danger">Failed to load slots info.</div>');
                    resetTable();
                }
            });
        }

        // Update slots info display dynamically
        function updateSlotsInfo(slots) {
            if (slots > 0) {
                $('#slots-info').html(`
                    <div>
                        <i class="ph-fill ph-info custom-icons-i"></i>
                        <em><strong>${slots}</strong> slot${slots !== 1 ? 's' : ''} available</em>
                    </div>
                `);
            } else {
                $('#slots-info').html(`
                    <div class="text-danger">
                        <em>Maximum number of slots reached</em>
                    </div>
                `);
            }
        }

        // On HTE select change, refresh data
        $('#hteSelect').change(function() {
            const hteId = $(this).val();
            refreshDataForHTE(hteId);
        });

        // Load recommended interns via AJAX and render table
        function loadRecommendedInterns(hteId, requiredSkills) {
            $('#internsReccTable tbody').html('<tr><td colspan="6" class="text-center text-muted"><i class="fas fa-spinner fa-spin"></i> Loading recommendations...</td></tr>');
            $('#endorseSelectedBtn').hide();

            $.ajax({
                url: '{{ route("coordinator.getRecommendedInterns") }}',
                method: 'POST',
                data: {
                    hte_id: hteId,
                    required_skills: requiredSkills,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success && response.interns.length > 0) {
                        // Destroy existing DataTable if exists
                        if ($.fn.DataTable.isDataTable('#internsReccTable')) {
                            $('#internsReccTable').DataTable().destroy();
                        }

                        // Build table rows HTML with knobs
                        let html = '';
                        response.interns.forEach(intern => {
                            const isIncomplete = intern.status === 'pending requirements';
                            const statusClass = 
                                intern.status === 'pending requirements' ? 'text-danger bg-danger-subtle' :
                                intern.status === 'ready for deployment' ? 'text-warning bg-warning-subtle' :
                                intern.status === 'endorsed' ? 'text-primary bg-primary-subtle' :
                                intern.status === 'processing' ? 'text-info bg-info-subtle' :
                                intern.status === 'deployed' ? 'text-success bg-success-subtle' :
                                'text-secondary bg-secondary-subtle';


                            let knobColor = intern.match_percentage >= 70 ? '#198754' :
                                            intern.match_percentage >= 40 ? '#ffc107' :
                                            '#dc3545';

                            const checkboxDisabled = intern.status === 'ready for deployment' ? '' : 'disabled';

                            html += `
                            <tr ${isIncomplete ? 'class="table-danger"' : ''}>
                                <td class="align-middle">${intern.student_id || 'N/A'}</td>
                                <td class="align-middle">${intern.fname} ${intern.lname}</td>
                                <td class="align-middle"><span class="badge px-3 py-2 w-100 rounded-pill ${statusClass}">${intern.status.toUpperCase()}</span></td>
                                <td class="align-middle small text-muted">${intern.matching_skills.join(', ') || 'None'}</td>
                                <td class="align-middle">
                                    ${createKnobDisplay(intern.match_percentage, knobColor)}
                                </td>
                                <td class="align-middle text-center">
                                    <div class="form-check d-flex justify-content-center">
                                        <input class="form-check-input intern-checkbox" type="checkbox" 
                                            id="checkbox-intern-${intern.id}" data-intern-id="${intern.id}"
                                            ${checkboxDisabled}>
                                        <label class="form-check-label" for="checkbox-intern-${intern.id}"></label>
                                    </div>
                                </td>
                            </tr>
                            `;
                        });
                        $('#internsReccTable tbody').html(html);

                        // Initialize DataTable
                        dataTableInstance = $('#internsReccTable').DataTable({
                            paging: true,
                            order: [],
                            pageLength: 10,
                            lengthChange: true,
                            searching: true,
                            info: false,
                            ordering: true,
                            columnDefs: [
                                { orderable: false, targets: 5 }
                            ],
                            language: {
                                search: "Search interns:",
                                zeroRecords: "No matching interns found",
                                paginate: {
                                    previous: "&laquo;",
                                    next: "&raquo;"
                                }
                            }
                        });

                        // Checkbox change handler with dynamic slots update and button count
                        $('#internsReccTable tbody').off('change', '.intern-checkbox').on('change', '.intern-checkbox', function() {
                            const checkedCount = $('.intern-checkbox:checked').length;

                            if (checkedCount > maxSelectable) {
                                this.checked = false;
                                toastr.warning(`Maximum number of selectable interns reached (${maxSelectable}).`);
                                return;
                            }

                            const remainingSlots = maxSelectable - checkedCount;
                            updateSlotsInfo(remainingSlots);

                            if (checkedCount > 0) {
                                $('#endorseSelectedBtn').show().html(`<i class="ph-fill ph-paper-plane-tilt custom-icons-i mr-2"></i>Endorse (${checkedCount})`);
                            } else {
                                $('#endorseSelectedBtn').hide();
                            }
                        });

                        // Reset slots info display on table load
                        updateSlotsInfo(availableSlots);

                        $('#endorseSelectedBtn').hide();
                    } else {
                        if ($.fn.DataTable.isDataTable('#internsReccTable')) {
                            $('#internsReccTable').DataTable().destroy();
                        }
                        $('#internsReccTable tbody').html('<tr><td colspan="6" class="text-center">No recommended interns found.</td></tr>');
                        $('#endorseSelectedBtn').hide();
                        updateSlotsInfo(availableSlots);
                    }
                },
                error: function() {
                    if ($.fn.DataTable.isDataTable('#internsReccTable')) {
                        $('#internsReccTable').DataTable().destroy();
                    }
                    $('#internsReccTable tbody').html('<tr><td colspan="6" class="text-center text-danger">Failed to load interns.</td></tr>');
                    $('#endorseSelectedBtn').hide();
                    updateSlotsInfo(availableSlots);
                }
            });
        }

        // Create knob display HTML for match percentage
        function createKnobDisplay(value, color) {
            const angle = value * 3.6;
            return `
            <div class="d-flex justify-content-center align-items-center">
                <div class="knob-container">
                    <div class="knob-display">
                        <div class="knob-bg" style="
                            background: conic-gradient(${color} ${angle}deg, #e9ecef 0);
                        ">
                            <div class="knob-center">${value}%</div>
                        </div>
                    </div>
                </div>
            </div>
            `;
        }

        // Reset table and controls
        function resetTable() {
            $('#slots-info').html('');
            $('#internsReccTable tbody').html('<tr><td colspan="6" class="text-center text-muted">Select an HTE to view recommended interns</td></tr>');
            $('#endorseSelectedBtn').hide();
            availableSlots = 0;
            maxSelectable = 0;
            currentEndorsedCount = 0;

            if ($.fn.DataTable.isDataTable('#internsReccTable')) {
                $('#internsReccTable').DataTable().destroy();
            }
        }

        // Batch endorse button click handler
        $('#endorseSelectedBtn').click(function() {
            const selectedInternIds = $('.intern-checkbox:checked').map(function() {
                return $(this).data('intern-id');
            }).get();

            if (selectedInternIds.length === 0) {
                toastr.warning('Please select at least one intern to endorse.');
                return;
            }

            const hteId = $('#hteSelect').val();
            if (!hteId) {
                toastr.warning('Please select an HTE first.');
                return;
            }

            $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Endorsing...');

            $.ajax({
                url: '{{ route("coordinator.batchEndorseInterns") }}',
                method: 'POST',
                data: {
                    hte_id: hteId,
                    intern_ids: selectedInternIds,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message || 'Interns endorsed successfully.');

                        // Refresh slots info and recommended interns table
                        refreshDataForHTE(hteId);
                    } else {
                        toastr.error(response.message || 'Failed to endorse interns.');
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'Failed to endorse interns. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    toastr.error(errorMsg);
                },
                complete: function() {
                    $('#endorseSelectedBtn').prop('disabled', false).html('<i class="fas fa-paper-plane"></i> Endorse Selected');
                }
            });
        });
    });
    </script>

    <!-- Coordinator: Intern Import -->
    <script>
    // Import form handling
    $('#importForm').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const submitBtn = $('#importSubmit');
        const progress = $('#importProgress');
        const results = $('#importResults');
        const spinner = progress.find('.spinner-border');
        
        // Show progress, hide results
        progress.removeClass('d-none');
        results.addClass('d-none');
        submitBtn.prop('disabled', true);
        
        // Prepare form data
        const formData = new FormData(this);
        
        // AJAX request
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            xhr: function() {
                const xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                        const percent = Math.round((e.loaded / e.total) * 100);
                        $('.progress-bar').css('width', percent + '%');
                    }
                });
                return xhr;
            },
            success: function(response) {
                if (response.success) {
                    // Update results display
                    $('#successCount').text(response.success_count);
                    $('#failCount').text(response.fail_count);
                    
                    if (response.failures.length > 0) {
                        const failBody = $('#failDetailsBody');
                        failBody.empty();
                        
                        response.failures.forEach(failure => {
                            failBody.append(`
                                <tr>
                                    <td>${failure.row}</td>
                                    <td>${failure.student_id || 'N/A'}</td>
                                    <td>${failure.name || 'N/A'}</td>
                                    <td>${failure.errors.join('<br>')}</td>
                                </tr>
                            `);
                        });
                        
                        $('#failDetails').removeClass('d-none');
                    } else {
                        $('#failDetails').addClass('d-none');
                    }
                    
                    // Show results and hide spinner
                    spinner.addClass('d-none');
                    results.removeClass('d-none');
                    
                    // Remove Close button
                    $('.modal-footer .btn-secondary').remove();
                    
                    // Change Import button to Complete button
                    submitBtn
                        .removeClass('btn-success')
                        .addClass('btn-primary')
                        .html('Complete')
                        .prop('disabled', false)
                        .off('click')
                        .on('click', function(e) {
                            e.preventDefault(); // Prevent form submission
                            e.stopPropagation(); // Stop event bubbling
                            
                            // Close modal immediately
                            $('#importModal').modal('hide');
                            
                            // Show success message if any interns were imported
                            if (response.success_count > 0) {
                                sessionStorage.setItem('importSuccess', response.success_count);
                            }
                            
                            // Refresh page after modal is fully hidden
                            $('#importModal').on('hidden.bs.modal', function() {
                                window.location.reload();
                            });
                        });
                }
            },
            error: function(xhr) {
                let errorMsg = 'An error occurred during import.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                alert(errorMsg);
                submitBtn.prop('disabled', false);
            },
            complete: function() {
                $('#progressText').text('Import completed');
                $('.progress-bar').removeClass('progress-bar-animated');
                spinner.addClass('d-none');
            }
        });
    });

    // Reset modal when closed
    $('#importModal').on('hidden.bs.modal', function() {
        $('#importForm')[0].reset();
        $('#importProgress').addClass('d-none');
        $('#importResults').addClass('d-none');
        $('.progress-bar').css('width', '0%').addClass('progress-bar-animated');
        $('#progressText').text('Processing import...');
        $('#importProgress').find('.spinner-border').removeClass('d-none');
        
        // Reset button to original state
        $('#importSubmit')
            .removeClass('btn-primary')
            .addClass('btn-success')
            .html('Import')
            .off('click')
            .prop('disabled', false);
            
        // Restore Close button if it was removed
        if ($('.modal-footer .btn-secondary').length === 0) {
            $('.modal-footer').prepend('<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>');
        }
    });

    // Show success message after page reload if needed
    $(document).ready(function() {
        const importedCount = sessionStorage.getItem('importSuccess');
        if (importedCount) {
            toastr.success(`${importedCount} interns imported successfully`);
            sessionStorage.removeItem('importSuccess');
        }
    });
    </script>

    <!-- Coordinator: Intern Management Table -->
    <script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#internsTable').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "language": {
            "emptyTable": "No intern data found.",
            "search": "_INPUT_",
            "searchPlaceholder": "Search...",
            "lengthMenu": "Show _MENU_ entries",
            "info": "Showing _START_ to _END_ of _TOTAL_ interns",
            "paginate": {
            "previous": "«",
            "next": "»"
            }
        },
        "columnDefs": [
            { "orderable": false, "targets": [6] } // Disable sorting for Actions column
        ],
        "initComplete": function() {
            // Hide loading overlay when table is ready
            $('#tableLoadingOverlay').fadeOut();
        }
        });
    });
    </script>

    <!-- Coordinator HTE Management Table -->
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#htesTable').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "language": {
                    "emptyTable": "No HTE data found.",
                    "search": "_INPUT_",
                    "searchPlaceholder": "Search...",
                    "lengthMenu": "Show _MENU_ entries",
                    "info": "Showing _START_ to _END_ of _TOTAL_ HTEs",
                    "paginate": {
                        "previous": "«",
                        "next": "»"
                    }
                },
                "columnDefs": [
                    { "orderable": false, "targets": [5] } // Disable sorting for Actions column
                ],
                "initComplete": function() {
                    // Hide loading overlay when table is ready
                    $('#tableLoadingOverlay').fadeOut();
                }
            });
            
            // Remove the manual search input
            $('.card-header input[type="search"]').parent().remove();
        });
    </script>

    <!-- Coordinator Deployments Management Table -->
    <script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#deploymentsTable').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "language": {
            "emptyTable": "No deployments found.",
            "search": "_INPUT_",
            "searchPlaceholder": "Search...",
            "lengthMenu": "Show _MENU_ entries",
            "info": "Showing _START_ to _END_ of _TOTAL_ deployments",
            "paginate": {
            "previous": "«",
            "next": "»"
            }
        },
        "columnDefs": [
            { "orderable": false, "targets": [5] }
        ],
        "initComplete": function() {
            // Hide loading overlay when table is ready
            $('#tableLoadingOverlay').fadeOut();
        }
        });
    });
    </script>

    <!-- Coordinator: HTE/show - Remove Endorsement -->
    <script>
        $(document).ready(function() {
            // Handle Remove Endorsement button click inside modal
            $('.btn-remove-endorsement').on('click', function() {
                const internsHteId = $(this).data('interns-hte-id');
                const rowId = $(this).data('row-id');
                const $modal = $(this).closest('.modal');

                $(this).prop('disabled', true).text('Removing...');

                $.ajax({
                    url: `/coordinator/remove-endorsement/${internsHteId}`,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#' + rowId).fadeOut(400, function() {
                                $(this).remove();
                            });
                            toastr.success(response.message || 'Endorsement removed successfully.');
                        } else {
                            toastr.error(response.message || 'Failed to remove endorsement.');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Failed to remove endorsement. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        toastr.error(errorMsg);
                    },
                    complete: function() {
                        $('.btn-remove-endorsement').prop('disabled', false).text('Remove Endorsement');
                        $modal.modal('hide');
                    }
                });
            });
        });
    </script>

    <!-- Coordinator: HTE/show - All Students Table with Rowspan -->
    <script>
    $(document).ready(function() {
        // Wait a bit for the DOM to fully render the rowspan structure
        setTimeout(function() {
            try {
                // Initialize DataTable with minimal configuration
                $('#allStudentsTable').DataTable({
                    "paging": true,
                    "lengthChange": false,
                    "searching": false,
                    "ordering": true,
                    "info": true,
                    "autoWidth": false,
                    "responsive": false, // Disable responsive for complex tables
                    "pageLength": 10,
                    "order": [], // No initial sorting to avoid conflicts
                    "language": {
                        "emptyTable": "No students endorsed to this HTE yet.",
                        "lengthMenu": "",
                        "info": "Showing _START_ to _END_ of _TOTAL_ students",
                        "infoEmpty": "Showing 0 to 0 of 0 students",
                        "infoFiltered": "",
                        "paginate": {
                            "previous": "«",
                            "next": "»"
                        }
                    },
                    "initComplete": function() {
                        console.log('DataTable initialized successfully');
                        $('#allStudentsLoadingOverlay').fadeOut();
                    },
                    "drawCallback": function() {
                        console.log('Table redrawn');
                    }
                });
            } catch (error) {
                console.error('DataTable initialization failed:', error);
                // If DataTable fails, just hide the overlay and show the table as-is
                $('#allStudentsLoadingOverlay').fadeOut();
            }
        }, 100); // Small delay to ensure DOM is ready
        
        // Fallback: Always hide overlay after 3 seconds
        setTimeout(function() {
            $('#allStudentsLoadingOverlay').fadeOut();
        }, 3000);
    });
    </script>

    <!-- Coordinator: Documents Management -->
    <script>
    $(document).ready(function() {
        // Upload document modal
        $('.upload-document').click(function() {
            const type = $(this).data('type');
            const label = $(this).closest('tr').find('td:first').text() || 
                        $(this).closest('.card').find('.card-title').text();
            
            $('#documentType').val(type);
            $('#uploadModal .modal-title').text('Upload ' + label);
            $('#uploadModal').modal('show');
        });

        // View document
        $('.view-document').click(function() {
            const url = $(this).data('url');
            const label = $(this).closest('tr').find('td:first').text() || 
                        $(this).closest('.card-body').find('.card-title').text();
            
            $('#documentTitle').text(label);
            $('#documentFrame').attr('src', url);
            $('#downloadLink').attr('href', url);
            $('#documentModal').modal('show');
        });

        // Remove document
        $('.remove-document').click(function() {
            const documentId = $(this).data('id');
            const row = $(this).closest('tr');
            const card = $(this).closest('.col-12');
            const documentType = row.data('document-type') || card.data('document-type');
            
            if (confirm('Are you sure you want to delete this document?')) {
                $.ajax({
                    url: '{{ route("coordinator.documents.delete", "") }}/' + documentId,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Document deleted successfully!', 'Success', {
                                timeOut: 3000,
                                progressBar: true
                            });
                            
                            // Update UI without page reload
                            updateUIAfterDelete(response, documentType);
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Error deleting document. Please try again.', 'Error', {
                            timeOut: 5000,
                            progressBar: true
                        });
                    }
                });
            }
        });

        // Upload form submission
        $('#uploadForm').submit(function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();
            const documentType = $('#documentType').val();
            
            submitBtn.prop('disabled', true).html('<i class="ph ph-circle-notch ph-spin custom-icons-i mr-1"></i>Uploading...');
            
            $.ajax({
                url: '{{ route("coordinator.documents.upload") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#uploadModal').modal('hide');
                    toastr.success('Document uploaded successfully!', 'Success', {
                        timeOut: 3000,
                        progressBar: true
                    });
                    
                    // Update UI without page reload
                    updateUIAfterUpload(response, documentType);
                },
                error: function(xhr) {
                    let errorMessage = 'Error uploading document. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    }
                    toastr.error(errorMessage, 'Error', {
                        timeOut: 5000,
                        progressBar: true
                    });
                    submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });

        // Function to update UI after successful upload
        function updateUIAfterUpload(response, documentType) {
            updateStatusUI(response);
            
            // Update the specific row/card that was uploaded
            const row = $(`[data-document-type="${documentType}"]`);
            
            if (row.length) {
                // Desktop table view
                if (row.is('tr')) {
                    // Clear existing status content and replace
                    const statusTd = row.find('td').eq(2);
                    statusTd.html(`
                        <span class="badge bg-success-subtle text-success py-2 px-3 rounded-4 w-100 status-badge">Submitted</span><br>
                        <small>${new Date().toISOString().split('T')[0]}</small>
                    `);
                    
                    // Replace upload button with dropdown
                    const actionHtml = `
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="actionDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ph-fill ph-gear custom-icons-i"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right py-0 overflow-hidden" aria-labelledby="actionDropdown">
                                <button class="dropdown-item btn btn-outline-light view-document w-100 fw-medium border-bottom border-lightgray btn-flat text-dark py-2" 
                                        data-url="${response.document.file_path}">
                                    <i class="ph ph-eye custom-icons-i"></i>
                                    <span>View</span>
                                </button>
                                <button class="dropdown-item btn btn-outline-light remove-document w-100 fw-medium btn-flat text-danger py-2" 
                                        data-id="${response.document.id}">
                                    <i class="ph ph-trash custom-icons-i"></i>
                                    <span>Delete</span>
                                </button>
                            </div>
                        </div>
                    `;
                    row.find('td:last').html(actionHtml);
                } 
                // Mobile card view
                else {
                    const statusDiv = row.find('.text-end');
                    statusDiv.html(`
                        <span class="badge bg-success-subtle text-success py-1 px-2 rounded-4">Submitted</span>
                        <small class="d-block text-muted">${new Date().toISOString().split('T')[0]}</small>
                    `);
                    
                    const actionHtml = `
                        <div class="btn-group w-100" role="group">
                            <button class="btn btn-outline-primary view-document flex-fill" 
                                    data-url="${response.document.file_path}">
                                <i class="ph ph-eye custom-icons-i me-1"></i>
                                View
                            </button>
                            <button class="btn btn-outline-danger remove-document flex-fill" 
                                    data-id="${response.document.id}">
                                <i class="ph ph-trash custom-icons-i me-1"></i>
                                Delete
                            </button>
                        </div>
                    `;
                    row.find('.d-grid').html(actionHtml);
                }
                
                // Re-bind event listeners to the new buttons
                bindEventListeners();
            }
        }

        // Function to update UI after successful delete
        function updateUIAfterDelete(response, documentType) {
            updateStatusUI(response);
            
            // Update the specific row/card that was deleted
            const row = $(`[data-document-type="${documentType}"]`);
            
            if (row.length) {
                // Desktop table view
                if (row.is('tr')) {
                    // Clear existing status content and replace
                    const statusTd = row.find('td').eq(2);
                    statusTd.html(`
                        <span class="badge bg-danger-subtle text-danger py-2 px-3 rounded-pill w-100 status-badge">Missing</span>
                    `);
                    
                    // Replace dropdown with upload button
                    const actionHtml = `
                        <button class="btn btn-sm btn-outline-success upload-document fw-medium" 
                                data-type="${documentType}">
                            <i class="ph-fill ph-upload custom-icons-i"></i>
                            <span>Upload</span>
                        </button>
                    `;
                    row.find('td:last').html(actionHtml);
                } 
                // Mobile card view
                else {
                    const statusDiv = row.find('.text-end');
                    statusDiv.html(`
                        <span class="badge bg-danger-subtle text-danger py-1 px-2 rounded-pill">Missing</span>
                    `);
                    
                    const actionHtml = `
                        <button class="btn btn-success upload-document w-100" 
                                data-type="${documentType}">
                            <i class="ph-fill ph-upload custom-icons-i me-1"></i>
                            Upload Document
                        </button>
                    `;
                    row.find('.d-grid').html(actionHtml);
                }
                
                // Re-bind event listeners to the new buttons
                bindEventListeners();
            }
        }

        // Function to update status UI (counter, badge, etc.)
        function updateStatusUI(response) {
            // Update document counter
            $('#documentCounter').text(response.document_count);
            
            // Format status text for display (capitalize first letter of each word)
            const formatStatusText = (status) => {
                return status.split(' ')
                    .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                    .join(' ');
            };
            
            const displayStatus = formatStatusText(response.status);
            $('#statusText').text(displayStatus);
            
            // Update badge appearance based on new status
            const statusBadge = $('#statusBadge');
            statusBadge.removeClass('bg-success-subtle bg-info-subtle bg-warning-subtle text-success text-info text-warning');
            
            switch(response.status) {
                case 'eligible for claim':
                case 'claimed':
                    statusBadge.addClass('bg-success-subtle text-success');
                    break;
                case 'for validation':
                    statusBadge.addClass('bg-info-subtle text-info');
                    break;
                default:
                    statusBadge.addClass('bg-warning-subtle text-warning');
            }
            
            // Update status icon
            const statusIcon = statusBadge.find('i');
            statusIcon.removeClass('ph-seal-check ph-seal-warning ph-seal-question');
            
            switch(response.status) {
                case 'eligible for claim':
                case 'claimed':
                    statusIcon.addClass('ph-seal-check');
                    break;
                case 'for validation':
                    statusIcon.addClass('ph-seal-warning');
                    break;
                default:
                    statusIcon.addClass('ph-seal-question');
            }
        }

        // Function to bind event listeners to dynamically created elements
        function bindEventListeners() {
            // Re-bind upload document buttons
            $('.upload-document').off('click').on('click', function() {
                const type = $(this).data('type');
                const label = $(this).closest('tr').find('td:first').text() || 
                            $(this).closest('.card').find('.card-title').text();
                
                $('#documentType').val(type);
                $('#uploadModal .modal-title').text('Upload ' + label);
                $('#uploadModal').modal('show');
            });

            // Re-bind view document buttons
            $('.view-document').off('click').on('click', function() {
                const url = $(this).data('url');
                const label = $(this).closest('tr').find('td:first').text() || 
                            $(this).closest('.card-body').find('.card-title').text();
                
                $('#documentTitle').text(label);
                $('#documentFrame').attr('src', url);
                $('#downloadLink').attr('href', url);
                $('#documentModal').modal('show');
            });

            // Re-bind remove document buttons
            $('.remove-document').off('click').on('click', function() {
                const documentId = $(this).data('id');
                const row = $(this).closest('tr');
                const card = $(this).closest('.col-12');
                const documentType = row.data('document-type') || card.data('document-type');
                
                if (confirm('Are you sure you want to delete this document?')) {
                    $.ajax({
                        url: '{{ route("coordinator.documents.delete", "") }}/' + documentId,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success('Document deleted successfully!', 'Success', {
                                    timeOut: 3000,
                                    progressBar: true
                                });
                                
                                updateUIAfterDelete(response, documentType);
                            }
                        },
                        error: function(xhr) {
                            toastr.error('Error deleting document. Please try again.', 'Error', {
                                timeOut: 5000,
                                progressBar: true
                            });
                        }
                    });
                }
            });
        }

        // Reset form when modal is closed
        $('#uploadModal').on('hidden.bs.modal', function() {
            $('#uploadForm')[0].reset();
            $('#uploadForm button[type="submit"]').prop('disabled', false).html('<i class="ph-fill ph-upload custom-icons-i mr-1"></i>Upload');
        });

        // Handle modal iframes - clean up when modal is closed
        $('#documentModal').on('hidden.bs.modal', function() {
            $('#documentFrame').attr('src', '');
        });

        // Define document type labels for JavaScript use
        const CoordinatorDocument = {
            typeLabels: {
                'consolidated_moas': 'Consolidated Notarized MOAs',
                'consolidated_sics': 'Consolidated Notarized SICs',
                'annex_c': 'ANNEX C Series of 2017',
                'annex_d': 'ANNEX D Series of 2017',
                'honorarium_request': 'Honorarium Request',
                'special_order': 'Special Order',
                'board_resolution': 'Board Resolution'
            }
        };
    });
    </script>












