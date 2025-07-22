@extends('layouts.app')
@section('title', 'Home')
@section('content')
<style>
.rating-stars {
    display: inline-flex;
    flex-direction: row-reverse;
    gap: 2px;
}

.rating-stars input:checked ~ label,
.rating-stars label:hover,
.rating-stars label:hover ~ label {
    color: #ffc107;
}

.star-label {
    cursor: pointer;
    font-size: 1.5rem;
    color: #dee2e6;
    transition: color 0.2s;
}

.star-label:hover {
    color: #ffc107;
}

.review-item {
    border-bottom: 1px solid #dee2e6;
    padding: 10px 0;
}

.review-item:last-child {
    border-bottom: none;
}

.review-rating {
    color: #ffc107;
}

.review-author {
    font-weight: 600;
    color: #6c757d;
}

.review-date {
    font-size: 0.875rem;
    color: #6c757d;
}
</style>

<div class="container-fluid">


<!-- Hero Section -->
<div class="text-center py-5 bg-light rounded-3 mb-5 hero-section" data-aos="fade-up" data-aos-duration="1000">
    <h1 class="display-4 fw-bold mb-3" data-aos="fade-up" data-aos-delay="200">Welcome to StayRoomz</h1>
    <p class="lead mb-4" data-aos="fade-up" data-aos-delay="400">Find your perfect room, hassle-free. StayRoomz makes room rental easy, fast, and reliable.</p>
</div>

<!-- Search and Filter Section -->
<div class="card mb-5" data-aos="fade-up" data-aos-delay="300">
    <div class="card-body">
        <h3 class="card-title mb-4" data-aos="fade-up" data-aos-delay="400">Find Your Perfect Room</h3>
        <p class="text-muted mb-3">Search for rooms by location, type, budget, and more. You can search by city name, area, or full address.</p>
        <form id="searchForm" class="row g-3">
            <div class="col-md-4">
                <label for="location" class="form-label">Location</label>
                <input type="text" class="form-control" id="location" name="location" placeholder="Enter city, area, or address...">
                <div class="form-text">
                    <small class="text-muted">Popular: Mumbai, Delhi, Bangalore, Pune, Hyderabad</small>
                </div>
            </div>
            <div class="col-md-4">
                <label for="roomType" class="form-label">Room Type</label>
                <select class="form-select" id="roomType" name="roomType">
                    <option value="">All Room Types</option>
                    <option value="single">🏠 Single Room</option>
                    <option value="shared">👥 Shared Room</option>
                    <option value="studio">🏢 Studio Apartment</option>
                    <option value="1bhk">🏠 1 BHK</option>
                    <option value="2bhk">🏠 2 BHK</option>
                    <option value="3bhk">🏠 3 BHK</option>
                </select>
                <div class="form-text">
                    <small class="text-muted">Choose your preferred room type</small>
                </div>
            </div>
            <div class="col-md-4">
                <label for="budget" class="form-label">Budget Range (₹/month)</label>
                <select class="form-select" id="budget" name="budget">
                    <option value="">Any Budget</option>
                    <option value="0-5000">₹0 - ₹5,000</option>
                    <option value="5000-10000">₹5,000 - ₹10,000</option>
                    <option value="10000-15000">₹10,000 - ₹15,000</option>
                    <option value="15000-20000">₹15,000 - ₹20,000</option>
                    <option value="20000-30000">₹20,000 - ₹30,000</option>
                    <option value="30000+">₹30,000+</option>
                    <option value="custom">Custom Range</option>
                </select>
                <div class="form-text">
                    <small class="text-muted">Select your monthly budget range</small>
                </div>
                <!-- Custom Price Range Slider -->
                <div class="mt-2" id="priceRangeSlider" style="display: none;">
                    <label class="form-label">Custom Range: <span id="priceRangeDisplay">₹0 - ₹50,000</span></label>
                    <div class="d-flex gap-2 align-items-center">
                        <input type="range" class="form-range flex-grow-1" id="minPrice" min="0" max="50000" value="0" step="1000">
                        <input type="range" class="form-range flex-grow-1" id="maxPrice" min="0" max="50000" value="50000" step="1000">
                    </div>
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">₹<span id="minPriceDisplay">0</span></small>
                        <small class="text-muted">₹<span id="maxPriceDisplay">50,000</span></small>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary mt-1" onclick="togglePriceRange()">
                    <i class="bi bi-sliders"></i> Custom Range
                </button>
            </div>
            <div class="col-md-3">
                <label for="occupantType" class="form-label">Occupant Type</label>
                <select class="form-select" id="occupantType" name="occupantType">
                    <option value="">Select Type</option>
                    <option value="student">Student</option>
                    <option value="professional">Working Professional</option>
                    <option value="family">Family</option>
                    <option value="couple">Couple</option>
                    <option value="single">Single Person</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="amenities" class="form-label">Amenities</label>
                <select class="form-select" id="amenities" name="amenities">
                    <option value="">Select Amenities</option>
                    <option value="pet-friendly">Pet Friendly</option>
                    <option value="eco-friendly">Eco Friendly</option>
                    <option value="furnished">Furnished</option>
                    <option value="ac">AC</option>
                    <option value="parking">Parking</option>
                    <option value="wifi">WiFi</option>
                    <option value="kitchen">Kitchen</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="availability" class="form-label">Availability</label>
                <select class="form-select" id="availability" name="availability">
                    <option value="">Select Availability</option>
                    <option value="available">Available</option>
                    <option value="rented">Rented</option>
                    <option value="coming-soon">Coming Soon</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <button type="button" class="btn btn-primary w-100" onclick="searchRooms()">Search Rooms</button>
            </div>
        </form>
        <div class="mt-3">
            <small class="text-muted">Quick Search:</small>
            <div class="d-flex flex-wrap gap-2 mt-1">
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="quickSearch('Mumbai')">Mumbai</button>
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="quickSearch('Delhi')">Delhi</button>
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="quickSearch('Bangalore')">Bangalore</button>
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="quickSearch('Pune')">Pune</button>
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="quickSearch('Hyderabad')">Hyderabad</button>
            </div>
        </div>
        <div class="mt-2">
            <small class="text-muted">Popular Room Types:</small>
            <div class="d-flex flex-wrap gap-2 mt-1">
                <button type="button" class="btn btn-outline-info btn-sm" onclick="quickFilter('roomType', 'single')">🏠 Single Room</button>
                <button type="button" class="btn btn-outline-info btn-sm" onclick="quickFilter('roomType', '1bhk')">🏠 1 BHK</button>
                <button type="button" class="btn btn-outline-info btn-sm" onclick="quickFilter('roomType', '2bhk')">🏠 2 BHK</button>
                <button type="button" class="btn btn-outline-info btn-sm" onclick="quickFilter('roomType', 'studio')">🏢 Studio</button>
            </div>
        </div>
        <div class="mt-2">
            <small class="text-muted">Budget Ranges:</small>
            <div class="d-flex flex-wrap gap-2 mt-1">
                <button type="button" class="btn btn-outline-success btn-sm" onclick="quickFilter('budget', '0-5000')">₹0-5K</button>
                <button type="button" class="btn btn-outline-success btn-sm" onclick="quickFilter('budget', '5000-10000')">₹5K-10K</button>
                <button type="button" class="btn btn-outline-success btn-sm" onclick="quickFilter('budget', '10000-15000')">₹10K-15K</button>
                <button type="button" class="btn btn-outline-success btn-sm" onclick="quickFilter('budget', '15000-20000')">₹15K-20K</button>
            </div>
        </div>
    </div>
</div>

<!-- Room Listing Section -->
<div id="roomListing" class="d-none" data-aos="fade-up" data-aos-delay="500">
    <div id="searchResultsInfo"></div>
    <div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-up" data-aos-delay="600">
        <h3>Available Rooms</h3>
        <div class="d-flex gap-2">
            <select class="form-select form-select-sm" id="sortBy" onchange="sortRooms()">
                <option value="price-low">Price: Low to High</option>
                <option value="price-high">Price: High to Low</option>
                <option value="newest">Newest First</option>
                <option value="rating">Highest Rated</option>
            </select>
        </div>
    </div>
    
    <div class="row" id="roomsContainer">
        <!-- Room cards will be dynamically populated here -->
    </div>
</div>

<!-- Recently Added Available Rooms Section -->
<div class="mt-5" id="recentRoomsSection" data-aos="fade-up" data-aos-delay="700">
    <div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-up" data-aos-delay="800">
        <h3>Recently Added Available Rooms</h3>
        <a href="/list-room" class="btn btn-outline-primary btn-sm">List Your Room</a>
    </div>
    <div class="row" id="recentRoomsContainer">
        <!-- Recently added available rooms will be displayed here -->
    </div>
</div>

<!-- Room Details Modal -->
<div class="modal fade" id="roomDetailsModal" tabindex="-1" aria-labelledby="roomDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="roomDetailsModalLabel">Room Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <img id="modalRoomImage" src="" alt="Room Image" class="img-fluid rounded" style="width: 100%; height: 300px; object-fit: cover;">
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 id="modalRoomTitle" class="text-primary mb-0"></h4>
                            <span id="modalRoomBadge" class="badge fs-6"></span>
                        </div>
                        <div class="mb-3">
                            <h6 class="text-muted mb-2"><i class="bi bi-geo-alt-fill me-2"></i>Location</h6>
                            <p id="modalRoomAddress" class="mb-0"></p>
                        </div>
                        <div class="mb-3">
                            <h6 class="text-muted mb-2"><i class="bi bi-currency-rupee me-2"></i>Price Details</h6>
                            <div class="row">
                                <div class="col-6">
                                    <strong>Monthly Rent:</strong>
                                    <p id="modalRoomPrice" class="text-primary fs-5 mb-0"></p>
                                </div>
                                <div class="col-6">
                                    <strong>Security Deposit:</strong>
                                    <p id="modalRoomDeposit" class="text-muted mb-0"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <h6 class="text-muted mb-2"><i class="bi bi-info-circle me-2"></i>Description</h6>
                            <p id="modalRoomDescription" class="mb-0"></p>
                        </div>
                        <div class="mb-3">
                            <h6 class="text-muted mb-2"><i class="bi bi-house me-2"></i>Room Details</h6>
                            <div class="row">
                                <div class="col-6">
                                    <strong>Room Type:</strong>
                                    <p id="modalRoomType" class="mb-0"></p>
                                </div>
                                <div class="col-6">
                                    <strong>Occupant Type:</strong>
                                    <p id="modalRoomOccupant" class="mb-0"></p>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <h6 class="text-muted mb-2"><i class="bi bi-calendar me-2"></i>Availability</h6>
                            <div class="row">
                                <div class="col-6">
                                    <strong>Available From:</strong>
                                    <p id="modalRoomAvailableFrom" class="mb-0"></p>
                                </div>
                                <div class="col-6">
                                    <strong>Status:</strong>
                                    <p id="modalRoomStatus" class="mb-0"></p>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <h6 class="text-muted mb-2"><i class="bi bi-star me-2"></i>Amenities</h6>
                            <div id="modalRoomAmenities" class="d-flex flex-wrap gap-2"></div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-12">
                        <h6 class="text-primary mb-3"><i class="bi bi-star-fill me-2"></i>Reviews & Ratings</h6>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <span class="h5 text-warning mb-0">
                                    <i class="bi bi-star-fill"></i>
                                    <span id="modalRoomAverageRating">N/A</span>
                                </span>
                                <small class="text-muted ms-2">(<span id="modalRoomReviewCount">0</span> reviews)</small>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="modalAddReviewBtn">
                                <i class="bi bi-plus-circle me-1"></i>Add Review
                            </button>
                        </div>
                        <div id="modalRoomReviews" class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                            <div class="text-center text-muted">
                                <i class="bi bi-chat-dots fs-1"></i>
                                <p class="mb-0">No reviews yet. Be the first to review this room!</p>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-12">
                        <h6 class="text-primary mb-3"><i class="bi bi-person-circle me-2"></i>Owner Information</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Owner Name:</strong>
                                <p id="modalOwnerName" class="mb-0"></p>
                            </div>
                            <div class="col-md-4">
                                <strong>Phone:</strong>
                                <p id="modalOwnerPhone" class="mb-0">
                                    <a href="tel:" id="modalOwnerPhoneLink" class="text-decoration-none">
                                        <i class="bi bi-telephone-fill me-1"></i>
                                        <span id="modalOwnerPhoneText"></span>
                                    </a>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <strong>Email:</strong>
                                <p id="modalOwnerEmail" class="mb-0">
                                    <a href="mailto:" id="modalOwnerEmailLink" class="text-decoration-none">
                                        <i class="bi bi-envelope-fill me-1"></i>
                                        <span id="modalOwnerEmailText"></span>
                                    </a>
                                </p>
                            </div>
                        </div>
                        <div class="mt-2">
                            <strong>Preferred Contact:</strong>
                            <p id="modalPreferredContact" class="mb-0"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="modalCallButton">
                    <i class="bi bi-telephone-fill me-1"></i>Call Owner
                </button>
                <button type="button" class="btn btn-success" id="modalEmailButton">
                    <i class="bi bi-envelope-fill me-1"></i>Send Email
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Review Submission Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewModalLabel">Write a Review</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="reviewForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Rating *</label>
                        <div class="rating-stars">
                            <input type="radio" name="rating" value="5" id="star5" class="d-none">
                            <label for="star5" class="star-label"><i class="bi bi-star"></i></label>
                            <input type="radio" name="rating" value="4" id="star4" class="d-none">
                            <label for="star4" class="star-label"><i class="bi bi-star"></i></label>
                            <input type="radio" name="rating" value="3" id="star3" class="d-none">
                            <label for="star3" class="star-label"><i class="bi bi-star"></i></label>
                            <input type="radio" name="rating" value="2" id="star2" class="d-none">
                            <label for="star2" class="star-label"><i class="bi bi-star"></i></label>
                            <input type="radio" name="rating" value="1" id="star1" class="d-none">
                            <label for="star1" class="star-label"><i class="bi bi-star"></i></label>
                        </div>
                        <small class="text-muted">Click on a star to rate (1-5)</small>
                    </div>
                    <div class="mb-3">
                        <label for="guestName" class="form-label">Your Name (Optional)</label>
                        <input type="text" class="form-control" id="guestName" name="guest_name" placeholder="Enter your name (optional)">
                        <small class="text-muted">Leave blank to remain anonymous</small>
                    </div>
                    <div class="mb-3">
                        <label for="reviewComment" class="form-label">Comment (Optional)</label>
                        <textarea class="form-control" id="reviewComment" name="comment" rows="3" placeholder="Share your experience with this room..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Review</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Contact Owner Modal -->
<div class="modal fade" id="contactOwnerModal" tabindex="-1" aria-labelledby="contactOwnerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contactOwnerModalLabel">Contact Room Owner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary mb-3">Room Details</h6>
                        <div class="mb-3">
                            <strong>Room Title:</strong>
                            <p id="contactModalRoomTitle" class="mb-2"></p>
                        </div>
                        <div class="mb-3">
                            <strong>Location:</strong>
                            <p id="contactModalRoomLocation" class="mb-2"></p>
                        </div>
                        <div class="mb-3">
                            <strong>Price:</strong>
                            <p id="contactModalRoomPrice" class="mb-2"></p>
                        </div>
                        <div class="mb-3">
                            <strong>Description:</strong>
                            <p id="contactModalRoomDescription" class="mb-2"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary mb-3">Owner Details</h6>
                        <div class="mb-3">
                            <strong>Name:</strong>
                            <p id="contactModalOwnerName" class="mb-2"></p>
                        </div>
                        <div class="mb-3">
                            <strong>Phone:</strong>
                            <p id="contactModalOwnerPhone" class="mb-2">
                                <a href="tel:" id="contactModalOwnerPhoneLink" class="text-decoration-none">
                                    <i class="bi bi-telephone-fill me-1"></i>
                                    <span id="contactModalOwnerPhoneText"></span>
                                </a>
                            </p>
                        </div>
                        <div class="mb-3">
                            <strong>Email:</strong>
                            <p id="contactModalOwnerEmail" class="mb-2">
                                <a href="mailto:" id="contactModalOwnerEmailLink" class="text-decoration-none">
                                    <i class="bi bi-envelope-fill me-1"></i>
                                    <span id="contactModalOwnerEmailText"></span>
                                </a>
                            </p>
                        </div>
                        <div class="mb-3">
                            <strong>Preferred Contact:</strong>
                            <p id="contactModalPreferredContact" class="mb-2"></p>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Tip:</strong> You can click on the phone number or email to directly call or send an email to the owner.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="contactModalCallButton">
                    <i class="bi bi-telephone-fill me-1"></i>Call Owner
                </button>
                <button type="button" class="btn btn-success" id="contactModalEmailButton">
                    <i class="bi bi-envelope-fill me-1"></i>Send Email
                </button>
            </div>
        </div>
    </div>
</div>



@endsection

@section('scripts')
<script>
let rooms = [];
let currentDisplayedRooms = [];

function loadRoomData() {
    try {
        const roomData = @json($availableRooms ?? []);
        rooms = Array.isArray(roomData) ? roomData : [];
        currentDisplayedRooms = [...rooms];
    } catch (error) {
        rooms = [];
        currentDisplayedRooms = [];
    }
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function getBudgetRange(price) {
    if (price <= 5000) return '0-5000';
    if (price <= 10000) return '5000-10000';
    if (price <= 15000) return '10000-15000';
    if (price <= 20000) return '15000-20000';
    if (price <= 30000) return '20000-30000';
    return '30000+';
}

function togglePriceRange() {
    const budgetSelect = document.getElementById('budget');
    const priceRangeSlider = document.getElementById('priceRangeSlider');
    
    if (priceRangeSlider.style.display === 'none') {
        // Show custom range slider
        priceRangeSlider.style.display = 'block';
        budgetSelect.value = 'custom';
        setupPriceRangeSliders();
    } else {
        // Hide custom range slider
        priceRangeSlider.style.display = 'none';
        budgetSelect.value = '';
    }
}

function setupPriceRangeSliders() {
    const minPriceInput = document.getElementById('minPrice');
    const maxPriceInput = document.getElementById('maxPrice');
    const minPriceDisplay = document.getElementById('minPriceDisplay');
    const maxPriceDisplay = document.getElementById('maxPriceDisplay');
    const priceRangeDisplay = document.getElementById('priceRangeDisplay');
    
    // Update displays when sliders change
    minPriceInput.addEventListener('input', function() {
        const minPrice = parseInt(this.value);
        const maxPrice = parseInt(maxPriceInput.value);
        
        if (minPrice > maxPrice) {
            maxPriceInput.value = minPrice;
        }
        
        minPriceDisplay.textContent = minPrice.toLocaleString();
        priceRangeDisplay.textContent = `₹${minPrice.toLocaleString()} - ₹${parseInt(maxPriceInput.value).toLocaleString()}`;
        searchRooms();
    });
    
    maxPriceInput.addEventListener('input', function() {
        const maxPrice = parseInt(this.value);
        const minPrice = parseInt(minPriceInput.value);
        
        if (maxPrice < minPrice) {
            minPriceInput.value = maxPrice;
        }
        
        maxPriceDisplay.textContent = maxPrice.toLocaleString();
        priceRangeDisplay.textContent = `₹${parseInt(minPriceInput.value).toLocaleString()} - ₹${maxPrice.toLocaleString()}`;
        searchRooms();
    });
}

function searchRooms() {
    const location = document.getElementById('location').value.trim();
    const roomType = document.getElementById('roomType').value;
    const budget = document.getElementById('budget').value;
    const occupantType = document.getElementById('occupantType').value;
    const amenities = document.getElementById('amenities').value;
    const availability = document.getElementById('availability').value;
    
    // Get custom price range if using slider
    let minPrice = 0;
    let maxPrice = Infinity;
    
    if (budget === 'custom') {
        minPrice = parseInt(document.getElementById('minPrice').value);
        maxPrice = parseInt(document.getElementById('maxPrice').value);
    }
    
    let filteredRooms = rooms.filter(room => {
        let match = true;
        
        // Location search - check both address and location fields
        if (location) {
            const searchTerm = location.toLowerCase();
            const addressMatch = room.address && room.address.toLowerCase().includes(searchTerm);
            const locationMatch = room.location && room.location.toLowerCase().includes(searchTerm);
            if (!addressMatch && !locationMatch) match = false;
        }
        
        // Room type filter
        if (roomType && room.type !== roomType) match = false;
        
        // Budget filter
        if (budget && budget !== 'custom') {
            const roomBudgetRange = getBudgetRange(room.price);
            if (roomBudgetRange !== budget) match = false;
        } else if (budget === 'custom') {
            if (room.price < minPrice || room.price > maxPrice) match = false;
        }
        
        if (occupantType && room.occupant_type !== occupantType) match = false;
        if (amenities && room.amenities && !room.amenities.includes(amenities)) match = false;
        if (availability && room.availability !== availability) match = false;
        
        return match;
    });
    
    currentDisplayedRooms = filteredRooms;
    displayRooms(filteredRooms);
    
    // Show search results count
    const resultsCount = filteredRooms.length;
    const resultsInfo = document.getElementById('searchResultsInfo');
    
    if (resultsInfo) {
        let searchCriteria = [];
        if (location) searchCriteria.push(`Location: "${location}"`);
        if (roomType) searchCriteria.push(`Type: ${getRoomTypeText(roomType)}`);
        if (budget === 'custom') {
            searchCriteria.push(`Budget: ₹${minPrice.toLocaleString()} - ₹${maxPrice.toLocaleString()}`);
        } else if (budget) {
            searchCriteria.push(`Budget: ${budget}`);
        }
        if (occupantType) searchCriteria.push(`Occupant: ${getOccupantTypeText(occupantType)}`);
        if (amenities) searchCriteria.push(`Amenity: ${amenities}`);
        if (availability) searchCriteria.push(`Status: ${getAvailabilityText(availability)}`);
        
        const criteriaText = searchCriteria.length > 0 ? ` (${searchCriteria.join(', ')})` : '';
        
        resultsInfo.innerHTML = `
            <div class="alert alert-info">
                <i class="bi bi-search me-2"></i>
                Found ${resultsCount} room${resultsCount !== 1 ? 's' : ''} matching your criteria${criteriaText}
                <button type="button" class="btn btn-sm btn-outline-secondary ms-2" onclick="clearSearch()">Clear Search</button>
            </div>
        `;
    }
}

function quickSearch(location) {
    document.getElementById('location').value = location;
    searchRooms();
}

function clearSearch() {
    // Reset all form fields
    document.getElementById('searchForm').reset();
    
    // Hide price range slider
    const priceRangeSlider = document.getElementById('priceRangeSlider');
    if (priceRangeSlider) {
        priceRangeSlider.style.display = 'none';
    }
    
    // Reset price range sliders
    const minPriceInput = document.getElementById('minPrice');
    const maxPriceInput = document.getElementById('maxPrice');
    if (minPriceInput && maxPriceInput) {
        minPriceInput.value = 0;
        maxPriceInput.value = 50000;
    }
    
    // Show all rooms
    currentDisplayedRooms = [...rooms];
    displayRooms(rooms);
    
    // Hide results info
    const resultsInfo = document.getElementById('searchResultsInfo');
    if (resultsInfo) {
        resultsInfo.innerHTML = '';
    }
}

function displayRooms(roomsToShow) {
    const container = document.getElementById('roomsContainer');
    const listingSection = document.getElementById('roomListing');
    
    if (!roomsToShow || roomsToShow.length === 0) {
        container.innerHTML = `
            <div class="col-12 text-center py-5">
                <h4 class="text-muted">No available rooms found matching your criteria</h4>
                <p class="text-muted">Try adjusting your filters to see more options.</p>
            </div>
        `;
    } else {
        container.innerHTML = roomsToShow.map((room, index) => `
            <div class="col-md-6 col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="${index * 100}" data-room-id="${room.id}">
                <div class="card h-100 shadow-sm position-relative">
                    <!-- Favorite Button -->
                    <button class="btn btn-sm position-absolute top-0 end-0 m-2 favorite-btn" 
                            data-room-id="${room.id}" 
                            style="z-index: 10; background: rgba(255,255,255,0.9);">
                        <i class="bi bi-heart text-muted"></i>
                    </button>
                    
                    <img src="${escapeHtml(room.image || '/images/default-room.jpg')}" class="card-img-top" alt="${escapeHtml(room.title || 'Room')}" style="height: 200px; object-fit: cover;" onerror="this.src='/images/default-room.jpg'">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0">${escapeHtml(room.title || 'Untitled')}</h5>
                            <span class="badge ${getBadgeClass(room.availability)}">${getAvailabilityText(room.availability)}</span>
                        </div>
                        <p class="text-muted mb-2">${escapeHtml(room.address || 'Location not specified')}</p>
                        <p class="card-text">${escapeHtml(room.description || 'No description available')}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 text-primary mb-0">₹${(room.price || 0).toLocaleString()}/month</span>
                            <div class="text-warning">
                                <i class="bi bi-star-fill"></i>
                                <small>${room.average_rating ? room.average_rating.toFixed(1) : 'N/A'}</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        ${getButtonHtml(room.availability, room)}
                    </div>
                </div>
            </div>
        `).join('');
    }
    
    listingSection.classList.remove('d-none');
}

function getBadgeClass(availability) {
    switch(availability) {
        case 'available': return 'bg-success';
        case 'rented': return 'bg-danger';
        case 'coming-soon': return 'bg-warning';
        default: return 'bg-secondary';
    }
}

function getAvailabilityText(availability) {
    switch(availability) {
        case 'available': return 'Available';
        case 'rented': return 'Rented';
        case 'coming-soon': return 'Coming Soon';
        default: return 'Unknown';
    }
}

function getRoomTypeText(type) {
    switch(type) {
        case 'single': return 'Single Room';
        case 'shared': return 'Shared Room';
        case 'studio': return 'Studio Apartment';
        case '1bhk': return '1 BHK';
        case '2bhk': return '2 BHK';
        case '3bhk': return '3 BHK';
        default: return type;
    }
}

function getOccupantTypeText(occupantType) {
    switch(occupantType) {
        case 'student': return 'Student';
        case 'professional': return 'Working Professional';
        case 'family': return 'Family';
        case 'couple': return 'Couple';
        case 'single': return 'Single Person';
        default: return occupantType;
    }
}

function getButtonHtml(availability, room = null) {
    switch(availability) {
        case 'available':
            return '<div class="d-flex gap-1"><button class="btn btn-outline-primary btn-sm flex-grow-1" onclick="viewRoomDetails(' + JSON.stringify(room).replace(/"/g, '&quot;') + ')">View Details</button><button class="btn btn-success btn-sm" onclick="makePayment(' + room.id + ')" title="Make Payment"><i class="bi bi-credit-card"></i></button></div>';
        case 'rented':
            return '<button class="btn btn-outline-secondary btn-sm w-100" disabled>Currently Rented</button>';
        case 'coming-soon':
            return '<button class="btn btn-outline-warning btn-sm w-100">Notify When Available</button>';
        default:
            return '<div class="d-flex gap-1"><button class="btn btn-outline-primary btn-sm flex-grow-1" onclick="viewRoomDetails(' + JSON.stringify(room).replace(/"/g, '&quot;') + ')">View Details</button><button class="btn btn-success btn-sm" onclick="makePayment(' + room.id + ')" title="Make Payment"><i class="bi bi-credit-card"></i></button></div>';
    }
}

function sortRooms() {
    const sortBy = document.getElementById('sortBy').value;
    const currentRooms = getCurrentDisplayedRooms();
    
    switch(sortBy) {
        case 'price-low':
            currentRooms.sort((a, b) => a.price - b.price);
            break;
        case 'price-high':
            currentRooms.sort((a, b) => b.price - a.price);
            break;
        case 'newest':
            currentRooms.sort((a, b) => b.id - a.id);
            break;
        case 'rating':
            currentRooms.sort((a, b) => b.average_rating - a.average_rating);
            break;
    }
    
    displayRooms(currentRooms);
}

function getCurrentDisplayedRooms() {
    return currentDisplayedRooms;
}

document.addEventListener('DOMContentLoaded', function() {
    loadRoomData();
    loadRecentRooms();
    
    // Add real-time search for location
    const locationInput = document.getElementById('location');
    let searchTimeout;
    
    locationInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            if (this.value.trim()) {
                searchRooms();
            } else {
                // If location is cleared, show all rooms
                currentDisplayedRooms = [...rooms];
                displayRooms(rooms);
                const resultsInfo = document.getElementById('searchResultsInfo');
                if (resultsInfo) {
                    resultsInfo.innerHTML = '';
                }
            }
        }, 500); // Wait 500ms after user stops typing
    });
    
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('success')) {
        showSuccessMessage('Room added successfully!');
    }
    
    if (rooms.length > 0) {
        displayRooms(rooms);
    }
    
    // Initialize favorite buttons
    initializeFavoriteButtons();
});

// Favorites functionality
function initializeFavoriteButtons() {
    document.addEventListener('click', function(e) {
        if (e.target.closest('.favorite-btn')) {
            e.preventDefault();
            const button = e.target.closest('.favorite-btn');
            const roomId = button.dataset.roomId;
            toggleFavorite(roomId, button);
        }
    });
    
    // Check initial favorite status for all rooms
    checkAllFavoriteStatus();
}

function toggleFavorite(roomId, button) {
    fetch(`/favorites/${roomId}/toggle`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update button icon
            const icon = button.querySelector('i');
            if (data.is_favorited) {
                icon.className = 'bi bi-heart-fill text-danger';
                showSuccessMessage('Room added to favorites!');
            } else {
                icon.className = 'bi bi-heart text-muted';
                showSuccessMessage('Room removed from favorites!');
            }
            
            // Update favorites count in navbar if it exists
            updateFavoritesCount(data.favorites_count);
        } else {
            if (data.message.includes('login')) {
                window.location.href = '/login';
            } else {
                showSuccessMessage(data.message);
            }
        }
    })
    .catch(error => {
        console.error('Error toggling favorite:', error);
        showSuccessMessage('An error occurred. Please try again.');
    });
}

function checkAllFavoriteStatus() {
    const favoriteButtons = document.querySelectorAll('.favorite-btn');
    favoriteButtons.forEach(button => {
        const roomId = button.dataset.roomId;
        checkFavoriteStatus(roomId, button);
    });
}

function checkFavoriteStatus(roomId, button) {
    fetch(`/favorites/${roomId}/check`)
        .then(response => response.json())
        .then(data => {
            const icon = button.querySelector('i');
            if (data.is_favorited) {
                icon.className = 'bi bi-heart-fill text-danger';
            } else {
                icon.className = 'bi bi-heart text-muted';
            }
        })
        .catch(error => {
            console.error('Error checking favorite status:', error);
        });
}

function updateFavoritesCount(count) {
    const favoritesBadge = document.querySelector('.favorites-count');
    if (favoritesBadge) {
        favoritesBadge.textContent = count;
    }
}

function loadRecentRooms() {
    const recentRooms = rooms.slice(0, 6);
    const container = document.getElementById('recentRoomsContainer');
    
    if (!recentRooms || recentRooms.length === 0) {
        container.innerHTML = `
            <div class="col-12 text-center py-4">
                <p class="text-muted">No rooms have been added yet.</p>
                <a href="/list-room" class="btn btn-primary">Be the first to list your room!</a>
            </div>
        `;
    } else {
        container.innerHTML = recentRooms.map((room, index) => `
            <div class="col-md-6 col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="${index * 150}">
                <div class="card h-100 shadow-sm border-primary">
                    <div class="card-header bg-primary text-white">
                        <small>Newly Added</small>
                    </div>
                    <img src="${escapeHtml(room.image || '/images/default-room.jpg')}" class="card-img-top" alt="${escapeHtml(room.title || 'Room')}" style="height: 200px; object-fit: cover;" onerror="this.src='/images/default-room.jpg'">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0">${escapeHtml(room.title || 'Untitled')}</h5>
                            <span class="badge ${getBadgeClass(room.availability)}">${getAvailabilityText(room.availability)}</span>
                        </div>
                        <p class="text-muted mb-2">${escapeHtml(room.address || 'Location not specified')}</p>
                        <p class="card-text">${escapeHtml(room.description || 'No description available')}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 text-primary mb-0">₹${(room.price || 0).toLocaleString()}/month</span>
                            <div class="text-muted">
                                <small>Contact: ${escapeHtml(room.owner_name || 'Not specified')}</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <button class="btn btn-outline-primary btn-sm w-100" onclick="contactOwner(${JSON.stringify(room).replace(/"/g, '&quot;')})">Contact Owner</button>
                    </div>
                </div>
            </div>
        `).join('');
    }
}

function viewRoomDetails(room) {
    document.getElementById('modalRoomImage').src = escapeHtml(room.image || 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=400&h=300&fit=crop');
    document.getElementById('modalRoomTitle').textContent = room.title || 'N/A';
    document.getElementById('modalRoomBadge').innerHTML = `<span class="badge ${getBadgeClass(room.availability)}">${getAvailabilityText(room.availability)}</span>`;
    document.getElementById('modalRoomAddress').textContent = room.address || 'N/A';
    document.getElementById('modalRoomPrice').textContent = `₹${room.price ? room.price.toLocaleString() : 'N/A'}/month`;
    document.getElementById('modalRoomDeposit').textContent = room.deposit ? `₹${room.deposit.toLocaleString()}` : 'Not specified';
    document.getElementById('modalRoomDescription').textContent = room.description || 'N/A';
    document.getElementById('modalRoomType').textContent = getRoomTypeText(room.type) || 'N/A';
    document.getElementById('modalRoomOccupant').textContent = getOccupantTypeText(room.occupant_type) || 'N/A';
    document.getElementById('modalRoomAvailableFrom').textContent = room.available_from || 'N/A';
    document.getElementById('modalRoomStatus').textContent = getAvailabilityText(room.availability) || 'N/A';
    
    const amenitiesContainer = document.getElementById('modalRoomAmenities');
    if (room.amenities && room.amenities.length > 0) {
        amenitiesContainer.innerHTML = room.amenities.map(amenity => 
            `<span class="badge bg-light text-dark">${amenity}</span>`
        ).join('');
    } else {
        amenitiesContainer.innerHTML = '<span class="text-muted">No amenities specified</span>';
    }
    
    document.getElementById('modalOwnerName').textContent = room.owner_name || 'N/A';
    document.getElementById('modalOwnerPhoneText').textContent = room.phone || 'N/A';
    document.getElementById('modalOwnerEmailText').textContent = room.email || 'N/A';
    document.getElementById('modalPreferredContact').textContent = room.preferred_contact || 'Phone/Email';
    
    if (room.phone) {
        document.getElementById('modalOwnerPhoneLink').href = `tel:${room.phone}`;
        document.getElementById('modalCallButton').onclick = () => window.location.href = `tel:${room.phone}`;
    } else {
        document.getElementById('modalCallButton').disabled = true;
    }
    
    if (room.email) {
        document.getElementById('modalOwnerEmailLink').href = `mailto:${room.email}`;
        document.getElementById('modalEmailButton').onclick = () => window.location.href = `mailto:${room.email}`;
    } else {
        document.getElementById('modalEmailButton').disabled = true;
    }
    
    // Load reviews for this room
    loadRoomReviews(room.id);
    
    // Set up review button
    document.getElementById('modalAddReviewBtn').onclick = () => openReviewModal(room.id);
    
    const modal = new bootstrap.Modal(document.getElementById('roomDetailsModal'));
    modal.show();
}

function loadRoomReviews(roomId) {
    fetch(`/rooms/${roomId}/reviews`)
        .then(response => response.json())
        .then(reviews => {
            displayRoomReviews(reviews, roomId);
        })
        .catch(error => {
            console.error('Error loading reviews:', error);
            displayRoomReviews([], roomId);
        });
}

function displayRoomReviews(reviews, roomId) {
    const reviewsContainer = document.getElementById('modalRoomReviews');
    const averageRating = reviews.length > 0 ? 
        (reviews.reduce((sum, review) => sum + review.rating, 0) / reviews.length).toFixed(1) : 
        'N/A';
    
    document.getElementById('modalRoomAverageRating').textContent = averageRating;
    document.getElementById('modalRoomReviewCount').textContent = reviews.length;
    
    if (reviews.length === 0) {
        reviewsContainer.innerHTML = `
            <div class="text-center text-muted">
                <i class="bi bi-chat-dots fs-1"></i>
                <p class="mb-0">No reviews yet. Be the first to review this room!</p>
            </div>
        `;
    } else {
        reviewsContainer.innerHTML = reviews.map(review => `
            <div class="review-item">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <span class="review-author">${escapeHtml(review.user ? review.user.name : (review.guest_name || 'Anonymous'))}</span>
                        <div class="review-rating">
                            ${'★'.repeat(review.rating)}${'☆'.repeat(5-review.rating)}
                        </div>
                    </div>
                    <small class="review-date">${new Date(review.created_at).toLocaleDateString()}</small>
                </div>
                ${review.comment ? `<p class="mb-0">${escapeHtml(review.comment)}</p>` : ''}
            </div>
        `).join('');
    }
}

function openReviewModal(roomId) {
    // Reset form
    document.getElementById('reviewForm').reset();
    document.querySelectorAll('.star-label i').forEach(star => {
        star.className = 'bi bi-star';
    });
    
    // Set up form submission
    document.getElementById('reviewForm').onsubmit = (e) => {
        e.preventDefault();
        submitReview(roomId);
    };
    
    // Set up star rating interaction
    document.querySelectorAll('.rating-stars input').forEach(input => {
        input.addEventListener('change', function() {
            const rating = parseInt(this.value);
            document.querySelectorAll('.star-label i').forEach((star, index) => {
                star.className = index < rating ? 'bi bi-star-fill' : 'bi bi-star';
            });
        });
    });
    
    const modal = new bootstrap.Modal(document.getElementById('reviewModal'));
    modal.show();
}

function submitReview(roomId) {
    const formData = new FormData(document.getElementById('reviewForm'));
    const rating = formData.get('rating');
    const comment = formData.get('comment');
    const guestName = formData.get('guest_name');
    
    if (!rating) {
        alert('Please select a rating.');
        return;
    }
    
    fetch(`/rooms/${roomId}/review`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({
            rating: parseInt(rating),
            comment: comment,
            guest_name: guestName || null // Send guest_name if provided, otherwise null
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close review modal
            bootstrap.Modal.getInstance(document.getElementById('reviewModal')).hide();
            
            // Reload reviews
            loadRoomReviews(roomId);
            
            // Show success message
            showSuccessMessage('Review submitted successfully!');
        } else {
            alert(data.message || 'Failed to submit review. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error submitting review:', error);
        alert('Failed to submit review. Please try again.');
    });
}

function contactOwner(room) {
    document.getElementById('contactModalRoomTitle').textContent = room.title || 'N/A';
    document.getElementById('contactModalRoomLocation').textContent = room.address || 'N/A';
    document.getElementById('contactModalRoomPrice').textContent = `₹${room.price ? room.price.toLocaleString() : 'N/A'}/month`;
    document.getElementById('contactModalRoomDescription').textContent = room.description || 'N/A';
    
    document.getElementById('contactModalOwnerName').textContent = room.owner_name || 'N/A';
    document.getElementById('contactModalOwnerPhoneText').textContent = room.phone || 'N/A';
    document.getElementById('contactModalOwnerEmailText').textContent = room.email || 'N/A';
    document.getElementById('contactModalPreferredContact').textContent = room.preferred_contact || 'Phone/Email';
    
    if (room.phone) {
        document.getElementById('contactModalOwnerPhoneLink').href = `tel:${room.phone}`;
        document.getElementById('contactModalCallButton').onclick = () => window.location.href = `tel:${room.phone}`;
    } else {
        document.getElementById('contactModalCallButton').disabled = true;
    }
    
    if (room.email) {
        document.getElementById('contactModalOwnerEmailLink').href = `mailto:${room.email}`;
        document.getElementById('contactModalEmailButton').onclick = () => window.location.href = `mailto:${room.email}`;
    } else {
        document.getElementById('contactModalEmailButton').disabled = true;
    }
    
    const modal = new bootstrap.Modal(document.getElementById('contactOwnerModal'));
    modal.show();
}

function showSuccessMessage(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-success alert-dismissible fade show bounce';
    alertDiv.innerHTML = `
        <i class="bi bi-check-circle-fill me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('.container');
    container.insertBefore(alertDiv, container.firstChild);
    
    setTimeout(() => {
        alertDiv.style.animation = 'slideOutRight 0.5s ease-out';
        setTimeout(() => {
            alertDiv.remove();
        }, 500);
    }, 5000);
}

function showLoading(element) {
    element.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary loading-spinner" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Loading rooms...</p>
        </div>
    `;
}

function addShimmerEffect(element) {
    element.classList.add('shimmer');
    setTimeout(() => {
        element.classList.remove('shimmer');
    }, 1500);
}

function quickFilter(field, value) {
    document.getElementById(field).value = value;
    searchRooms();
}

function makePayment(roomId) {
    // Check if user is logged in
    if (!document.querySelector('meta[name="csrf-token"]')) {
        window.location.href = '/login';
        return;
    }
    
    // Redirect to payment form
    window.location.href = `/payment/${roomId}/create`;
}
</script>
@endsection 


