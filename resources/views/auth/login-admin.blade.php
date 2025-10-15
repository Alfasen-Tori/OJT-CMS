<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - OJT-CMS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/colors.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts.css') }}">
    <style>
        body {
            background: #900303;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }
        .custom-icons-i {
            font-size: 1.2rem;
            position: relative;
            top: 2px;
        }
        
        /* Background Pattern */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 80%, rgba(255,255,255,0.1) 2px, transparent 0),
                radial-gradient(circle at 80% 20%, rgba(255,255,255,0.08) 1px, transparent 0);
            background-size: 60px 60px, 40px 40px;
            z-index: 0;
        }
        
        .login-card {
            background: #fff;
            border-radius: 20px;
            border: none;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            position: relative;
            z-index: 1;
            overflow: hidden;
        }
        
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: #dc3545; /* Bootstrap danger red color */
        }
        
        .logo-container {
            position: relative;
            padding: 2rem 0 1rem;
        }
        
        .logo-img {
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.1));
            transition: transform 0.3s ease;
        }
        
        .logo-img:hover {
            transform: scale(1.05);
        }
        
        .portal-title {
            color: #dc3545; /* Bootstrap danger red color */
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .form-floating {
            margin-bottom: 1.5rem;
        }
        
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            padding-right: 3rem; /* Space for password toggle */
        }
        
        .form-control:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
            transform: translateY(-2px);
        }
        
        .form-control.is-invalid {
            padding-right: 3rem; /* Ensure consistent padding */
        }
        
        .form-label {
            color: #6c757d;
            font-weight: 500;
        }
        
        .btn-login {
            background: #dc3545; /* Bootstrap danger red color */
            border: none;
            border-radius: 12px;
            padding: 1rem 2rem;
            font-weight: 600;
            font-size: 1.1rem;
            color: #fff;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-login:hover {
            background: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(220, 53, 69, 0.4);
            color: #fff;
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            z-index: 5;
            padding: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .password-toggle:hover {
            color: #dc3545;
        }
        
        /* Hide password toggle when there's validation error */
        .is-invalid ~ .password-toggle {
            display: none;
        }
        
        .login-footer {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e9ecef;
            text-align: center;
        }
        
        .login-footer p {
            color: #6c757d;
            font-size: 0.9rem;
            margin: 0;
        }
        
        .format-helper {
            font-size: 0.8rem;
            color: #6c757d;
            margin-top: 0.25rem;
            display: none;
        }
        
        .form-control:focus + .format-helper {
            display: block;
        }
        
        @media (max-width: 768px) {
            .login-card {
                margin: 1rem;
                padding: 2rem 1.5rem !important;
            }
            
            .logo-img {
                height: 100px;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid d-flex justify-content-center align-items-center min-vh-100 p-3">
        <div class="login-card p-5 col-12 col-sm-10 col-md-8 col-lg-5 col-xl-4">
            <!-- Logo and Title -->
            <div class="logo-container text-center">
                <img class="logo-img mb-3" src="{{ asset('assets/images/OJT-CMS-logo.png') }}" alt="OJT-CMS Logo" height="120">
                <h2 class="portal-title">OJT-CMS</h2>
                <p class="text-muted mb-4">Admin Portal</p>
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('admin.authenticate') }}" class="w-100" id="loginForm">
                @csrf
                
                <!-- Faculty ID Field -->
                <div class="form-floating position-relative">
                    <input 
                        type="text" 
                        name="faculty_id" 
                        class="form-control @error('faculty_id') is-invalid @enderror" 
                        id="floatingFacultyId" 
                        placeholder="Faculty ID"
                        value="{{ old('faculty_id') }}"
                        required
                        pattern="[A-Za-z]\d{2}\d{2}\d{2}[A-Za-z][A-Za-z]" 
                        title="Please check your employee ID and try again."
                        maxlength="9"
                    >
                    <label for="floatingFacultyId">
                        <i class="ph ph-identification-card ph-icon me-1 custom-icons-i"></i>Faculty ID
                    </label>
                    <div class="format-helper">
                        Format: Letter + 6 digits + 2 letters (e.g., A090803GL)
                    </div>
                    @error('faculty_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="form-floating position-relative">
                    <input 
                        type="password" 
                        name="password" 
                        class="form-control @error('password') is-invalid @enderror" 
                        id="floatingPassword" 
                        placeholder="Password"
                        required
                    >
                    <label for="floatingPassword">
                        <i class="ph ph-lock me-1 custom-icons-i"></i>Password
                    </label>
                    <button type="button" class="password-toggle" id="passwordToggle">
                        <i class="ph-fill ph-eye ph-icon"></i>
                    </button>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Login Button -->
                <button type="submit" class="btn btn-login w-100 mt-4 mb-3">
                    <i class="ph-fill ph-sign-in ph-icon me-2"></i>Login to Portal
                </button>
            </form>

            <!-- Footer -->
            <div class="login-footer">
                <p class="text-muted">
                    &copy; {{ date('Y') }} OJT Coordination & Management System
                </p>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Phosphor Icons -->
    <script src="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2"></script>
    
    <script>
        // Password toggle functionality
        const passwordToggle = document.getElementById('passwordToggle');
        const passwordInput = document.getElementById('floatingPassword');
        
        passwordToggle.addEventListener('click', function() {
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('ph-eye');
                icon.classList.add('ph-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('ph-eye-slash');
                icon.classList.add('ph-eye');
            }
        });

        // Clear validation error and show password toggle when user starts typing
        passwordInput.addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) {
                this.classList.remove('is-invalid');
                // Find and remove the invalid feedback message
                const invalidFeedback = this.parentElement.querySelector('.invalid-feedback');
                if (invalidFeedback) {
                    invalidFeedback.remove();
                }
                // Show password toggle
                passwordToggle.style.display = 'flex';
            }
        });

        // Faculty ID formatting and validation
        document.getElementById('floatingFacultyId').addEventListener('input', function(e) {
            let value = e.target.value.toUpperCase();
            
            // Remove any non-alphanumeric characters
            value = value.replace(/[^A-Z0-9]/g, '');
            
            // Limit to 9 characters
            if (value.length > 9) {
                value = value.substring(0, 9);
            }
            
            // Auto-format as user types (optional visual aid)
            if (value.length >= 1) {
                // First character should be letter
                if (!/^[A-Z]/.test(value.charAt(0))) {
                    value = value.substring(1);
                }
            }
            
            if (value.length >= 3) {
                // Next 2 characters should be numbers (month)
                let monthPart = value.substring(1, 3);
                if (!/^\d{2}$/.test(monthPart)) {
                    value = value.substring(0, 1) + monthPart.replace(/\D/g, '') + value.substring(3);
                }
            }
            
            if (value.length >= 5) {
                // Next 2 characters should be numbers (date)
                let datePart = value.substring(3, 5);
                if (!/^\d{2}$/.test(datePart)) {
                    value = value.substring(0, 3) + datePart.replace(/\D/g, '') + value.substring(5);
                }
            }
            
            if (value.length >= 7) {
                // Next 2 characters should be numbers (year)
                let yearPart = value.substring(5, 7);
                if (!/^\d{2}$/.test(yearPart)) {
                    value = value.substring(0, 5) + yearPart.replace(/\D/g, '') + value.substring(7);
                }
            }
            
            if (value.length >= 8) {
                // Next character should be letter (last initial)
                let lastInitial = value.substring(7, 8);
                if (!/^[A-Z]$/.test(lastInitial)) {
                    value = value.substring(0, 7) + lastInitial.replace(/[^A-Z]/g, '') + value.substring(8);
                }
            }
            
            if (value.length >= 9) {
                // Last character should be letter (middle initial)
                let middleInitial = value.substring(8, 9);
                if (!/^[A-Z]$/.test(middleInitial)) {
                    value = value.substring(0, 8) + middleInitial.replace(/[^A-Z]/g, '');
                }
            }
            
            e.target.value = value;
        });

        // Clear faculty ID validation error when user starts typing
        const facultyInput = document.getElementById('floatingFacultyId');
        facultyInput.addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) {
                this.classList.remove('is-invalid');
                // Find and remove the invalid feedback message
                const invalidFeedback = this.parentElement.querySelector('.invalid-feedback');
                if (invalidFeedback) {
                    invalidFeedback.remove();
                }
            }
        });

        // Hide password toggle initially if there's a password error
        document.addEventListener('DOMContentLoaded', function() {
            const passwordField = document.getElementById('floatingPassword');
            if (passwordField.classList.contains('is-invalid')) {
                passwordToggle.style.display = 'none';
            }
            
            const facultyField = document.getElementById('floatingFacultyId');
            if (facultyField.classList.contains('is-invalid')) {
                // Ensure format helper is visible when there's an error
                facultyField.focus();
            }
        });
    </script>
</body>
</html>