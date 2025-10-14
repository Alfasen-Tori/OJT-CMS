<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login - OJT-CMS</title>
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
            background: #0d6efd;
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
            color: #0d6efd;
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
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
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
            background: #0d6efd;
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
            background: #0b5ed7;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(13, 110, 253, 0.4);
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
            color: #0d6efd;
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
                <img class="logo-img mb-3" src="{{ asset('assets/images/ojt-cms logo.png') }}" alt="OJT-CMS Logo" height="120">
                <h2 class="portal-title">OJT-CMS</h2>
                <p class="text-muted mb-4">Student-Intern Portal</p>
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('intern.authenticate') }}" class="w-100" id="loginForm">
                @csrf
                
                <!-- Student ID Field -->
                <div class="form-floating position-relative">
                    <input 
                        type="text" 
                        name="student_id" 
                        class="form-control @error('student_id') is-invalid @enderror" 
                        id="floatingStudentId" 
                        placeholder="XXXX-XXXXX"
                        value="{{ old('student_id') }}"
                        required
                        pattern="\d{4}-\d{5}" 
                        title="Student ID should be in the format: XXXX-XXXXX (e.g., 2013-05673)"
                    >
                    <label for="floatingStudentId">
                        <i class="ph ph-graduation-cap ph-icon me-1 custom-icons-i"></i>Student ID
                    </label>
                    @error('student_id')
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

        // Input formatting for Student ID
        document.getElementById('floatingStudentId').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 4) {
                value = value.substring(0, 4) + '-' + value.substring(4, 9);
            }
            e.target.value = value;
        });

        // Hide password toggle initially if there's a password error
        document.addEventListener('DOMContentLoaded', function() {
            const passwordField = document.getElementById('floatingPassword');
            if (passwordField.classList.contains('is-invalid')) {
                passwordToggle.style.display = 'none';
            }
        });
    </script>
</body>
</html>