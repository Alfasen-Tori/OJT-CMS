<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Portal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/colors.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts.css') }}">
    <style>
        .login-card {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            height: 100%;
            cursor: pointer;
            border: none;
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        }
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--card-color), transparent);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }
        .login-card:hover::before {
            transform: scaleX(1);
        }
        .login-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        .card-icon {
            font-size: 3rem;
            margin-bottom: 1.5rem;
            transition: transform 0.3s ease;
            display: inline-block;
            padding: 10px;
        }
        .login-card:hover .card-icon {
            transform: scale(1.05);
        }
        .logo-container {
            max-width: 100%;
            height: auto;
        }
        .logo-img {
            max-width: 100%;
            height: auto;
            max-height: 280px;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.1));
        }
        .card-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            background: rgba(255,255,255,0.9);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            opacity: 0;
            transform: translateY(-10px);
            transition: all 0.3s ease;
        }
        .login-card:hover .card-badge {
            opacity: 1;
            transform: translateY(0);
        }
        .role-description {
            font-size: 0.9rem;
            opacity: 0.8;
            transition: opacity 0.3s ease;
        }
        .login-card:hover .role-description {
            opacity: 1;
        }
        .card-content {
            position: relative;
            z-index: 2;
            padding: 2rem 1.5rem;
        }
        .card-bg-pattern {
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            opacity: 0.03;
            background: var(--card-color);
            border-radius: 50%;
            transform: translate(30px, -30px);
            transition: all 0.3s ease;
        }
        .login-card:hover .card-bg-pattern {
            transform: translate(20px, -20px) scale(1.2);
            opacity: 0.05;
        }
        .welcome-text {
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        /* Background Pattern */
        .bg-pattern {
            background-color: #720201;
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 2px, transparent 0),
                radial-gradient(circle at 75% 75%, rgba(255,255,255,0.08) 1px, transparent 0);
            background-size: 50px 50px, 30px 30px;
            background-position: 0 0, 25px 25px;
        }
        
        /* Footer */
        .footer {
            background: rgba(0,0,0,0.2);
            backdrop-filter: blur(10px);
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        
        @media (max-width: 768px) {
            .logo-img {
                max-height: 200px;
            }
            .login-card {
                margin-bottom: 1rem;
            }
            .card-content {
                padding: 1.5rem 1rem;
            }
        }
        
        /* Equal height columns for logo and cards */
        .main-container {
            min-height: calc(100vh - 80px);
        }
    </style>
</head>
<body class="bg-pattern">

    <!-- Animated Background Elements -->
    <div class="position-absolute w-100 h-100" style="overflow: hidden; z-index: 0;">
        <div class="position-absolute" style="top: 10%; left: 5%; width: 100px; height: 100px; background: rgba(255,255,255,0.03); border-radius: 50%;"></div>
        <div class="position-absolute" style="bottom: 20%; right: 10%; width: 150px; height: 150px; background: rgba(255,255,255,0.02); border-radius: 50%;"></div>
        <div class="position-absolute" style="top: 40%; right: 20%; width: 80px; height: 80px; background: rgba(255,255,255,0.04); border-radius: 50%;"></div>
    </div>

    <div class="container-fluid py-4 position-relative main-container" style="z-index: 1;">
        <div class="row min-vh-100 align-items-center justify-content-center">
            
            <!-- Logo Section - Equal proportion to cards -->
            <div class="col-xxl-5 col-lg-5 col-md-10 mb-5 mb-lg-0">
                <div class="logo-container p-4 p-lg-5 text-center text-lg-start h-100 d-flex flex-column justify-content-center">
                    <div class="mb-4">
                        <img src="{{ asset('assets/images/ojtcms_logo3.png') }}" alt="OJT CMS Logo" class="logo-img mb-4">
                    </div>
                    <div>
                        <h1 class="text-white welcome-text display-4 fw-bold mb-3">OJT Coordination & Management System</h1>
                        <p class="text-white-50 lead mb-0">Streamlining internship programs for educational excellence</p>
                    </div>
                </div>
            </div>
            
            <!-- Login Cards Section - Equal proportion to logo -->
            <div class="col-xxl-5 col-lg-6 col-md-10">
                <div class="row g-4 justify-content-center bg-warningr px-2">
                    
                    <!-- Intern Card -->
                    <div class="col-xl-6 col-md-6 col-12">
                        <a href="intern/login" class="text-decoration-none">
                            <div class="card login-card" style="--card-color: #0d6efd;">
                                <div class="card-bg-pattern"></div>
                                <div class="card-content">
                                    <span class="card-badge" style="color: #0d6efd;">STUDENT</span>
                                    <div class="card-icon text-primary">
                                        <i class="ph-fill ph-graduation-cap"></i>
                                    </div>
                                    <h3 class="card-title fw-bold text-dark mb-2">Intern</h3>
                                    <p class="role-description text-muted mb-0">
                                        Access your internship dashboard, track progress, and submit requirements
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <!-- Coordinator Card -->
                    <div class="col-xl-6 col-md-6 col-12">
                        <a href="coordinator/login" class="text-decoration-none">
                            <div class="card login-card" style="--card-color: #198754;">
                                <div class="card-bg-pattern"></div>
                                <div class="card-content">
                                    <span class="card-badge" style="color: #198754;">FACULTY</span>
                                    <div class="card-icon text-success">
                                        <i class="ph-fill ph-chalkboard-teacher"></i>
                                    </div>
                                    <h3 class="card-title fw-bold text-dark mb-2">Coordinator</h3>
                                    <p class="role-description text-muted mb-0">
                                        Manage interns, HTE partnerships, and oversee internship programs
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <!-- HTE Card -->
                    <div class="col-xl-6 col-md-6 col-12">
                        <a href="hte/login" class="text-decoration-none">
                            <div class="card login-card" style="--card-color: #ffc107;">
                                <div class="card-bg-pattern"></div>
                                <div class="card-content">
                                    <span class="card-badge" style="color: #ffc107;">PARTNER</span>
                                    <div class="card-icon text-warning">
                                        <i class="ph-fill ph-building-apartment"></i>
                                    </div>
                                    <h3 class="card-title fw-bold text-dark mb-2">HTE</h3>
                                    <p class="role-description text-muted mb-0">
                                        Host Training Establishment portal for intern supervision and management
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <!-- Admin Card -->
                    <div class="col-xl-6 col-md-6 col-12">
                        <a href="/admin/login" class="text-decoration-none">
                            <div class="card login-card" style="--card-color: #dc3545;">
                                <div class="card-bg-pattern"></div>
                                <div class="card-content">
                                    <span class="card-badge" style="color: #dc3545;">SYSTEM</span>
                                    <div class="card-icon text-danger">
                                        <i class="ph-fill ph-shield-checkered"></i>
                                    </div>
                                    <h3 class="card-title fw-bold text-dark mb-2">Admin</h3>
                                    <p class="role-description text-muted mb-0">
                                        System administration, user management, and platform configuration
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>

                </div>
                
                <!-- Footer Note -->
                <div class="row mt-5">
                    <div class="col-12 text-center">
                        <p class="text-white-50 small mb-0">
                            Select your role to access the OJT Coordination & Management System
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer py-3 mt-auto">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-center">
                    <p class="text-white-50 small mb-0">
                        &copy; {{ date('Y') }} OJT Coordination & Management System. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Phosphor Icons -->
    <script src="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2"></script>
    
    <script>
        // Add subtle animation to cards on page load
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.login-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 150);
            });
        });
    </script>
</body>
</html>