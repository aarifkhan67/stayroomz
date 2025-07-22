@extends('layouts.app')
@section('title', 'Reset Password')
@section('content')

<div class="row justify-content-center auth-page">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow">
            <div class="card-header bg-success text-white text-center">
                <h3 class="card-title mb-0">Reset Password</h3>
                <p class="mb-0 small">Enter your new password</p>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                <form action="/reset-password" method="POST" id="resetPasswordForm">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    <input type="hidden" name="token" value="{{ $token }}">
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">New Password *</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required 
                               placeholder="Enter new password" minlength="6">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <i class="bi bi-shield-check me-1"></i>
                            Password must be at least 6 characters long.
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Confirm New Password *</label>
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                               id="password_confirmation" name="password_confirmation" required 
                               placeholder="Confirm new password">
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <i class="bi bi-check-circle me-1"></i>
                            Please confirm your new password.
                        </div>
                    </div>
                    
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-key me-2"></i>Reset Password
                        </button>
                    </div>
                </form>
                
                <hr class="my-4">
                
                <div class="text-center">
                    <p class="mb-2">Remember your password?</p>
                    <a href="/login" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Login
                    </a>
                </div>
                
                <div class="text-center mt-3">
                    <p class="mb-2">Need help?</p>
                    <a href="/forgot-password" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-question-circle me-2"></i>Request New Link
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const passwordConfirmation = document.getElementById('password_confirmation').value;
    
    if (!password) {
        e.preventDefault();
        alert('Please enter a new password.');
        return;
    }
    
    if (password.length < 6) {
        e.preventDefault();
        alert('Password must be at least 6 characters long.');
        return;
    }
    
    if (!passwordConfirmation) {
        e.preventDefault();
        alert('Please confirm your new password.');
        return;
    }
    
    if (password !== passwordConfirmation) {
        e.preventDefault();
        alert('Password confirmation does not match.');
        return;
    }
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Resetting...';
    submitBtn.disabled = true;
    
    // Re-enable after a delay (in case of validation errors)
    setTimeout(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }, 3000);
});

// Password strength indicator
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strengthIndicator = document.getElementById('password-strength');
    
    if (!strengthIndicator) {
        const indicator = document.createElement('div');
        indicator.id = 'password-strength';
        indicator.className = 'mt-2';
        this.parentNode.appendChild(indicator);
    }
    
    let strength = 0;
    let message = '';
    let color = '';
    
    if (password.length >= 6) strength++;
    if (password.match(/[a-z]/)) strength++;
    if (password.match(/[A-Z]/)) strength++;
    if (password.match(/[0-9]/)) strength++;
    if (password.match(/[^a-zA-Z0-9]/)) strength++;
    
    switch(strength) {
        case 0:
        case 1:
            message = 'Very Weak';
            color = 'text-danger';
            break;
        case 2:
            message = 'Weak';
            color = 'text-warning';
            break;
        case 3:
            message = 'Fair';
            color = 'text-info';
            break;
        case 4:
            message = 'Good';
            color = 'text-primary';
            break;
        case 5:
            message = 'Strong';
            color = 'text-success';
            break;
    }
    
    document.getElementById('password-strength').innerHTML = `
        <small class="${color}">
            <i class="bi bi-shield-${strength >= 4 ? 'check' : 'exclamation'} me-1"></i>
            Password strength: ${message}
        </small>
    `;
});
</script>
@endsection 