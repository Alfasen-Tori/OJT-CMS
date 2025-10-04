<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>

    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('assets/colors.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts.css') }}">


</head>
<body style="background: #900303;">
    <div class="container-fluid p-md-0 p-lg-5 d-flex justify-content-center align-items-center flex-column w-100" style="height: 100vh;">
    <div class="card rounded-4 p-5 d-flex flex-column align-items-center justify-content-center col-12 col-sm-8 col-md-6 col-lg-5" style="background: #fff;">
        <img class="mb-4" src="{{ asset('assets/images/ojt-cms logo.png') }}" alt="" height="120" style="position: relative; right: 4px">
        <h3>OJT-CMS</h3>
        <p class="text-muted mb-5">Admin Portal</p>

        <form method="POST" action="{{ route('admin.authenticate') }}" class="w-100">
            @csrf
            <div class="form-floating mb-3">
                <input 
                    type="text" 
                    name="faculty_id" 
                    class="form-control rounded-4 @error('faculty_id') is-invalid @enderror" 
                    id="floatingFacultyId" 
                    placeholder="Faculty ID"
                    value="{{ old('faculty_id') }}"
                    required
                    pattern="[A-Za-z]\d{2}\d{2}\d{2}[A-Za-z][A-Za-z]" 
                    title="Please check your employee ID and try again."
                    maxlength="9"
                >
                <label for="floatingFacultyId">Faculty ID</label>
                @error('faculty_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <!-- <div class="form-text">
                    Format: FirstInitial+Month+Date+Year+LastInitial+MiddleInitial (e.g., A090803GL)
                </div> -->
            </div>
            <div class="form-floating mb-3">
                <input 
                    type="password" 
                    name="password" 
                    class="form-control rounded-4 @error('password') is-invalid @enderror" 
                    id="floatingPassword" 
                    placeholder="Password"
                    required
                >
                <label for="floatingPassword">Password</label>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <!-- <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label text-small text-muted" for="remember">
                    Remember Me
                </label>
            </div> -->
            <button type="submit" class="btn w-100 btn-gold rounded-4 mt-4 text-white mb-3 py-3">
                Login
            </button>
        </form>
    </div>
    </div>
</body>

<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Optional: Add real-time validation and formatting
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

// Add input event to show format helper
document.getElementById('floatingFacultyId').addEventListener('focus', function() {
    const helper = document.createElement('div');
    this.parentNode.appendChild(helper);
    
    this.addEventListener('blur', function() {
        if (helper.parentNode) {
            helper.parentNode.removeChild(helper);
        }
    }, { once: true });
});
</script>

</html>