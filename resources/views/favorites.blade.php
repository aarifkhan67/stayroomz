@extends('layouts.app')
@section('title', 'My Favorites')
@section('content')

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="display-6 fw-bold text-primary mb-2">
                        <i class="bi bi-heart-fill text-danger me-2"></i>My Favorites
                    </h1>
                    <p class="text-muted mb-0">Your saved rooms for easy access</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="/" class="btn btn-outline-primary">
                        <i class="bi bi-house me-2"></i>Browse More Rooms
                    </a>
                </div>
            </div>
        </div>
    </div>

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

    <div class="row" id="favoritesContainer">
        @if($favorites->count() > 0)
            @foreach($favorites as $room)
                <div class="col-md-6 col-lg-4 mb-4" data-room-id="{{ $room->id }}">
                    <div class="card h-100 shadow-sm position-relative">
                        <!-- Favorite Button -->
                        <button class="btn btn-sm position-absolute top-0 end-0 m-2 favorite-btn" 
                                data-room-id="{{ $room->id }}" 
                                style="z-index: 10; background: rgba(255,255,255,0.9);">
                            <i class="bi bi-heart-fill text-danger"></i>
                        </button>
                        
                        <!-- Room Image -->
                        <img src="{{ $room->image || '/images/default-room.jpg' }}" 
                             class="card-img-top" alt="{{ $room->title }}" 
                             style="height: 200px; object-fit: cover;" 
                             onerror="this.src='/images/default-room.jpg'">
                        
                        <div class="card-body">
                            <!-- Room Title and Status -->
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0">{{ $room->title }}</h5>
                                <span class="badge {{ $room->getAvailabilityBadgeClass() }}">
                                    {{ $room->getAvailabilityText() }}
                                </span>
                            </div>
                            
                            <!-- Location -->
                            <p class="text-muted mb-2">
                                <i class="bi bi-geo-alt me-1"></i>{{ $room->address }}
                            </p>
                            
                            <!-- Description -->
                            <p class="card-text text-muted small mb-3">
                                {{ Str::limit($room->description, 100) }}
                            </p>
                            
                            <!-- Price and Rating -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="h5 text-primary mb-0">₹{{ number_format($room->price) }}/month</span>
                                <div class="text-warning">
                                    @php $rating = $room->averageRating(); @endphp
                                    @if($rating)
                                        <i class="bi bi-star-fill"></i>
                                        <small>{{ number_format($rating, 1) }}</small>
                                    @else
                                        <small class="text-muted">No ratings</small>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Room Details -->
                            <div class="row text-center mb-3">
                                <div class="col-6">
                                    <small class="text-muted d-block">Type</small>
                                    <strong>{{ ucfirst($room->type) }}</strong>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Available From</small>
                                    <strong>{{ $room->available_from ? date('M Y', strtotime($room->available_from)) : 'Immediate' }}</strong>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-primary btn-sm flex-fill" 
                                        onclick="viewRoomDetails({{ $room->id }})">
                                    <i class="bi bi-eye me-1"></i>View Details
                                </button>
                                <button class="btn btn-outline-danger btn-sm" 
                                        onclick="removeFromFavorites({{ $room->id }})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <!-- Empty State -->
            <div class="col-12 text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-heart text-muted" style="font-size: 4rem;"></i>
                </div>
                <h3 class="text-muted mb-3">No favorites yet</h3>
                <p class="text-muted mb-4">Start browsing rooms and add them to your favorites for easy access later.</p>
                <a href="/" class="btn btn-primary btn-lg">
                    <i class="bi bi-search me-2"></i>Browse Rooms
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Room Details Modal -->
<div class="modal fade" id="roomDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Room Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="roomModalBody">
                <!-- Room details will be loaded here -->
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize favorite buttons
    initializeFavoriteButtons();
});

function initializeFavoriteButtons() {
    document.querySelectorAll('.favorite-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const roomId = this.dataset.roomId;
            toggleFavorite(roomId, this);
        });
    });
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
            if (data.action === 'removed') {
                // Remove the card from favorites page
                const card = button.closest('.col-md-6, .col-lg-4');
                if (card) {
                    card.style.animation = 'fadeOut 0.3s ease-out';
                    setTimeout(() => {
                        card.remove();
                        checkEmptyState();
                    }, 300);
                }
                
                // Update button icon
                button.innerHTML = '<i class="bi bi-heart text-muted"></i>';
            } else {
                // Update button icon
                button.innerHTML = '<i class="bi bi-heart-fill text-danger"></i>';
            }
            
            // Update favorites count in navbar if it exists
            updateFavoritesCount(data.favorites_count);
            
            showAlert(data.message, 'success');
        } else {
            showAlert(data.message, 'danger');
        }
    })
    .catch(error => {
        showAlert('An error occurred. Please try again.', 'danger');
    });
}

function removeFromFavorites(roomId) {
    if (confirm('Are you sure you want to remove this room from your favorites?')) {
        const card = document.querySelector(`[data-room-id="${roomId}"]`);
        if (card) {
            card.style.animation = 'fadeOut 0.3s ease-out';
            setTimeout(() => {
                card.remove();
                checkEmptyState();
            }, 300);
        }
        
        // Also update the favorite button
        const favoriteBtn = card?.querySelector('.favorite-btn');
        if (favoriteBtn) {
            favoriteBtn.innerHTML = '<i class="bi bi-heart text-muted"></i>';
        }
        
        showAlert('Room removed from favorites', 'success');
    }
}

function checkEmptyState() {
    const container = document.getElementById('favoritesContainer');
    const cards = container.querySelectorAll('.col-md-6, .col-lg-4');
    
    if (cards.length === 0) {
        container.innerHTML = `
            <div class="col-12 text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-heart text-muted" style="font-size: 4rem;"></i>
                </div>
                <h3 class="text-muted mb-3">No favorites yet</h3>
                <p class="text-muted mb-4">Start browsing rooms and add them to your favorites for easy access later.</p>
                <a href="/" class="btn btn-primary btn-lg">
                    <i class="bi bi-search me-2"></i>Browse Rooms
                </a>
            </div>
        `;
    }
}

function viewRoomDetails(roomId) {
    // This would load room details in the modal
    // For now, just show a simple message
    const modal = new bootstrap.Modal(document.getElementById('roomDetailsModal'));
    document.getElementById('roomModalBody').innerHTML = `
        <div class="text-center py-4">
            <i class="bi bi-info-circle text-primary" style="font-size: 3rem;"></i>
            <h4 class="mt-3">Room Details</h4>
            <p class="text-muted">Room details would be loaded here.</p>
        </div>
    `;
    modal.show();
}

function updateFavoritesCount(count) {
    // Update favorites count in navbar if it exists
    const favoritesBadge = document.querySelector('.favorites-count');
    if (favoritesBadge) {
        favoritesBadge.textContent = count;
    }
}

function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('.container');
    container.insertBefore(alertDiv, container.firstChild);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 3000);
}
</script>

<style>
@keyframes fadeOut {
    from { opacity: 1; transform: scale(1); }
    to { opacity: 0; transform: scale(0.9); }
}

.favorite-btn {
    transition: all 0.2s ease;
}

.favorite-btn:hover {
    transform: scale(1.1);
}
</style>
@endsection 