@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

<div class="row dashboard-page">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Welcome back, {{ session('user')['name'] }}!</h2>
            <a href="/list-room" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Add New Room
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Stats Cards -->
    <div class="col-md-3 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0" id="totalRooms">0</h4>
                        <p class="mb-0">Total Rooms</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-house-door fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0" id="availableRooms">0</h4>
                        <p class="mb-0">Available</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-check-circle fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0" id="rentedRooms">0</h4>
                        <p class="mb-0">Rented</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-people fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0" id="totalViews">0</h4>
                        <p class="mb-0">Total Views</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-eye fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Activity -->
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Recent Activity</h5>
            </div>
            <div class="card-body">
                <div id="recentActivity">
                    <div class="text-center py-4">
                        <i class="bi bi-activity text-muted fs-1"></i>
                        <p class="text-muted mt-2">No recent activity</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="/list-room" class="btn btn-outline-primary">
                        <i class="bi bi-plus-circle me-2"></i>Add New Room
                    </a>
                    <a href="/my-rooms" class="btn btn-outline-secondary">
                        <i class="bi bi-house me-2"></i>Manage My Rooms
                    </a>
                    <a href="/profile" class="btn btn-outline-info">
                        <i class="bi bi-person me-2"></i>Edit Profile
                    </a>
                    <button class="btn btn-outline-success" onclick="exportData()">
                        <i class="bi bi-download me-2"></i>Export Data
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Rooms -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">My Recent Rooms</h5>
                <a href="/my-rooms" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                <div id="recentRooms">
                    <div class="text-center py-4">
                        <i class="bi bi-house text-muted fs-1"></i>
                        <p class="text-muted mt-2">No rooms listed yet</p>
                        <a href="/list-room" class="btn btn-primary">List Your First Room</a>
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
    loadDashboardData();
});

function loadDashboardData() {
    const userRooms = @json($userRooms ?? []);
    
    document.getElementById('totalRooms').textContent = userRooms.length || 0;
    document.getElementById('availableRooms').textContent = userRooms.filter(room => room.availability === 'available').length || 0;
    document.getElementById('rentedRooms').textContent = userRooms.filter(room => room.availability === 'rented').length || 0;
    document.getElementById('totalViews').textContent = userRooms.reduce((sum, room) => sum + (room.views || 0), 0) || 0;
    
    loadRecentRooms(userRooms);
    loadRecentActivity(userRooms);
}

function loadRecentRooms(rooms) {
    const container = document.getElementById('recentRooms');
    
    if (rooms.length === 0) {
        return;
    }
    
    const recentRooms = rooms.slice(0, 3);
    
    container.innerHTML = `
        <div class="row">
            ${recentRooms.map(room => `
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <img src="${room.image || 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=400&h=300&fit=crop'}" class="card-img-top" alt="${room.title}" style="height: 150px; object-fit: cover;">
                        <div class="card-body">
                            <h6 class="card-title">${room.title}</h6>
                            <p class="card-text small text-muted">${room.address}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h6 text-primary mb-0">₹${room.price.toLocaleString()}/month</span>
                                <span class="badge ${getBadgeClass(room.availability)}">${getAvailabilityText(room.availability)}</span>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <a href="/edit-room/${room.id}" class="btn btn-sm btn-outline-primary w-100">Edit</a>
                        </div>
                    </div>
                </div>
            `).join('')}
        </div>
    `;
}

function loadRecentActivity(rooms) {
    const container = document.getElementById('recentActivity');
    
    if (rooms.length === 0) {
        return;
    }
    
    const activities = rooms.map(room => ({
        type: 'room_added',
        title: room.title,
        date: new Date(room.added_at).toLocaleDateString(),
        time: new Date(room.added_at).toLocaleTimeString()
    })).slice(0, 5);
    
    container.innerHTML = `
        <div class="list-group list-group-flush">
            ${activities.map(activity => `
                <div class="list-group-item d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-plus-circle text-success"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0">Added new room: ${activity.title}</h6>
                        <small class="text-muted">${activity.date} at ${activity.time}</small>
                    </div>
                </div>
            `).join('')}
        </div>
    `;
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

function exportData() {
    alert('Export functionality has been removed. Data is now stored securely in the database.');
}
</script>
@endsection 