<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Your Password - OJT-CMS</title>
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
        
        .password-card {
            background: #fff;
            border-radius: 20px;
            border: none;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            position: relative;
            z-index: 1;
            overflow: hidden;
        }
        
        .password-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            /* Dynamic color based on role */
            background: 
                @if($role === 'coordinator') #198754 
                @elseif($role === 'intern') #0d6efd 
                @else #ffc107 
            @endif;
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
            /* Dynamic color based on role */
            color: 
                @if($role === 'coordinator') #198754 
                @elseif($role === 'intern') #0d6efd 
                @else #ffc107 
            @endif;
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
            /* Dynamic border color based on role */
            border-color: 
                @if($role === 'coordinator') #198754 
                @elseif($role === 'intern') #0d6efd 
                @else #ffc107 
            @endif;
            box-shadow: 
                @if($role === 'coordinator') 0 0 0 0.2rem rgba(25, 135, 84, 0.25)
                @elseif($role === 'intern') 0 0 0 0.2rem rgba(13, 110, 253, 0.25)
                @else 0 0 0 0.2rem rgba(255, 193, 7, 0.25)
            @endif;
            transform: translateY(-2px);
        }
        
        .form-control.is-invalid {
            padding-right: 3rem; /* Ensure consistent padding */
        }
        
        .form-label {
            color: #6c757d;
            font-weight: 500;
        }
        
        .btn-setup {
            /* Dynamic button color based on role */
            background: 
                @if($role === 'coordinator') #198754 
                @elseif($role === 'intern') #0d6efd 
                @else #ffc107 
            @endif;
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
        
        .btn-setup:hover {
            /* Dynamic hover color based on role */
            background: 
                @if($role === 'coordinator') #157347 
                @elseif($role === 'intern') #0b5ed7 
                @else #ffca2c 
            @endif;
            transform: translateY(-2px);
            box-shadow: 
                @if($role === 'coordinator') 0 8px 25px rgba(25, 135, 84, 0.4)
                @elseif($role === 'intern') 0 8px 25px rgba(13, 110, 253, 0.4)
                @else 0 8px 25px rgba(255, 193, 7, 0.4)
            @endif;
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
            /* Dynamic hover color based on role */
            color: 
                @if($role === 'coordinator') #198754 
                @elseif($role === 'intern') #0d6efd 
                @else #ffc107 
            @endif;
        }
        
        /* Hide password toggle when there's validation error */
        .is-invalid ~ .password-toggle {
            display: none;
        }
        
        .password-footer {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e9ecef;
            text-align: center;
        }
        
        .password-footer p {
            color: #6c757d;
            font-size: 0.9rem;
            margin: 0;
        }
        
        .requirement-text {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 0.25rem;
        }
        
        @media (max-width: 768px) {
            .password-card {
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
        <div class="password-card p-5 col-12 col-sm-10 col-md-8 col-lg-5 col-xl-4">
            <!-- Logo and Title -->
            <div class="logo-container text-center">
                <img class="logo-img mb-3" src="{{ asset('assets/images/OJT-CMS logo.png') }}" alt="OJT-CMS Logo" height="120">
                <h2 class="portal-title">OJT-CMS</h2>
                <p class="text-muted mb-4">
                    @if($role === 'coordinator')
                        Coordinator Password Setup
                    @elseif($role === 'intern')
                        Intern Password Setup
                    @else
                        HTE Password Setup
                    @endif
                </p>
            </div>

            <!-- Error Messages -->
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Password Setup Form -->
            <form method="POST" action="{{ route('password.setup', ['token' => $token, 'role' => $role]) }}" class="w-100" id="passwordForm">
                @csrf
                
                <!-- New Password Field -->
                <div class="form-floating position-relative">
                    <input 
                        type="password" 
                        name="password" 
                        class="form-control rounded-4 @error('password') is-invalid @enderror" 
                        id="floatingPassword" 
                        placeholder="New Password"
                        required
                        autocomplete="new-password"
                    >
                    <label for="floatingPassword">
                        <i class="ph ph-lock me-1 custom-icons-i"></i>Set Password
                    </label>
                    <button type="button" class="password-toggle" id="passwordToggle">
                        <i class="ph-fill ph-eye ph-icon"></i>
                    </button>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password Field -->
                <div class="form-floating position-relative">
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        class="form-control rounded-4" 
                        id="floatingPasswordConfirm" 
                        placeholder="Confirm Password"
                        required
                        autocomplete="new-password"
                    >
                    <label for="floatingPasswordConfirm">
                        <i class="ph ph-lock me-1 custom-icons-i"></i>Confirm Password
                    </label>
                    <button type="button" class="password-toggle" id="confirmPasswordToggle">
                        <i class="ph-fill ph-eye ph-icon"></i>
                    </button>
                </div>

                <!-- Password Requirements -->
                <div class="requirement-text mb-3">
                    Must be at least 8 characters
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-setup w-100 mt-2 mb-3 py-3">
                    <i class="ph-fill ph-key ph-icon me-2"></i>Set Password
                </button>
            </form>

            <!-- Footer -->
            <div class="password-footer">
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
        // Password toggle functionality for new password field
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

        // Password toggle functionality for confirm password field
        const confirmPasswordToggle = document.getElementById('confirmPasswordToggle');
        const confirmPasswordInput = document.getElementById('floatingPasswordConfirm');
        
        confirmPasswordToggle.addEventListener('click', function() {
            const icon = this.querySelector('i');
            
            if (confirmPasswordInput.type === 'password') {
                confirmPasswordInput.type = 'text';
                icon.classList.remove('ph-eye');
                icon.classList.add('ph-eye-slash');
            } else {
                confirmPasswordInput.type = 'password';
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