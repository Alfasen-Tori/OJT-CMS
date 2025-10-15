<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Your Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('assets/colors.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts.css') }}">
</head>
<body style="background: #900303;">
    <div class="container-fluid p-md-0 p-lg-5 d-flex justify-content-center align-items-center flex-column w-100" style="height: 100vh;">
        <div class="card rounded-4 p-5 d-flex flex-column align-items-center justify-content-center col-12 col-sm-8 col-md-6 col-lg-5" style="background: #fff;">
            <img class="mb-4" src="{{ asset('assets/images/ojt-cms-logo.png') }}" alt="" height="120" style="position: relative; right: 4px">
            <h3>OJT-CMS</h3>
            <p class="text-muted mb-5">
                @if($role === 'coordinator')
                    Coordinator Password Setup
                @elseif($role === 'intern')
                    Intern Password Setup
                @else
                    HTE Password Setup
            @endif
                </p>

                        @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('password.setup', ['token' => $token, 'role' => $role]) }}" class="w-100">
                @csrf
                
                <div class="form-floating mb-3">
                    <input 
                        type="password" 
                        name="password" 
                        class="form-control rounded-4 @error('password') is-invalid @enderror" 
                        id="floatingPassword" 
                        placeholder="New Password"
                        required
                        autocomplete="new-password"
                    >
                    <label for="floatingPassword">Set Password</label>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating mb-3">
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        class="form-control rounded-4" 
                        id="floatingPasswordConfirm" 
                        placeholder="Confirm Password"
                        required
                        autocomplete="new-password"
                    >
                    <label for="floatingPasswordConfirm">Confirm Password</label>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-text">
                        Must be at least 8 characters
                    </div>
                </div>

                <button type="submit" class="btn w-100 btn-gold rounded-4 mt-2 text-white mb-3 py-3">
                    Set Password
                </button>
            </form>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>