@extends('layouts.app')
@section('title', 'List Your Room')
@section('content')

<div class="row justify-content-center list-room-page">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">List Your Room</h3>
                <p class="mb-0 small">Share your space and earn money</p>
            </div>
            <div class="card-body">
                <form action="/add-room" method="POST" enctype="multipart/form-data" id="roomForm">
                    @csrf
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    
                    <!-- Basic Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary mb-3">Basic Information</h5>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="roomTitle" class="form-label">Room Title *</label>
                            <input type="text" class="form-control" id="roomTitle" name="title" required placeholder="e.g., Cozy Single Room in Andheri">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="roomType" class="form-label">Room Type *</label>
                            <select class="form-select" id="roomType" name="type" required>
                                <option value="">Select Room Type</option>
                                <option value="single">Single Room</option>
                                <option value="shared">Shared Room</option>
                                <option value="studio">Studio Apartment</option>
                                <option value="1bhk">1 BHK</option>
                                <option value="2bhk">2 BHK</option>
                                <option value="3bhk">3 BHK</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="location" class="form-label">Location *</label>
                            <input type="text" class="form-control" id="location" name="location" required placeholder="e.g., Mumbai, Delhi, Bangalore">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="address" class="form-label">Full Address *</label>
                            <input type="text" class="form-control" id="address" name="address" required placeholder="e.g., Andheri West, Mumbai">
                        </div>
                    </div>

                    <!-- Pricing -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary mb-3">Pricing & Availability</h5>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="monthlyRent" class="form-label">Monthly Rent (₹) *</label>
                            <input type="number" class="form-control" id="monthlyRent" name="price" required min="0" placeholder="e.g., 8000">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="securityDeposit" class="form-label">Security Deposit (₹)</label>
                            <input type="number" class="form-control" id="securityDeposit" name="deposit" min="0" placeholder="e.g., 10000">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="availability" class="form-label">Availability Status *</label>
                            <select class="form-select" id="availability" name="availability" required>
                                <option value="">Select Availability</option>
                                <option value="available">Available Now</option>
                                <option value="coming-soon">Coming Soon</option>
                                <option value="rented">Currently Rented</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="availableFrom" class="form-label">Available From</label>
                            <input type="date" class="form-control" id="availableFrom" name="available_from">
                        </div>
                    </div>

                    <!-- Room Details -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary mb-3">Room Details</h5>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea class="form-control" id="description" name="description" rows="4" required placeholder="Describe your room, amenities, nearby facilities, etc."></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="occupantType" class="form-label">Preferred Occupant Type</label>
                            <select class="form-select" id="occupantType" name="occupant_type">
                                <option value="">Any Type</option>
                                <option value="student">Student</option>
                                <option value="professional">Working Professional</option>
                                <option value="family">Family</option>
                                <option value="couple">Couple</option>
                                <option value="single">Single Person</option>
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Amenities Available</label>
                            <div class="row">
                                <div class="col-md-3 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="furnished" id="furnished">
                                        <label class="form-check-label" for="furnished">Furnished</label>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="ac" id="ac">
                                        <label class="form-check-label" for="ac">AC</label>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="wifi" id="wifi">
                                        <label class="form-check-label" for="wifi">WiFi</label>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="kitchen" id="kitchen">
                                        <label class="form-check-label" for="kitchen">Kitchen</label>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="parking" id="parking">
                                        <label class="form-check-label" for="parking">Parking</label>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="pet-friendly" id="petFriendly">
                                        <label class="form-check-label" for="petFriendly">Pet Friendly</label>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="eco-friendly" id="ecoFriendly">
                                        <label class="form-check-label" for="ecoFriendly">Eco Friendly</label>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="security" id="security">
                                        <label class="form-check-label" for="security">24/7 Security</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary mb-3">Contact Information</h5>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ownerName" class="form-label">Owner Name *</label>
                            <input type="text" class="form-control" id="ownerName" name="owner_name" required placeholder="Your full name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone Number *</label>
                            <input type="tel" class="form-control" id="phone" name="phone" required placeholder="+91 98765 43210">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" class="form-control" id="email" name="email" required placeholder="your@email.com">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="preferredContact" class="form-label">Preferred Contact Method</label>
                            <select class="form-select" id="preferredContact" name="preferred_contact">
                                <option value="phone">Phone</option>
                                <option value="email">Email</option>
                                <option value="both">Both</option>
                            </select>
                        </div>
                    </div>

                    <!-- Room Images -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary mb-3">Room Images</h5>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="mainImage" class="form-label">Main Room Image *</label>
                            <input type="file" class="form-control" id="mainImage" name="main_image" accept="image/*" required onchange="validateFileSize(this, 10)">
                            <div class="form-text">Upload a clear image of your room (Max 10MB)</div>
                            <div id="mainImageSize" class="form-text text-info"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="additionalImages" class="form-label">Additional Images</label>
                            <input type="file" class="form-control" id="additionalImages" name="additional_images[]" accept="image/*" multiple onchange="validateMultipleFiles(this, 10)">
                            <div class="form-text">Upload more images to showcase your room (Max 5 images, 10MB each)</div>
                            <div id="additionalImagesSize" class="form-text text-info"></div>
                        </div>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="#" class="text-primary">Terms and Conditions</a> and <a href="#" class="text-primary">Privacy Policy</a> *
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex gap-3">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-check-circle me-2"></i>List My Room
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-lg" onclick="previewRoom()">
                                    <i class="bi bi-eye me-2"></i>Preview
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-lg" onclick="clearForm()">
                                    <i class="bi bi-trash me-2"></i>Clear Form
                                </button>
                                <a href="/" class="btn btn-outline-danger btn-lg">
                                    <i class="bi bi-x-circle me-2"></i>Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Room Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="previewContent">
                <!-- Preview content will be populated here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitForm()">Submit Listing</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function validateFileSize(input, maxSizeMB) {
    const file = input.files[0];
    const sizeDiv = document.getElementById(input.id + 'Size');
    
    if (file) {
        const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
        const maxSize = maxSizeMB;
        
        if (fileSizeMB > maxSize) {
            sizeDiv.innerHTML = `<span class="text-danger">⚠️ File size (${fileSizeMB}MB) exceeds ${maxSize}MB limit. File may not upload properly.</span>`;
            sizeDiv.className = 'form-text text-danger';
        } else {
            sizeDiv.innerHTML = `<span class="text-success">✅ File size: ${fileSizeMB}MB (Good)</span>`;
            sizeDiv.className = 'form-text text-success';
        }
    } else {
        sizeDiv.innerHTML = '';
    }
}

function validateMultipleFiles(input, maxSizeMB) {
    const files = input.files;
    const sizeDiv = document.getElementById(input.id + 'Size');
    let totalSize = 0;
    let validFiles = 0;
    let invalidFiles = 0;
    
    if (files.length > 0) {
        for (let i = 0; i < files.length; i++) {
            const fileSizeMB = (files[i].size / (1024 * 1024)).toFixed(2);
            totalSize += parseFloat(fileSizeMB);
            
            if (fileSizeMB > maxSizeMB) {
                invalidFiles++;
            } else {
                validFiles++;
            }
        }
        
        const totalSizeMB = totalSize.toFixed(2);
        let message = `📁 ${files.length} files selected. Total size: ${totalSizeMB}MB`;
        
        if (invalidFiles > 0) {
            message += `<br><span class="text-danger">⚠️ ${invalidFiles} file(s) exceed ${maxSizeMB}MB limit</span>`;
            sizeDiv.className = 'form-text text-warning';
        } else {
            message += `<br><span class="text-success">✅ All files are within size limit</span>`;
            sizeDiv.className = 'form-text text-success';
        }
        
        sizeDiv.innerHTML = message;
    } else {
        sizeDiv.innerHTML = '';
    }
}

function previewRoom() {
    const formData = new FormData(document.getElementById('roomForm'));
    const previewContent = document.getElementById('previewContent');
    
    let previewHTML = `
        <div class="card">
            <div class="card-body">
                <h4>${formData.get('title') || 'Room Title'}</h4>
                <p class="text-muted">${formData.get('address') || 'Address'}</p>
                <p>${formData.get('description') || 'Description'}</p>
                <div class="row">
                    <div class="col-md-6">
                        <strong>Monthly Rent:</strong> ₹${formData.get('price') || '0'}
                    </div>
                    <div class="col-md-6">
                        <strong>Room Type:</strong> ${formData.get('type') || 'Not specified'}
                    </div>
                </div>
                <div class="mt-3">
                    <strong>Contact:</strong> ${formData.get('owner_name') || 'Owner Name'} - ${formData.get('phone') || 'Phone'}
                </div>
            </div>
        </div>
    `;
    
    previewContent.innerHTML = previewHTML;
    
    const modal = new bootstrap.Modal(document.getElementById('previewModal'));
    modal.show();
}

function submitForm() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const tokenInput = document.querySelector('input[name="_token"]');
    if (tokenInput) {
        tokenInput.value = csrfToken;
    }
    
    document.getElementById('roomForm').submit();
}

function clearForm() {
    if (confirm('Are you sure you want to clear the form? This action cannot be undone.')) {
        document.getElementById('roomForm').reset();
        localStorage.removeItem('roomFormData');
        alert('Form cleared successfully!');
    }
}

document.getElementById('roomForm').addEventListener('submit', function(e) {
    const requiredFields = ['title', 'type', 'location', 'address', 'price', 'availability', 'description', 'owner_name', 'phone', 'email'];
    
    for (let field of requiredFields) {
        const element = document.getElementById(field) || document.querySelector(`[name="${field}"]`);
        if (!element.value.trim()) {
            e.preventDefault();
            alert(`Please fill in the ${field.replace(/([A-Z])/g, ' $1').toLowerCase()} field.`);
            element.focus();
            return;
        }
    }
    
    const email = document.getElementById('email').value;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        e.preventDefault();
        alert('Please enter a valid email address.');
        document.getElementById('email').focus();
        return;
    }
    
    const phone = document.getElementById('phone').value;
    const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
    if (!phoneRegex.test(phone.replace(/\s/g, ''))) {
        e.preventDefault();
        alert('Please enter a valid phone number.');
        document.getElementById('phone').focus();
        return;
    }
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const tokenInput = document.querySelector('input[name="_token"]');
    if (tokenInput) {
        tokenInput.value = csrfToken;
    }
    
    const submitBtn = document.querySelector('button[type="submit"]');
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Submitting...';
    }
});

const form = document.getElementById('roomForm');
const formInputs = form.querySelectorAll('input, select, textarea');

formInputs.forEach(input => {
    input.addEventListener('change', function() {
        const formData = new FormData(form);
        const data = {};
        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }
        localStorage.setItem('roomFormData', JSON.stringify(data));
    });
});

window.addEventListener('load', function() {
    const savedData = localStorage.getItem('roomFormData');
    if (savedData) {
        const data = JSON.parse(savedData);
        const hasData = Object.values(data).some(value => value && value !== '' && value !== 'on');
        
        if (hasData) {
            const loadSaved = confirm('We found previously saved form data. Would you like to load it?');
            if (loadSaved) {
                for (let [key, value] of Object.entries(data)) {
                    const element = form.querySelector(`[name="${key}"]`);
                    if (element) {
                        if (element.type === 'checkbox') {
                            element.checked = value === 'on';
                        } else {
                            element.value = value;
                        }
                    }
                }
            } else {
                localStorage.removeItem('roomFormData');
            }
        }
    }
});
</script>
@endsection 