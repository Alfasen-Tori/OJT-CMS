@extends('layouts.coordinator')

@section('title', 'Coordinator User Guide')

@section('content')
<section class="content-header">
    @include('layouts.partials.scripts-main')

    <div class="container-fluid px-0 px-sm-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="page-header">USER GUIDE</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item fw-medium">Coordinator</li>
                    <li class="breadcrumb-item active">User Guide</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid px-0 px-sm-2">

        <!-- Table of Contents -->
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <span class="fw-medium text-primary"><i class="ph-bold ph-list-bullets custom-icons-i me-2"></i>TABLE OF CONTENTS</span>
            </div>
            <div class="card-body">
                <ol class="mb-0">
                    <li><a href="#intern-management" class="text-decoration-none">Intern Management</a></li>
                    <li><a href="#hte-management" class="text-decoration-none">HTE Management</a></li>
                    <li><a href="#endorsement" class="text-decoration-none">Endorsement</a></li>
                    <li><a href="#deployments" class="text-decoration-none">Deployments</a></li>
                    <li><a href="#honorarium" class="text-decoration-none">Honorarium Requirements</a></li>
                </ol>
            </div>
        </div>

        <!-- 1. Intern Management -->
        <div id="intern-management" class="card shadow-sm mb-4">
            <div class="card-header">
                <span class="fw-medium text-primary">1. Intern Management</span>
            </div>
            <div class="card-body">
                <!-- Adding Intern -->
                <div class="mb-4">
                    <h5>Adding Intern</h5>
                    <div class="ps-4">
                        <h6 class="mt-3">Manual Entry</h6>
                        <ol class="mb-3">
                            <li>Navigate to <strong>Interns → Add Intern</strong></li>
                            <li>Fill in all required fields</li>
                            <li>Upload supporting documents</li>
                            <li>Click <button type="button" class="btn btn-sm btn-primary" disabled>Register Intern</button></li>
                        </ol>

                        <h6>Import from CSV</h6>
                        <ol>
                            <li>Go to <strong>Interns → Import</strong></li>
                            <li>Download the template file</li>
                            <li>Fill in the template with intern data</li>
                            <li>Upload the completed CSV file</li>
                            <li>Review and confirm the import</li>
                        </ol>
                    </div>
                </div>

                <!-- Edit Intern -->
                <div class="mb-4">
                    <h5>Edit Intern</h5>
                    <ol class="ps-4">
                        <li>Find the intern in the <strong>Intern List</strong></li>
                        <li>Click the <button disabled class="btn btn-sm btn-outline-light rounded-4 text-primary"><i class="ph ph-wrench custom-icons-i me-1"></i>Update</button> option</li>
                        <li>Update the necessary information</li>
                        <li>When done, click <button type="button" class="btn btn-sm btn-primary" disabled>Save Changes</button></li>
                    </ol>
                </div>

                <!-- Delete Intern -->
                <div>
                    <h5>Delete Intern</h5>
                    <div class="alert bg-warning-subtle mb-3 text-warning border-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        This action cannot be undone.
                    </div>
                    <ol class="ps-4">
                        <li>Locate the intern to be removed</li>
                        <li>Click the <button disabled class="btn btn-sm btn-outline-light rounded-4 text-danger"><i class="ph ph-trash custom-icons-i me-1"></i>Delete</button> option
                        <li>Confirm deletion in the pop-up dialog</li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- 2. HTE Management -->
        <div id="hte-management" class="card shadow-sm mb-4">
            <div class="card-header">
                <span class="fw-medium text-primary">2. HTE Management</span>
            </div>
            <div class="card-body">
                <!-- Adding HTE -->
                <div class="mb-4">
                    <h5>Adding HTE</h5>
                    <ol class="ps-4">
                        <li>Navigate to <strong>HTE → Add HTE</strong></li>
                        <li>Complete company details & representative info</li>
                        <li>Select HTE status (active/new)</li>
                        <li>Upload Student Internship Plan</li>
                        <li>Click <button type="button" class="btn btn-sm btn-primary" disabled>Register HTE</button></li>
                    </ol>
                </div>

                <!-- Updating MOA Status -->
                <div class="mb-4">
                    <h5>Updating MOA Status</h5>
                    <ol class="ps-4">
                        <li>Go to <strong>HTE → MOA Status</strong></li>
                        <li>Select HTE with <span class="text-warning small text-bold">VALIDATION REQUIRED</span> status</li>
                        <li>Upload MOA status accordingly (signed/unsigned)</li>
                    </ol>
                </div>

                <!-- Edit HTE -->
                <div class="mb-4">
                    <h5>Edit HTE</h5>
                    <ol class="ps-4">
                        <li>Find the HTE in the <strong>HTE List</strong></li>
                        <li>Click the <button disabled class="btn btn-sm btn-outline-light rounded-4 text-primary"><i class="ph ph-wrench custom-icons-i me-1"></i>Update</button> option</li>
                        <li>Update the necessary information</li>
                        <li>When done, click <button type="button" class="btn btn-sm btn-primary" disabled>Save Changes</button></li>
                    </ol>
                </div>

                <!-- Delete HTE -->
                <div>
                    <h5>Delete HTE</h5>
                    <div class="alert bg-danger-subtle text-danger mb-3 border-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        This action cannot be undone and <strong>affects ongoing internships</strong>.
                    </div>
                    <ol class="ps-4">
                        <li>Locate the HTE to be removed</li>
                        <li>Click the <button disabled class="btn btn-sm btn-outline-light rounded-4 text-danger"><i class="ph ph-trash custom-icons-i me-1"></i>Delete</button> option
                        <li>Confirm deletion in the pop-up dialog</li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- 3. Endorsement -->
        <div id="endorsement" class="card shadow-sm mb-4">
            <div class="card-header">
                <span class="fw-medium text-primary">3. Endorsement</span>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h5>HTE Assignment</h5>

                    <ol class="ps-4">
                        <li>Select HTE</li>
                        <li>
                            Choose interns with <span class="text-warning small text-bold">READY FOR DEPLOYMENT</span> status to endorse by selecting the 
                            <div class="form-check form-check-inline p-0 my-0 mx-1" ><input type="checkbox" class="form-check-input m-0"checked disabled> </div> checkbox
                        </li>                     
                        <li>Review selection then click <button type="button" class="btn btn-sm btn-primary mx-1" disabled><i class="ph-fill ph-paper-plane-tilt me-1"></i>Endorse</button></li>
                    </ol>
                </div>

            </div>
        </div>

        <!-- 4. Deployments -->
        <div id="deployments" class="card shadow-sm mb-4">
            <div class="card-header">
                <span class="fw-medium text-primary">4. Deployments</span>
            </div>
            <div class="card-body">
                <!-- Cancel Endorsement -->
                <div class="mb-4">
                    <h5>Cancel Endorsement</h5>
                    <p class="ps-4">If endorsement needs to be cancelled, select <button disabled class="btn btn-sm btn-outline-light rounded-4 text-danger mx-1"><i class="ph ph-x-circle custom-icons-i me-1"></i> Cancel Endorsement</button> to revert intern status to <span class="small text-warning text-bold">READY FOR DEPLOYMENT</span>.</p>
                </div>

                <!-- Initiate Deployment -->
                <div class="mb-4">
                    <h5>Initiate Deployment</h5>
                    <ol class="ps-5">
                        <li>To proceed with the deployment, click <button disabled class="btn btn-sm btn-info mx-1"><i class="ph ph-rocket-launch custom-icons-i me-1"></i>Initiate Deployment</button></li>
                        <li>Input deployment details (start date, hours required)</li>
                        <li>Verify deployment details, and select the checkbox to confirm</li>
                        <li><em>Upon completion, system generates endorsement letter and sends out student internship contracts via email.</em></li>
                    </ol>
                </div>

                <!-- Processing and Official Deployment -->
                <div>
                    <h5>Processing and Official Deployment</h5>
                    <p class="ps-4">Upon recieving the requirements (signed internship contracts, signed MOA, list of interns) finalize deployment by clicking  <button disabled class="btn btn-sm btn-success mx-1"><i class="ph-fill ph-seal-check custom-icons-i me-1"></i>Officially Deploy </button></p>
                </div>
            </div>
        </div>

        <!-- 5. Honorarium Requirements -->
        <div id="honorarium" class="card shadow-sm">
            <div class="card-header">
                <span class="fw-medium text-primary">5. Honorarium Requirements</span>
            </div>
            <div class="card-body">
                <h5><i class="fas fa-file-upload text-primary me-2"></i>Submission of Documents</h5>
                
                <!-- Required Documents -->
                <div class="mt-3 mb-4">
                    <h6><i class="fas fa-file-alt text-secondary me-2"></i>Required Documents:</h6>
                    <ul class="ps-4">
                        <li>Consolidated Notarized MOAs</li>
                        <li>Consolidated Notarized SICs</li>
                        <li>ANNEX C CMO104 Series of 2017</li>
                        <li>ANNEX D CMO104 Series of 2017</li>
                        <li>Honorarium Request</li>
                        <li>Special Order</li>
                        <li>Board Resolution</li>
                    </ul>
                </div>

                <!-- Submission Process -->
                <div>
                    <h6><i class="fas fa-tasks text-secondary me-2"></i>Submission Process:</h6>
                    <ol class="ps-4">
                        <li>Collect all required documents from intern</li>
                        <li>Verify completeness and signatures</li>
                        <li>Upload to the Honorarium module</li>
                        <li>Submit for processing and approval</li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- Back to Top Button -->
        <div class="text-center mt-4 mb-5">
            <a href="#" class="btn btn-outline-primary px-3 py-2 rounded-pill" id="backToTop">
                <i class="ph ph-arrow-up custom-icons-i me-2"></i>Back to Top
            </a>
        </div>
    </div>
</section>

<!-- COORDINATOR USER GUIDE -->
<script>
    $(document).ready(function() {
        // Smooth scrolling for table of contents links
        $('a[href^="#"]').on('click', function(e) {
            e.preventDefault();
            const target = $(this).attr('href');
            if (target === '#') return;
            
            $('html, body').animate({
                scrollTop: $(target).offset().top - 100
            }, 500);
        });

        // Back to top button
        $('#backToTop').on('click', function(e) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: 0
            }, 500);
        });

        // Show/hide back to top button
        $(window).on('scroll', function() {
            if ($(this).scrollTop() > 300) {
                $('#backToTop').fadeIn();
            } else {
                $('#backToTop').fadeOut();
            }
        });

        // Initialize back to top button as hidden
        $('#backToTop').hide();
    });
</script>

<style>
    /* Minimal custom CSS */
    #backToTop {
        position: relative;
        display: inline-block;
    }
    
    .card {
        scroll-margin-top: 80px;
    }
    
    @media print {
        #backToTop {
            display: none !important;
        }
    }
</style>

@endsection