@extends('layouts.app')
@section('title', 'My Profile')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Profile Header -->
            <div class="card shadow-sm mb-4" data-aos="fade-up">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                <div class="bg-{{ $user->role === 'owner' ? 'primary' : 'success' }} bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-4" style="width: 80px; height: 80px;">
                                    <i class="bi bi-{{ $user->role === 'owner' ? 'house-fill' : 'person-fill' }} text-{{ $user->role === 'owner' ? 'primary' : 'success' }} fs-1"></i>
                                </div>
                                <div>
                                    <h2 class="fw-bold mb-1">{{ $user->name }}</h2>
                                    <p class="text-muted mb-2">
                                        <i class="bi bi-envelope me-2"></i>{{ $user->email }}
                                    </p>
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="badge bg-{{ $user->role === 'owner' ? 'primary' : 'success' }} fs-6">
                                            <i class="bi bi-{{ $user->role === 'owner' ? 'house-fill' : 'person-fill' }} me-1"></i>
                                            {{ $user->getRoleDisplayName() }}
                                        </span>
                                        <span class="text-muted">
                                            <i class="bi bi-calendar me-1"></i>
                                            Member since {{ $user->created_at->format('M Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="d-flex flex-column gap-2">
                                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                    <i class="bi bi-pencil me-2"></i>Edit Profile
                                </button>
                                <button class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                    <i class="bi bi-key me-2"></i>Change Password
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" data-aos="fade-up">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert" data-aos="fade-up">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <!-- Role-specific Dashboard -->
                    @if($user->isRoomOwner())
                        <!-- Owner Dashboard -->
                        <div class="card shadow-sm mb-4" data-aos="fade-up" data-aos-delay="100">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-speedometer2 me-2"></i>Owner Dashboard
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="bg-primary bg-opacity-10 rounded p-3 text-center">
                                            <i class="bi bi-house-fill text-primary fs-2 mb-2"></i>
                                            <h3 class="fw-bold mb-1">{{ $user->getRoomCount() }}</h3>
                                            <p class="text-muted mb-0">Total Rooms</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="bg-success bg-opacity-10 rounded p-3 text-center">
                                            <i class="bi bi-check-circle-fill text-success fs-2 mb-2"></i>
                                            <h3 class="fw-bold mb-1">{{ $user->getAvailableRoomCount() }}</h3>
                                            <p class="text-muted mb-0">Available Rooms</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="bg-warning bg-opacity-10 rounded p-3 text-center">
                                            <i class="bi bi-star-fill text-warning fs-2 mb-2"></i>
                                            <h3 class="fw-bold mb-1">{{ $user->roomReviews()->count() }}</h3>
                                            <p class="text-muted mb-0">Total Reviews</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="bg-info bg-opacity-10 rounded p-3 text-center">
                                            <i class="bi bi-eye-fill text-info fs-2 mb-2"></i>
                                            <h3 class="fw-bold mb-1">{{ $user->rooms()->sum('views') }}</h3>
                                            <p class="text-muted mb-0">Total Views</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-4">
                                    <h6 class="fw-bold mb-3">Quick Actions</h6>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <a href="/list-room" class="btn btn-primary">
                                            <i class="bi bi-plus-circle me-2"></i>List New Room
                                        </a>
                                        <a href="/my-rooms" class="btn btn-outline-primary">
                                            <i class="bi bi-house me-2"></i>Manage Rooms
                                        </a>
                                        <a href="/dashboard" class="btn btn-outline-success">
                                            <i class="bi bi-graph-up me-2"></i>Analytics
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Rooms -->
                        <div class="card shadow-sm mb-4" data-aos="fade-up" data-aos-delay="200">
                            <div class="card-header bg-success text-white">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-house me-2"></i>My Recent Rooms
                                </h5>
                            </div>
                            <div class="card-body">
                                @if($user->rooms()->count() > 0)
                                    <div class="row g-3">
                                        @foreach($user->rooms()->latest()->take(3)->get() as $room)
                                            <div class="col-md-6">
                                                <div class="card border-0 shadow-sm h-100">
                                                    @if($room->image)
                                                        <img src="/uploads/rooms/{{ $room->image }}" class="card-img-top" alt="{{ $room->title }}" style="height: 150px; object-fit: cover;">
                                                    @else
                                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                                            <i class="bi bi-house text-muted fs-1"></i>
                                                        </div>
                                                    @endif
                                                    <div class="card-body">
                                                        <h6 class="card-title fw-bold">{{ $room->title }}</h6>
                                                        <p class="text-muted small mb-2">{{ $room->location }}</p>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="badge bg-{{ $room->availability === 'available' ? 'success' : 'secondary' }}">
                                                                {{ ucfirst($room->availability) }}
                                                            </span>
                                                            <span class="fw-bold text-primary">₹{{ number_format($room->price) }}/month</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="text-center mt-3">
                                        <a href="/my-rooms" class="btn btn-outline-primary">View All Rooms</a>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="bi bi-house text-muted fs-1 mb-3"></i>
                                        <h6 class="text-muted">No rooms listed yet</h6>
                                        <p class="text-muted small">Start by listing your first room</p>
                                        <a href="/list-room" class="btn btn-primary">
                                            <i class="bi bi-plus-circle me-2"></i>List Your First Room
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <!-- Renter Dashboard -->
                        <div class="card shadow-sm mb-4" data-aos="fade-up" data-aos-delay="100">
                            <div class="card-header bg-success text-white">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-person-check me-2"></i>Renter Dashboard
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="bg-danger bg-opacity-10 rounded p-3 text-center">
                                            <i class="bi bi-heart-fill text-danger fs-2 mb-2"></i>
                                            <h3 class="fw-bold mb-1">{{ $user->getFavoritesCount() }}</h3>
                                            <p class="text-muted mb-0">Favorited Rooms</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="bg-info bg-opacity-10 rounded p-3 text-center">
                                            <i class="bi bi-eye-fill text-info fs-2 mb-2"></i>
                                            <h3 class="fw-bold mb-1">{{ $user->favorites()->count() }}</h3>
                                            <p class="text-muted mb-0">Viewed Rooms</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-4">
                                    <h6 class="fw-bold mb-3">Quick Actions</h6>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <a href="/" class="btn btn-success">
                                            <i class="bi bi-search me-2"></i>Browse Rooms
                                        </a>
                                        <a href="/favorites" class="btn btn-outline-danger">
                                            <i class="bi bi-heart me-2"></i>My Favorites
                                        </a>
                                        <a href="/contact" class="btn btn-outline-info">
                                            <i class="bi bi-envelope me-2"></i>Contact Support
                                        </a>
                                        <a href="/payments" class="btn btn-outline-primary">
                                            <i class="bi bi-credit-card me-2"></i>Payment History
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Favorite Rooms -->
                        <div class="card shadow-sm mb-4" data-aos="fade-up" data-aos-delay="200">
                            <div class="card-header bg-danger text-white">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-heart me-2"></i>My Favorite Rooms
                                </h5>
                            </div>
                            <div class="card-body">
                                @if($user->favorites()->count() > 0)
                                    <div class="row g-3">
                                        @foreach($user->favorites()->with('room')->latest()->take(3)->get() as $favorite)
                                            @if($favorite->room)
                                                <div class="col-md-6">
                                                    <div class="card border-0 shadow-sm h-100">
                                                        @if($favorite->room->image)
                                                            <img src="/uploads/rooms/{{ $favorite->room->image }}" class="card-img-top" alt="{{ $favorite->room->title }}" style="height: 150px; object-fit: cover;">
                                                        @else
                                                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                                                <i class="bi bi-house text-muted fs-1"></i>
                                                            </div>
                                                        @endif
                                                        <div class="card-body">
                                                            <h6 class="card-title fw-bold">{{ $favorite->room->title }}</h6>
                                                            <p class="text-muted small mb-2">{{ $favorite->room->location }}</p>
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <span class="badge bg-{{ $favorite->room->availability === 'available' ? 'success' : 'secondary' }}">
                                                                    {{ ucfirst($favorite->room->availability) }}
                                                                </span>
                                                                <span class="fw-bold text-primary">₹{{ number_format($favorite->room->price) }}/month</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <div class="text-center mt-3">
                                        <a href="/favorites" class="btn btn-outline-danger">View All Favorites</a>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="bi bi-heart text-muted fs-1 mb-3"></i>
                                        <h6 class="text-muted">No favorite rooms yet</h6>
                                        <p class="text-muted small">Start exploring and save your favorite rooms</p>
                                        <a href="/" class="btn btn-success">
                                            <i class="bi bi-search me-2"></i>Browse Rooms
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Account Activity -->
                    <div class="card shadow-sm" data-aos="fade-up" data-aos-delay="300">
                        <div class="card-header bg-info text-white">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-activity me-2"></i>Recent Activity
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-primary"></div>
                                    <div class="timeline-content">
                                        <h6 class="fw-bold">Profile Updated</h6>
                                        <p class="text-muted small mb-0">Your profile information was updated</p>
                                        <small class="text-muted">{{ now()->subDays(1)->format('M j, Y g:i A') }}</small>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <h6 class="fw-bold">Account Created</h6>
                                        <p class="text-muted small mb-0">Welcome to StayRoomz!</p>
                                        <small class="text-muted">{{ $user->created_at->format('M j, Y g:i A') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Account Status -->
                    <div class="card shadow-sm mb-4" data-aos="fade-up" data-aos-delay="100">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-shield-check me-2"></i>Account Status
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span>Account Status</span>
                                <span class="badge bg-success">Active</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span>Email Verification</span>
                                <span class="badge bg-success">Verified</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span>Phone Number</span>
                                <span class="badge bg-{{ $user->phone ? 'success' : 'warning' }}">
                                    {{ $user->phone ? 'Added' : 'Not Added' }}
                                </span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <span>Last Login</span>
                                <small class="text-muted">{{ now()->subHours(2)->format('M j, g:i A') }}</small>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="card shadow-sm mb-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-graph-up me-2"></i>Quick Stats
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($user->isRoomOwner())
                                <div class="row text-center">
                                    <div class="col-6 mb-3">
                                        <div class="bg-primary bg-opacity-10 rounded p-2">
                                            <i class="bi bi-house-fill text-primary fs-4"></i>
                                            <h5 class="mb-0 mt-1">{{ $user->getRoomCount() }}</h5>
                                            <small class="text-muted">Rooms</small>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="bg-success bg-opacity-10 rounded p-2">
                                            <i class="bi bi-check-circle-fill text-success fs-4"></i>
                                            <h5 class="mb-0 mt-1">{{ $user->getAvailableRoomCount() }}</h5>
                                            <small class="text-muted">Available</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="bg-warning bg-opacity-10 rounded p-2">
                                            <i class="bi bi-star-fill text-warning fs-4"></i>
                                            <h5 class="mb-0 mt-1">{{ $user->roomReviews()->count() }}</h5>
                                            <small class="text-muted">Reviews</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="bg-info bg-opacity-10 rounded p-2">
                                            <i class="bi bi-eye-fill text-info fs-4"></i>
                                            <h5 class="mb-0 mt-1">{{ $user->rooms()->sum('views') }}</h5>
                                            <small class="text-muted">Views</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="bg-success bg-opacity-10 rounded p-2">
                                            <i class="bi bi-credit-card text-success fs-4"></i>
                                            <h5 class="mb-0 mt-1">{{ $user->paymentsAsLandlord()->count() }}</h5>
                                            <small class="text-muted">Received</small>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="row text-center">
                                    <div class="col-6 mb-3">
                                        <div class="bg-danger bg-opacity-10 rounded p-2">
                                            <i class="bi bi-heart-fill text-danger fs-4"></i>
                                            <h5 class="mb-0 mt-1">{{ $user->getFavoritesCount() }}</h5>
                                            <small class="text-muted">Favorites</small>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="bg-info bg-opacity-10 rounded p-2">
                                            <i class="bi bi-eye-fill text-info fs-4"></i>
                                            <h5 class="mb-0 mt-1">{{ $user->favorites()->count() }}</h5>
                                            <small class="text-muted">Viewed</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="bg-primary bg-opacity-10 rounded p-2">
                                            <i class="bi bi-credit-card text-primary fs-4"></i>
                                            <h5 class="mb-0 mt-1">{{ $user->paymentsAsRenter()->count() }}</h5>
                                            <small class="text-muted">Payments</small>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Support & Help -->
                    <div class="card shadow-sm" data-aos="fade-up" data-aos-delay="300">
                        <div class="card-header bg-dark text-white">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-question-circle me-2"></i>Need Help?
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="/contact" class="btn btn-outline-primary">
                                    <i class="bi bi-envelope me-2"></i>Contact Support
                                </a>
                                <a href="/about" class="btn btn-outline-info">
                                    <i class="bi bi-info-circle me-2"></i>About StayRoomz
                                </a>
                                <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#helpModal">
                                    <i class="bi bi-question-circle me-2"></i>FAQ
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-pencil me-2"></i>Edit Profile
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/profile/update" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="modal_name" class="form-label">Full Name *</label>
                        <input type="text" class="form-control" id="modal_name" name="name" value="{{ $user->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="modal_phone" class="form-label">Phone Number *</label>
                        <input type="tel" class="form-control" id="modal_phone" name="phone" value="{{ $user->phone }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-key me-2"></i>Change Password
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/profile/update" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" minlength="6" required>
                        <div class="form-text">Minimum 6 characters</div>
                    </div>
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Change Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Help Modal -->
<div class="modal fade" id="helpModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-question-circle me-2"></i>Frequently Asked Questions
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="accordion" id="helpAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                How do I update my profile information?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#helpAccordion">
                            <div class="accordion-body">
                                Click the "Edit Profile" button in your profile page to update your name and phone number. Email addresses cannot be changed for security reasons.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                How do I change my password?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                            <div class="accordion-body">
                                Click the "Change Password" button and enter your current password along with your new password. Make sure to confirm the new password.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                How do I list a room as an owner?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                            <div class="accordion-body">
                                As a room owner, you can click "List New Room" from your dashboard or profile page to add a new room listing with details and photos.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true
    });

    // Form validation
    document.addEventListener('DOMContentLoaded', function() {
        // Profile form validation
        const profileForm = document.querySelector('form[action="/profile/update"]');
        if (profileForm) {
            profileForm.addEventListener('submit', function(e) {
                const name = document.querySelector('input[name="name"]').value.trim();
                const phone = document.querySelector('input[name="phone"]').value.trim();
                
                if (!name) {
                    e.preventDefault();
                    alert('Please enter your full name.');
                    return;
                }
                
                if (!phone) {
                    e.preventDefault();
                    alert('Please enter your phone number.');
                    return;
                }
            });
        }

        // Password form validation
        const passwordForm = document.querySelector('#changePasswordModal form');
        if (passwordForm) {
            passwordForm.addEventListener('submit', function(e) {
                const currentPassword = document.getElementById('current_password').value;
                const newPassword = document.getElementById('new_password').value;
                const confirmPassword = document.getElementById('new_password_confirmation').value;
                
                if (!currentPassword) {
                    e.preventDefault();
                    alert('Please enter your current password.');
                    return;
                }
                
                if (newPassword.length < 6) {
                    e.preventDefault();
                    alert('New password must be at least 6 characters long.');
                    return;
                }
                
                if (newPassword !== confirmPassword) {
                    e.preventDefault();
                    alert('New password and confirmation do not match.');
                    return;
                }
            });
        }
    });
</script>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -35px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 0 0 3px #dee2e6;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: -29px;
    top: 17px;
    width: 2px;
    height: calc(100% + 3px);
    background-color: #dee2e6;
}

.timeline-content h6 {
    margin-bottom: 5px;
}

.timeline-content p {
    margin-bottom: 5px;
}
</style>
@endpush 