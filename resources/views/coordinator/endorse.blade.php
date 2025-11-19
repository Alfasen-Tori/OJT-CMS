@extends('layouts.coordinator')

@section('title', 'Endorse')

@section('content')
<section class="content-header">
@include('layouts.partials.scripts-main')

  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="page-header">ENDORSEMENT</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item fw-medium">Coordinator</li>
          <li class="breadcrumb-item active text-muted">Endorse</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Select HTE</h3>
      </div>
      <div class="card-body">
        <div class="form-group">
          <label>Host Training Establishment</label>
          <select id="hteSelect" class="form-control select2">
            <option value="" selected disabled>Select an HTE</option>
            @foreach($htes as $hte)
              <option value="{{ $hte->id }}" data-skills="{{ $hte->skills->pluck('skill_id')->toJson() }}" data-slots="{{ $hte->slots }}">
                {{ $hte->organization_name }} 
              </option>
            @endforeach
          </select>

          <div id="slots-info" class="mt-2 text-muted small"></div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title" id="internsTableHeader">Top Candidates</h3>
        <button id="endorseSelectedBtn" class="btn btn-primary ml-auto" style="display:none;">
          <i class="ph ph-paper-plane-tilted"></i> Endorse Selected
        </button>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table id="internsReccTable" class="table table-bordered">
            <thead class="thead-light">
              <tr>
                <th>Student ID</th>
                <th>Name</th>
                <th>Status</th>
                <th>Matching Skills</th>
                <th>Match %</th>
                <th width="7%" class="text-center">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td colspan="6" class="text-center text-muted">Select an HTE to view recommended interns</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

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

@endsection



