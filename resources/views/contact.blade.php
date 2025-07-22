@extends('layouts.app')
@section('title', 'Contact Us')
@section('content')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header Section -->
            <div class="text-center mb-5" data-aos="fade-up">
                <h1 class="display-4 fw-bold text-primary mb-3">Contact Us</h1>
                <p class="lead text-muted">Have questions? We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" data-aos="fade-up">
                    <i class="bi bi-check-circle me-2"></i>
                    <strong>Thank you!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Contact Form -->
            <div class="card shadow-lg" data-aos="fade-up" data-aos-delay="200">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">
                        <i class="bi bi-envelope me-2"></i>Send us a Message
                    </h3>
                </div>
                <div class="card-body p-4">
                    <form action="/contact" method="POST" id="contactForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" 
                                       placeholder="Enter your full name" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" 
                                       placeholder="your@email.com" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}" 
                                       placeholder="+91 98765 43210">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>Optional - for faster response
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="inquiry_type" class="form-label">Inquiry Type *</label>
                                <select class="form-select @error('inquiry_type') is-invalid @enderror" 
                                        id="inquiry_type" name="inquiry_type" required>
                                    <option value="">Select inquiry type</option>
                                    <option value="general" {{ old('inquiry_type') == 'general' ? 'selected' : '' }}>General Inquiry</option>
                                    <option value="technical" {{ old('inquiry_type') == 'technical' ? 'selected' : '' }}>Technical Support</option>
                                    <option value="support" {{ old('inquiry_type') == 'support' ? 'selected' : '' }}>Customer Support</option>
                                    <option value="business" {{ old('inquiry_type') == 'business' ? 'selected' : '' }}>Business Partnership</option>
                                    <option value="other" {{ old('inquiry_type') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('inquiry_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject *</label>
                            <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                                   id="subject" name="subject" value="{{ old('subject') }}" 
                                   placeholder="Brief subject of your inquiry" required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="message" class="form-label">Message *</label>
                            <textarea class="form-control @error('message') is-invalid @enderror" 
                                      id="message" name="message" rows="5" 
                                      placeholder="Please describe your inquiry in detail..." required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>Maximum 2000 characters
                                <span class="float-end">
                                    <span id="charCount">0</span>/2000
                                </span>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                <i class="bi bi-send me-2"></i>Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="row mt-5">
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-envelope-fill text-primary fs-4"></i>
                            </div>
                            <h5 class="card-title">Email Us</h5>
                            <p class="card-text text-muted">Get in touch via email</p>
                            <a href="mailto:info@stayroomz.com" class="btn btn-outline-primary btn-sm">
                                info@stayroomz.com
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-telephone-fill text-success fs-4"></i>
                            </div>
                            <h5 class="card-title">Call Us</h5>
                            <p class="card-text text-muted">Speak to our team</p>
                            <a href="tel:+918078636367" class="btn btn-outline-success btn-sm">
                                +91 80786 36367
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="500">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-clock-fill text-info fs-4"></i>
                            </div>
                            <h5 class="card-title">Response Time</h5>
                            <p class="card-text text-muted">We typically respond within</p>
                            <span class="badge bg-info">24 Hours</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="mt-5" data-aos="fade-up" data-aos-delay="600">
                <h3 class="text-center mb-4">Frequently Asked Questions</h3>
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq1">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                                How do I list my room on StayRoomz?
                            </button>
                        </h2>
                        <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Simply click on "List Your Room" in the navigation bar, create an account if you haven't already, and fill out the room details form. It's completely free to list your room!
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                                Is it safe to rent through StayRoomz?
                            </button>
                        </h2>
                        <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Yes! We verify all room listings and provide secure communication channels. We also have a review system to help you make informed decisions.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                                How much does it cost to list a room?
                            </button>
                        </h2>
                        <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Listing rooms on StayRoomz is completely free! We don't charge any listing fees or commissions.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contactForm');
    const messageTextarea = document.getElementById('message');
    const charCount = document.getElementById('charCount');
    const submitBtn = document.getElementById('submitBtn');

    // Character counter
    messageTextarea.addEventListener('input', function() {
        const length = this.value.length;
        charCount.textContent = length;
        
        if (length > 1800) {
            charCount.classList.add('text-warning');
        } else {
            charCount.classList.remove('text-warning');
        }
        
        if (length > 2000) {
            charCount.classList.add('text-danger');
            this.value = this.value.substring(0, 2000);
        } else {
            charCount.classList.remove('text-danger');
        }
    });

    // Form validation
    form.addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const subject = document.getElementById('subject').value.trim();
        const message = document.getElementById('message').value.trim();
        const inquiryType = document.getElementById('inquiry_type').value;

        let isValid = true;
        let errorMessage = '';

        if (!name) {
            errorMessage = 'Please enter your name.';
            isValid = false;
        } else if (!email) {
            errorMessage = 'Please enter your email address.';
            isValid = false;
        } else if (!isValidEmail(email)) {
            errorMessage = 'Please enter a valid email address.';
            isValid = false;
        } else if (!subject) {
            errorMessage = 'Please enter a subject.';
            isValid = false;
        } else if (!message) {
            errorMessage = 'Please enter your message.';
            isValid = false;
        } else if (!inquiryType) {
            errorMessage = 'Please select an inquiry type.';
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            alert(errorMessage);
            return;
        }

        // Show loading state
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Sending...';
        submitBtn.disabled = true;

        // Re-enable after a delay (in case of validation errors)
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 5000);
    });

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Phone number formatting
    const phoneInput = document.getElementById('phone');
    phoneInput.addEventListener('input', function() {
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
});
</script>
@endsection 