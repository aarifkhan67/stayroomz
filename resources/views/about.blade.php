@extends('layouts.app')
@section('title', 'About Us')

@section('content')
<!-- Hero Section -->
<section class="hero-section text-white py-5" data-aos="fade-up">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <h1 class="display-4 fw-bold mb-4">About StayRoomz</h1>
                <p class="lead mb-4">Connecting people with perfect spaces since 2024. We're revolutionizing the way people find and rent rooms, making the process simple, transparent, and stress-free.</p>
                <div class="d-flex gap-3">
                    <div class="text-center">
                        <h3 class="fw-bold">500+</h3>
                        <small>Happy Renters</small>
                    </div>
                    <div class="text-center">
                        <h3 class="fw-bold">200+</h3>
                        <small>Verified Owners</small>
                    </div>
                    <div class="text-center">
                        <h3 class="fw-bold">1000+</h3>
                        <small>Rooms Listed</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="text-center">
                    <img src="/images/logo.png" alt="StayRoomz" class="img-fluid" style="max-height: 200px;">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Our Story Section -->
<section class="py-5 bg-light" data-aos="fade-up">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <h2 class="fw-bold mb-4">Our Story</h2>
                <p class="lead text-muted mb-4">StayRoomz was born from a simple observation: finding the right room to rent shouldn't be complicated.</p>
                <p class="mb-4">In 2024, our founders experienced firsthand the frustration of searching for affordable, quality accommodation. The process was often opaque, time-consuming, and filled with uncertainty. That's when we decided to create a platform that would change everything.</p>
                <p class="mb-4">Today, StayRoomz has grown from a simple idea into a trusted community where thousands of people find their perfect living space. We've built a platform that prioritizes transparency, security, and user experience above all else.</p>
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-house-heart fs-4"></i>
                    </div>
                    <div>
                        <h5 class="mb-1">Trusted by Thousands</h5>
                        <small class="text-muted">Join our growing community</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-shield-check text-primary fs-1 mb-3"></i>
                                <h5>Verified Listings</h5>
                                <p class="text-muted small">Every room is verified for quality and safety</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-people text-primary fs-1 mb-3"></i>
                                <h5>Community Driven</h5>
                                <p class="text-muted small">Built by users, for users</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-lightning text-primary fs-1 mb-3"></i>
                                <h5>Instant Booking</h5>
                                <p class="text-muted small">Book your room in minutes, not days</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-headset text-primary fs-1 mb-3"></i>
                                <h5>24/7 Support</h5>
                                <p class="text-muted small">We're here when you need us</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission & Values Section -->
<section class="py-5" data-aos="fade-up">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mb-5" data-aos="fade-right">
                <h2 class="fw-bold mb-4">Our Mission</h2>
                <p class="lead mb-4">To democratize access to quality accommodation by creating a transparent, efficient, and user-friendly platform that connects people with their perfect living spaces.</p>
                <div class="d-flex align-items-start gap-3 mb-3">
                    <i class="bi bi-check-circle-fill text-success fs-5 mt-1"></i>
                    <div>
                        <h6 class="fw-bold">Transparency First</h6>
                        <p class="text-muted small mb-0">Clear pricing, honest reviews, and detailed room information</p>
                    </div>
                </div>
                <div class="d-flex align-items-start gap-3 mb-3">
                    <i class="bi bi-check-circle-fill text-success fs-5 mt-1"></i>
                    <div>
                        <h6 class="fw-bold">Community Safety</h6>
                        <p class="text-muted small mb-0">Verified users, secure payments, and 24/7 support</p>
                    </div>
                </div>
                <div class="d-flex align-items-start gap-3">
                    <i class="bi bi-check-circle-fill text-success fs-5 mt-1"></i>
                    <div>
                        <h6 class="fw-bold">User Experience</h6>
                        <p class="text-muted small mb-0">Intuitive design that makes finding rooms effortless</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <h2 class="fw-bold mb-4">Our Values</h2>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="bi bi-heart text-primary fs-4"></i>
                                </div>
                                <h6 class="fw-bold">Empathy</h6>
                                <p class="text-muted small">We understand the challenges of finding accommodation and design solutions with real users in mind.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="bi bi-shield-check text-success fs-4"></i>
                                </div>
                                <h6 class="fw-bold">Trust</h6>
                                <p class="text-muted small">Building a safe, reliable platform where users can confidently make decisions about their living arrangements.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="bi bi-lightbulb text-warning fs-4"></i>
                                </div>
                                <h6 class="fw-bold">Innovation</h6>
                                <p class="text-muted small">Continuously improving our platform with cutting-edge technology and user feedback.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="bi bi-people text-info fs-4"></i>
                                </div>
                                <h6 class="fw-bold">Community</h6>
                                <p class="text-muted small">Fostering connections between renters and owners, creating meaningful relationships beyond transactions.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="py-5 bg-primary text-white" data-aos="fade-up">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-3 col-md-6 mb-4" data-aos="zoom-in" data-aos-delay="100">
                <div class="d-flex flex-column align-items-center">
                    <h2 class="display-4 fw-bold mb-2">500+</h2>
                    <p class="mb-0">Happy Renters</p>
                    <small class="text-light">Finding their perfect space</small>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4" data-aos="zoom-in" data-aos-delay="200">
                <div class="d-flex flex-column align-items-center">
                    <h2 class="display-4 fw-bold mb-2">200+</h2>
                    <p class="mb-0">Verified Owners</p>
                    <small class="text-light">Trusted property managers</small>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4" data-aos="zoom-in" data-aos-delay="300">
                <div class="d-flex flex-column align-items-center">
                    <h2 class="display-4 fw-bold mb-2">1000+</h2>
                    <p class="mb-0">Rooms Listed</p>
                    <small class="text-light">Quality accommodations</small>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4" data-aos="zoom-in" data-aos-delay="400">
                <div class="d-flex flex-column align-items-center">
                    <h2 class="display-4 fw-bold mb-2">4.8★</h2>
                    <p class="mb-0">Average Rating</p>
                    <small class="text-light">From verified users</small>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="py-5" data-aos="fade-up">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center mb-5">
                <h2 class="fw-bold mb-4">Meet Our Team</h2>
                <p class="lead text-muted">The passionate individuals behind StayRoomz who work tirelessly to make your room-finding experience exceptional.</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="card border-0 shadow-sm text-center h-100">
                    <div class="card-body">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-person-badge text-primary fs-2"></i>
                        </div>
                        <h5 class="fw-bold">Alex Johnson</h5>
                        <p class="text-primary mb-2">Founder & CEO</p>
                        <p class="text-muted small">Former software engineer with a passion for solving real-world problems through technology.</p>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="#" class="text-muted"><i class="bi bi-linkedin"></i></a>
                            <a href="#" class="text-muted"><i class="bi bi-twitter"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="card border-0 shadow-sm text-center h-100">
                    <div class="card-body">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-gear text-success fs-2"></i>
                        </div>
                        <h5 class="fw-bold">Sarah Chen</h5>
                        <p class="text-success mb-2">CTO</p>
                        <p class="text-muted small">Experienced tech leader focused on building scalable, user-friendly platforms that make a difference.</p>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="#" class="text-muted"><i class="bi bi-linkedin"></i></a>
                            <a href="#" class="text-muted"><i class="bi bi-github"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="card border-0 shadow-sm text-center h-100">
                    <div class="card-body">
                        <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-heart text-warning fs-2"></i>
                        </div>
                        <h5 class="fw-bold">Maria Rodriguez</h5>
                        <p class="text-warning mb-2">Head of Customer Success</p>
                        <p class="text-muted small">Dedicated to ensuring every user has an exceptional experience and finds their perfect space.</p>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="#" class="text-muted"><i class="bi bi-linkedin"></i></a>
                            <a href="#" class="text-muted"><i class="bi bi-envelope"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="py-5 bg-light" data-aos="fade-up">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="fw-bold mb-4">Ready to Find Your Perfect Space?</h2>
                <p class="lead text-muted mb-4">Join thousands of satisfied users who have found their ideal accommodation through StayRoomz.</p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="/" class="btn btn-primary btn-lg">
                        <i class="bi bi-search me-2"></i>Browse Rooms
                    </a>
                    <a href="/contact" class="btn btn-outline-primary btn-lg">
                        <i class="bi bi-envelope me-2"></i>Contact Us
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true
    });
</script>
@endpush 