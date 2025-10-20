<!-- Phosphour Icons -->
<script src="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2"></script>

<!-- jQuery -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>

<!-- JQueryKnobCharts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-knob/1.2.13/jquery.knob.min.js"></script>

<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTable JS -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<!-- JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "5000"
    };
</script>

<!-- Document handling scripts -->
<script>
$(document).ready(function() {
    // Initialize all event handlers
    initializeDocumentHandlers();

    function initializeDocumentHandlers() {
        // Document Preview - works for both table and card views
        $(document).off('click', '.view-document').on('click', '.view-document', function() {
            const url = $(this).data('url');
            const title = $(this).closest('tr').find('td:first').text() || 
                         $(this).closest('.card').find('.card-title').text();
            
            $('#documentTitle').text(title);
            $('#documentFrame').attr('src', url);
            $('#downloadLink').attr('href', url);
            $('#documentModal').modal('show');
        });

        // Document Upload Init - works for both table and card views
        $(document).off('click', '.upload-document').on('click', '.upload-document', function() {
            const type = $(this).data('type');
            const title = $(this).closest('tr').find('td:first').text() || 
                         $(this).closest('.card').find('.card-title').text();
            
            $('#documentType').val(type);
            $('#uploadModal .modal-title').text('Upload: ' + title);
            $('#uploadModal').modal('show');
        });

        // Document Removal - works for both table and card views
        $(document).off('click', '.remove-document').on('click', '.remove-document', function() {
            if (!confirm('Are you sure you want to remove this document?')) return;
            
            const documentId = $(this).data('id');
            const row = $(this).closest('tr, .col-12'); // Support both table row and card
            const documentType = row.data('document-type');
            
            // Show loading state
            const deleteBtn = $(this);
            deleteBtn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);
            
            $.ajax({
                url: '{{ route("intern.docs.delete") }}',
                method: 'DELETE',
                data: { id: documentId },
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                success: function(response) {
                    // Update BOTH table and card views to keep them in sync
                    updateDocumentUI(documentType, 'remove', response);
                    
                    // Get current count before updating
                    const currentCount = parseInt($('#documentCounter').text());
                    $('#documentCounter').text(currentCount - 1);
                    updateStatusBadge(currentCount - 1);
                    
                    toastr.success(response.message);
                    
                    initializeDocumentHandlers();
                },
                error: function(xhr) {
                    toastr.error('Error removing document: ' + (xhr.responseJSON?.message || 'Unknown error'));
                    deleteBtn.html('<i class="ph ph-trash custom-icons-i me-1"></i>Delete').prop('disabled', false);
                }
            });
        });
    }

    // Form Submission - No page refresh
    $('#uploadForm').submit(function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const submitBtn = $(this).find('button[type="submit"]');
        const documentType = $('#documentType').val();
        
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Uploading...');
        
        $.ajax({
            url: '{{ route("intern.docs.upload") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function(response) {
                $('#uploadModal').modal('hide');
                
                // Clear the form fields
                $('#uploadForm')[0].reset();
                
                // Update BOTH table and card views to keep them in sync
                updateDocumentUI(documentType, 'upload', response);
                
                // Get current count before updating
                const currentCount = parseInt($('#documentCounter').text());
                $('#documentCounter').text(currentCount + 1);
                updateStatusBadge(currentCount + 1);
                
                toastr.success(response.message);
                
                initializeDocumentHandlers();
                submitBtn.prop('disabled', false).html('<i class="ph-fill ph-upload custom-icons-i mr-1"></i> Upload');
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Error uploading document');
                submitBtn.prop('disabled', false).html('<i class="ph-fill ph-upload custom-icons-i mr-1"></i> Upload');
            }
        });
    });

    // Unified function to update both table and card views
    function updateDocumentUI(documentType, action, response) {
        // Update table view
        const tableRow = $(`tr[data-document-type="${documentType}"]`);
        if (tableRow.length) {
            if (action === 'upload') {
                tableRow.find('td:eq(2)').html(`
                    <span class="badge bg-success-subtle text-success py-2 px-3 rounded-pill w-100">Submitted</span>
                    <br>
                    <small>${response.created_at}</small>
                `);
                
                tableRow.find('td:eq(3)').html(`
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="actionDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ph-fill ph-gear custom-icons-i"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right py-0 overflow-hidden" aria-labelledby="actionDropdown">
                            <button class="dropdown-item btn btn-outline-light view-document w-100 fw-medium border-bottom border-lightgray btn-flat text-dark py-2" 
                                    data-url="${response.file_url}">
                                <i class="ph ph-eye custom-icons-i"></i>
                                <span>View</span>
                            </button>
                            <button class="dropdown-item btn btn-outline-light remove-document w-100 fw-medium btn-flat text-danger py-2" 
                                    data-id="${response.document_id}">
                                <i class="ph ph-trash custom-icons-i"></i>
                                <span>Delete</span>
                            </button>
                        </div>
                    </div>
                `);
            } else if (action === 'remove') {
                tableRow.find('td:eq(2)').html(`
                    <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill w-100 status-badge">Missing</span>
                `);
                
                tableRow.find('td:eq(3)').html(`
                    <button class="btn btn-sm btn-outline-success upload-document" 
                            data-type="${documentType}">
                        <i class="ph-fill ph-upload custom-icons-i"></i>
                        <span>Upload</span>
                    </button>
                `);
            }
        }

        // Update card view
        const cardElement = $(`.col-12[data-document-type="${documentType}"]`);
        if (cardElement.length) {
            const cardBody = cardElement.find('.card-body');
            if (action === 'upload') {
                cardBody.find('.badge').removeClass('bg-danger-subtle text-danger')
                    .addClass('bg-success-subtle text-success')
                    .text('Submitted')
                    .next('small').remove(); // Remove any existing date
                
                // Add date after badge
                cardBody.find('.badge').after(`<small class="d-block text-muted">${response.created_at}</small>`);
                
                // Replace upload button with view/delete buttons
                cardBody.find('.btn, .btn-group').replaceWith(`
                    <div class="btn-group w-100" role="group">
                        <button class="btn btn-outline-primary view-document flex-fill" 
                                data-url="${response.file_url}">
                            <i class="ph ph-eye custom-icons-i me-1"></i>
                            View
                        </button>
                        <button class="btn btn-outline-danger remove-document flex-fill" 
                                data-id="${response.document_id}">
                            <i class="ph ph-trash custom-icons-i me-1"></i>
                            Delete
                        </button>
                    </div>
                `);
            } else if (action === 'remove') {
                cardBody.find('.badge').removeClass('bg-success-subtle text-success')
                    .addClass('bg-danger-subtle text-danger')
                    .text('Missing')
                    .next('small').remove(); // Remove date
                
                // Replace view/delete buttons with upload button
                cardBody.find('.btn-group').replaceWith(`
                    <button class="btn btn-success upload-document w-100" 
                            data-type="${documentType}">
                        <i class="ph-fill ph-upload custom-icons-i me-1"></i>
                        Upload Document
                    </button>
                `);
            }
        }
    }

    function updateStatusBadge(count) {
        const badge = $('#statusBadge');
        const icon = badge.find('i');
        const statusText = $('#statusText');
        
        if (count >= 9) {
            badge.removeClass('bg-warning-subtle text-warning')
                 .addClass('bg-success-subtle text-success');
            icon.removeClass('ph-seal-question').addClass('ph-seal-check');
            statusText.text('Complete');
        } else {
            badge.removeClass('bg-success-subtle text-success')
                 .addClass('bg-warning-subtle text-warning');
            icon.removeClass('ph-seal-check').addClass('ph-seal-question');
            statusText.text('Incomplete');
        }
    }
});
</script>

<!-- Profile Management -->
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
            url: '{{ route("intern.profile.picture") }}',
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

    $('#skillsForm').submit(function(e) {
        e.preventDefault();
        const form = $(this);
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST', // This will be converted to PUT by @method('PUT')
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    // Update the skills display
                    $('#skillsBadges').html(
                        response.skills.map(skill => 
                            `<span class="badge bg-primary py-2 px-3 mr-2 mb-2">
                                <i class="fas fa-check-circle mr-1"></i> ${skill}
                            </span>`
                        ).join('')
                    );
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

<!-- Attendances Table -->
 <script>
$(document).ready(function() {
    // Initialize DataTable
    $('#internAttendanceTable').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "order": [[0, 'desc']], // Sort by date descending
        "language": {
            "emptyTable": "No attendance records found.",
            "search": "_INPUT_",
            "searchPlaceholder": "Search dates, times...",
            "lengthMenu": "Show _MENU_ entries",
            "info": "Showing _START_ to _END_ of _TOTAL_ records",
            "infoEmpty": "Showing 0 to 0 of 0 records",
            "infoFiltered": "(filtered from _MAX_ total records)",
            "paginate": {
                "previous": "«",
                "next": "»"
            }
        },
        "initComplete": function() {
            // Hide loading overlay when table is ready
            $('#tableLoadingOverlay').fadeOut();
            
            // Move search input to card header
            const searchInput = $('.dataTables_filter input');
            const cardHeader = $('.card-header .input-group');
            
            if (cardHeader.length) {
                searchInput.detach().appendTo(cardHeader);
                searchInput.attr('placeholder', 'Search dates, times...');
                $('.dataTables_filter').remove();
            }
        },
        "drawCallback": function() {
            // Update any dynamic content after table redraw
            console.log('Table redrawn');
        }
    });
});
</script>


