@extends('layouts.app')
@section('title', 'Login')
@section('content')

<div class="row justify-content-center auth-page">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">
                <h3 class="card-title mb-0">Login to StayRoomz</h3>
                <p class="mb-0 small">Access your account dashboard</p>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form action="/login" method="POST" id="loginForm">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="user_type" class="form-label">I am a *</label>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="user_type" id="owner" value="owner" {{ old('user_type') == 'owner' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="owner">
                                        <i class="bi bi-house-fill text-primary me-2"></i>Room Owner
                                    </label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="user_type" id="renter" value="renter" {{ old('user_type') == 'renter' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="renter">
                                        <i class="bi bi-person-fill text-success me-2"></i>Renter
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-text">
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                Room Owners can list and manage rooms. Renters can browse and favorite rooms.
                            </small>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address *</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" 
                               required placeholder="your@email.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password *</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required placeholder="Enter your password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg" id="loginBtn">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Login
                        </button>
                    </div>
                </form>
                
                <hr class="my-4">
                
                <div class="text-center">
                    <p class="mb-2">Don't have an account?</p>
                    <a href="/signup" class="btn btn-outline-primary">
                        <i class="bi bi-person-plus me-2"></i>Create Account
                    </a>
                </div>
                
                <div class="text-center mt-3">
                    <a href="/forgot-password" class="text-muted small">
                        <i class="bi bi-question-circle me-1"></i>Forgot your password?
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.getElementById('loginForm').addEventListener('submit', function(e) {
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const userType = document.querySelector('input[name="user_type"]:checked');
    
    if (!email || !password) {
        e.preventDefault();
        alert('Please fill in all required fields.');
        return;
    }
    
    if (!userType) {
        e.preventDefault();
        alert('Please select your user type (Room Owner or Renter).');
        return;
    }
    
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        e.preventDefault();
        alert('Please enter a valid email address.');
        return;
    }
    
    // Show loading state
    const submitBtn = document.getElementById('loginBtn');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Logging in...';
    submitBtn.disabled = true;
    
    // Re-enable after a delay (in case of validation errors)
    setTimeout(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }, 3000);
});

window.addEventListener('load', function() {
    const savedEmail = localStorage.getItem('loginEmail');
    if (savedEmail) {
        document.getElementById('email').value = savedEmail;
        document.getElementById('remember').checked = true;
    }
});

document.getElementById('loginForm').addEventListener('submit', function() {
    if (document.getElementById('remember').checked) {
        localStorage.setItem('loginEmail', document.getElementById('email').value);
    } else {
        localStorage.removeItem('loginEmail');
    }
});
</script>
@endsection 