<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - OJT-CMS</title>
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
            display: flex;
            align-items: center;
            padding: 1rem;
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
        
        .forgot-card {
            background: #fff;
            border-radius: 24px;
            border: none;
            box-shadow: 0 25px 70px rgba(0,0,0,0.25);
            position: relative;
            z-index: 1;
            overflow: hidden;
            max-width: 500px;
            margin: 0 auto;
        }
        
        .forgot-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 8px;
            background: linear-gradient(90deg, #0dcaf0 0%, #0aa2c0 100%);
        }
        
        .logo-container {
            position: relative;
            padding: 2.5rem 0 1.5rem;
        }
        
        .logo-img {
            filter: drop-shadow(0 8px 16px rgba(0,0,0,0.15));
            transition: transform 0.3s ease;
            max-width: 140px;
            height: auto;
        }
        
        .logo-img:hover {
            transform: scale(1.05);
        }
        
        .portal-title {
            color: #0dcaf0;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .icon-container {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #0dcaf0 0%, #0aa2c0 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 8px 25px rgba(13, 202, 240, 0.3);
        }
        
        .icon-container i {
            font-size: 2rem;
            color: white;
        }
        
        .instruction-text {
            color: #6c757d;
            text-align: center;
            line-height: 1.6;
            margin-bottom: 2rem;
            font-size: 1rem;
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
        }
        
        .form-control:focus {
            border-color: #0dcaf0;
            box-shadow: 0 0 0 0.25rem rgba(13, 202, 240, 0.25);
            transform: translateY(-1px);
        }
        
        .form-label {
            color: #6c757d;
            font-weight: 500;
        }
        
        .btn-reset {
            background: linear-gradient(135deg, #0dcaf0 0%, #0aa2c0 100%);
            border: none;
            border-radius: 12px;
            padding: 1rem 2rem;
            font-weight: 600;
            font-size: 1.1rem;
            color: white;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            width: 100%;
            margin-top: 0.5rem;
        }
        
        .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(13, 202, 240, 0.4);
            color: white;
        }
        
        .btn-back {
            background: transparent;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 1rem 2rem;
            font-weight: 600;
            font-size: 1.1rem;
            color: #6c757d;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 1rem;
        }
        
        .btn-back:hover {
            border-color: #0dcaf0;
            color: #0dcaf0;
            transform: translateY(-1px);
        }
        
        .forgot-footer {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e9ecef;
            text-align: center;
        }
        
        .forgot-footer p {
            color: #6c757d;
            font-size: 0.9rem;
            margin: 0;
        }
        
        .success-message {
            background: linear-gradient(135deg, #d1e7dd 0%, #a3cfbb 100%);
            border: none;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        
        .success-message i {
            color: #198754;
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        
        @media (max-width: 768px) {
            .forgot-card {
                margin: 1rem;
                padding: 2rem 1.5rem !important;
            }
            
            .logo-img {
                max-width: 120px;
            }
            
            .icon-container {
                width: 70px;
                height: 70px;
            }
        }
        
        @media (max-width: 576px) {
            body {
                padding: 0.5rem;
            }
            
            .forgot-card {
                border-radius: 16px;
            }
        }
        
        /* Loading animation */
        .btn-reset.loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container-fluid d-flex justify-content-center align-items-center min-vh-100">
        <div class="forgot-card px-5 py-3 col-12 col-sm-10 col-md-8 col-lg-5">
            <!-- Logo and Title -->
            <div class="logo-container text-center">
                <h2 class="portal-title">Forgot Password</h2>
                <p class="text-muted mb-4">Enter your email to receive reset instructions</p>
            </div>

            <!-- Success Message (shown after form submission) -->
            @if(session('status'))
                <div class="success-message">
                    <i class="fas fa-check-circle"></i>
                    <h4 class="text-success">Check Your Email</h4>
                    <p class="mb-0">{{ session('status') }}</p>
                </div>
            @endif

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

            <!-- Forgot Password Form -->
            <form method="POST" action="{{ route('password.email') }}" class="w-100" id="forgotForm">
                @csrf
                
                <!-- Email Icon -->
                <div class="icon-container">
                    <i class="ph ph-envelope ph-icon"></i>
                </div>
                
                <p class="small text-muted text-center">
                    Enter the email address associated with your account and we'll send you a link to reset your password.
                </p>

                <!-- Email Field -->
                <div class="form-floating">
                    <input 
                        type="email" 
                        name="email" 
                        class="form-control @error('email') is-invalid @enderror" 
                        id="floatingEmail" 
                        placeholder="name@example.com"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        autofocus
                    >
                    <label for="floatingEmail">
                        <i class="ph ph-envelope ph-icon me-1"></i>Email Address
                    </label>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-reset" id="submitBtn">
                    <i class="ph ph-paper-plane-tilt ph-icon me-2"></i>Send Reset Link
                </button>

            </form>

            <!-- Footer -->
            <div class="forgot-footer">
                <p class="text-muted">
                    <a href="/" class="text-decoration-none link-secondary small"><i class="ph ph-arrow-left custom-icons-i me-1"></i>Return to Main Menu</a>
                </p>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Phosphor Icons -->
    <script src="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('forgotForm');
            const submitBtn = document.getElementById('submitBtn');
            
            form.addEventListener('submit', function(e) {
                // Show loading state
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
                submitBtn.innerHTML = 'Sending...';
            });
            
            // Clear validation error when user starts typing
            const emailInput = document.getElementById('floatingEmail');
            emailInput.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) {
                    this.classList.remove('is-invalid');
                    const invalidFeedback = this.parentElement.querySelector('.invalid-feedback');
                    if (invalidFeedback) {
                        invalidFeedback.remove();
                    }
                }
            });
        });
    </script>
</body>
</html>