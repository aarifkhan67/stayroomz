@extends('layouts.app')
@section('title', 'Sign Up')
@section('content')

<div class="row justify-content-center auth-page">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow">
            <div class="card-header bg-success text-white text-center">
                <h3 class="card-title mb-0">Join StayRoomz</h3>
                <p class="mb-0 small">Create your account to start listing rooms</p>
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
                
                <form action="/signup" method="POST" id="signupForm">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="user_type" class="form-label">I want to *</label>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="user_type" id="owner" value="owner" {{ old('user_type') == 'owner' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="owner">
                                        <i class="bi bi-house-fill text-primary me-2"></i>List Rooms
                                    </label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="user_type" id="renter" value="renter" {{ old('user_type') == 'renter' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="renter">
                                        <i class="bi bi-person-fill text-success me-2"></i>Find Rooms
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-text">
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                Choose whether you want to list rooms or find rooms to rent.
                            </small>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" 
                               required placeholder="Enter your full name">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                        <label for="phone" class="form-label">Phone Number *</label>
                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" name="phone" value="{{ old('phone') }}" 
                               required placeholder="+91 98765 43210">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password *</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required 
                               placeholder="Create a password" minlength="6">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <i class="bi bi-shield-check me-1"></i>
                            Password must be at least 6 characters long.
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password *</label>
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                               id="password_confirmation" name="password_confirmation" required 
                               placeholder="Confirm your password">
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4 form-check">
                        <input type="checkbox" class="form-check-input @error('terms') is-invalid @enderror" 
                               id="terms" name="terms" required>
                        <label class="form-check-label" for="terms">
                            I agree to the <a href="#" class="text-decoration-none">Terms and Conditions</a> *
                        </label>
                        @error('terms')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success btn-lg" id="signupBtn">
                            <i class="bi bi-person-plus me-2"></i>Create Account
                        </button>
                    </div>
                </form>
                
                <hr class="my-4">
                
                <div class="text-center">
                    <p class="mb-2">Already have an account?</p>
                    <a href="/login" class="btn btn-outline-primary">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.getElementById('signupForm').addEventListener('submit', function(e) {
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const password = document.getElementById('password').value;
    const passwordConfirmation = document.getElementById('password_confirmation').value;
    const userType = document.querySelector('input[name="user_type"]:checked');
    const terms = document.getElementById('terms').checked;
    
    if (!name) {
        e.preventDefault();
        alert('Please enter your full name.');
        return;
    }
    
    if (!email) {
        e.preventDefault();
        alert('Please enter your email address.');
        return;
    }
    
    if (!phone) {
        e.preventDefault();
        alert('Please enter your phone number.');
        return;
    }
    
    if (!userType) {
        e.preventDefault();
        alert('Please select whether you want to list rooms or find rooms.');
        return;
    }
    
    if (!password) {
        e.preventDefault();
        alert('Please enter a password.');
        return;
    }
    
    if (password.length < 6) {
        e.preventDefault();
        alert('Password must be at least 6 characters long.');
        return;
    }
    
    if (!passwordConfirmation) {
        e.preventDefault();
        alert('Please confirm your password.');
        return;
    }
    
    if (password !== passwordConfirmation) {
        e.preventDefault();
        alert('Password confirmation does not match.');
        return;
    }
    
    if (!terms) {
        e.preventDefault();
        alert('You must accept the terms and conditions.');
        return;
    }
    
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        e.preventDefault();
        alert('Please enter a valid email address.');
        return;
    }
    
    // Show loading state
    const submitBtn = document.getElementById('signupBtn');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Creating Account...';
    submitBtn.disabled = true;
    
    // Re-enable after a delay (in case of validation errors)
    setTimeout(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }, 3000);
});

// Phone number formatting
document.getElementById('phone').addEventListener('input', function() {
    let value = this.value.replace(/\D/g, '');
    if (value.length > 0) {
        if (value.length <= 5) {
            value = value.replace(/(\d{2})(\d{3})/, '+$1 $2');
        } else if (value.length <= 10) {
            value = value.replace(/(\d{2})(\d{3})(\d{5})/, '+$1 $2 $3');
        } else {
            value = value.replace(/(\d{2})(\d{3})(\d{5})(\d{1})/, '+$1 $2 $3 $4');
        }
    }
    this.value = value;
});
</script>
@endsection 