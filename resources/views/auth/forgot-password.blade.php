@extends('layouts.app')
@section('title', 'Forgot Password')
@section('content')

<div class="row justify-content-center auth-page">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">
                <h3 class="card-title mb-0">Forgot Password</h3>
                <p class="mb-0 small">Enter your email to reset your password</p>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                <form action="/forgot-password" method="POST" id="forgotPasswordForm">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="email" class="form-label">Email Address *</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" required 
                               placeholder="your@email.com" value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            We'll send you a password reset link to this email address.
                        </div>
                    </div>
                    
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-envelope me-2"></i>Send Reset Link
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
                    <p class="mb-2">Don't have an account?</p>
                    <a href="/signup" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-person-plus me-2"></i>Create Account
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.getElementById('forgotPasswordForm').addEventListener('submit', function(e) {
    const email = document.getElementById('email').value;
    
    if (!email) {
        e.preventDefault();
        alert('Please enter your email address.');
        return;
    }
    
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        e.preventDefault();
        alert('Please enter a valid email address.');
        return;
    }
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Sending...';
    submitBtn.disabled = true;
    
    // Re-enable after a delay (in case of validation errors)
    setTimeout(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }, 3000);
});
</script>
@endsection 