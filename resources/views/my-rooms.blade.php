@extends('layouts.app')
@section('title', 'My Rooms')
@section('content')



<div class="row my-rooms-page">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>My Room Listings</h2>
            <a href="/list-room" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Add New Room
            </a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(!session('user'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <strong>Please login first!</strong> You need to be logged in to view your rooms.
        <a href="/login" class="btn btn-primary btn-sm ms-2">Login Now</a>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row" id="myRoomsContainer">
    <!-- Rooms will be loaded here dynamically -->
    <div class="col-12 text-center py-5">
        <i class="bi bi-house text-muted fs-1"></i>
        <h4 class="text-muted mt-3">No rooms listed yet</h4>
        <p class="text-muted">Start by adding your first room listing</p>
        <a href="/list-room" class="btn btn-primary">List Your First Room</a>
    </div>
</div>

<!-- Room Management Modal -->
<div class="modal fade" id="roomModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="roomModalTitle">Edit Room</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="roomModalBody">
                <!-- Room edit form will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveRoomBtn">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this room listing?</p>
                <p class="text-danger"><small>This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete Room</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
let currentRooms = [];
let roomToDelete = null;

document.addEventListener('DOMContentLoaded', function() {
    loadMyRooms();
});

function loadMyRooms() {
    const userRooms = @json($userRooms ?? []);
    currentRooms = userRooms;
    displayMyRooms(currentRooms);
}

function displayMyRooms(rooms) {
    const container = document.getElementById('myRoomsContainer');
    if (rooms.length === 0) {
        container.innerHTML = `
            <div class="col-12 text-center py-5">
                <i class="bi bi-house text-muted fs-1"></i>
                <h4 class="text-muted mt-3">No rooms listed yet</h4>
                <p class="text-muted">Start by adding your first room listing</p>
                <a href="/list-room" class="btn btn-primary">List Your First Room</a>
            </div>
        `;
        return;
    }
    
    container.innerHTML = rooms.map(room => `
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm">
                <img src="${room.image || '/images/default-room.jpg'}" class="card-img-top" alt="${room.title}" style="height: 200px; object-fit: cover;" onerror="this.src='/images/default-room.jpg'">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title mb-0">${room.title}</h5>
                        <span class="badge ${getBadgeClass(room.availability)}">${getAvailabilityText(room.availability)}</span>
                    </div>
                    <p class="text-muted mb-2">${room.address}</p>
                    <p class="card-text">${room.description}</p>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="h5 text-primary mb-0">₹${room.price.toLocaleString()}/month</span>
                        <small class="text-muted">Added: ${new Date(room.created_at).toLocaleDateString()}</small>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary btn-sm flex-fill" onclick="editRoom(${room.id})">
                            <i class="bi bi-pencil me-1"></i>Edit
                        </button>
                        <button class="btn btn-outline-success btn-sm flex-fill" onclick="toggleAvailability(${room.id})">
                            <i class="bi bi-toggle-on me-1"></i>Toggle Status
                        </button>
                        <button class="btn btn-outline-danger btn-sm" onclick="deleteRoom(${room.id})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
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

function editRoom(roomId) {
    const room = currentRooms.find(r => r.id === roomId);
    if (!room) return;
    
    const modal = new bootstrap.Modal(document.getElementById('roomModal'));
    const modalTitle = document.getElementById('roomModalTitle');
    const modalBody = document.getElementById('roomModalBody');
    
    modalTitle.textContent = `Edit: ${room.title}`;
    
    modalBody.innerHTML = `
        <form id="editRoomForm">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Room Title</label>
                    <input type="text" class="form-control" id="editTitle" value="${room.title}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Price (₹)</label>
                    <input type="number" class="form-control" id="editPrice" value="${room.price}" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" id="editDescription" rows="3" required>${room.description}</textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Availability</label>
                    <select class="form-select" id="editAvailability">
                        <option value="available" ${room.availability === 'available' ? 'selected' : ''}>Available</option>
                        <option value="rented" ${room.availability === 'rented' ? 'selected' : ''}>Rented</option>
                        <option value="coming-soon" ${room.availability === 'coming-soon' ? 'selected' : ''}>Coming Soon</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Address</label>
                    <input type="text" class="form-control" id="editAddress" value="${room.address}" required>
                </div>
            </div>
        </form>
    `;
    
    document.getElementById('saveRoomBtn').onclick = () => saveRoomChanges(roomId);
    modal.show();
}

function saveRoomChanges(roomId) {
    const roomIndex = currentRooms.findIndex(r => r.id === roomId);
    if (roomIndex === -1) return;
    
    const updatedRoom = {
        ...currentRooms[roomIndex],
        title: document.getElementById('editTitle').value,
        price: parseInt(document.getElementById('editPrice').value),
        description: document.getElementById('editDescription').value,
        availability: document.getElementById('editAvailability').value,
        address: document.getElementById('editAddress').value
    };
    
    fetch(`/update-room/${roomId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify(updatedRoom)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            currentRooms[roomIndex] = updatedRoom;
            displayMyRooms(currentRooms);
            bootstrap.Modal.getInstance(document.getElementById('roomModal')).hide();
            showAlert('Room updated successfully!', 'success');
        } else {
            showAlert('Failed to update room. Please try again.', 'danger');
        }
    })
    .catch(error => {
        showAlert('Failed to update room. Please try again.', 'danger');
    });
}

function toggleAvailability(roomId) {
    const room = currentRooms.find(r => r.id === roomId);
    if (!room) return;
    
    const newStatus = room.availability === 'available' ? 'rented' : 'available';
    const statusText = newStatus === 'available' ? 'Available' : 'Rented';
    
    fetch(`/update-room/${roomId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({
            availability: newStatus
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            room.availability = newStatus;
            displayMyRooms(currentRooms);
            showAlert(`Room status changed to ${statusText}!`, 'success');
        } else {
            showAlert('Failed to update room status. Please try again.', 'danger');
        }
    })
    .catch(error => {
        showAlert('Failed to update room status. Please try again.', 'danger');
    });
}

function deleteRoom(roomId) {
    roomToDelete = roomId;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

document.getElementById('confirmDeleteBtn').onclick = function() {
    if (!roomToDelete) return;
    
    fetch(`/delete-room/${roomToDelete}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            currentRooms = currentRooms.filter(r => r.id !== roomToDelete);
            displayMyRooms(currentRooms);
            bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
            showAlert('Room deleted successfully!', 'success');
            roomToDelete = null;
        } else {
            showAlert('Failed to delete room. Please try again.', 'danger');
        }
    })
    .catch(error => {
        showAlert('Failed to delete room. Please try again.', 'danger');
    });
};

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
@endsection 