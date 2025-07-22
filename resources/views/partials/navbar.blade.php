<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold d-flex align-items-center" href="/">
      <div class="logo-container" style="background-color: #f8f9fa; padding: 8px; border-radius: 8px; margin-right: 20px;">
        <img src="/images/logo.png" alt="StayRoomz Logo" style="height: 55px; width: auto;">
      </div>
      <div>
        <span class="fw-bold">StayRoomz</span>
        <span class="d-block small text-muted" style="font-size: 0.85rem;">Find your space. Without the hassle.</span>
      </div>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="/">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/about">About Us</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/contact">Contact Us</a>
        </li>
      </ul>
      <div class="d-flex align-items-center">
        @if(session('user'))
          <!-- Logged In User -->
          <div class="d-flex align-items-center gap-3">
            <div class="dropdown">
              <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle me-1"></i>
                <span>{{ session('user')['name'] ?? 'User' }}</span>
                <small class="badge bg-{{ session('user')['role'] === 'owner' ? 'primary' : 'success' }} ms-1">
                  {{ session('user')['role_display'] ?? 'User' }}
                </small>
              </button>
              <ul class="dropdown-menu" aria-labelledby="userDropdown">
                <li><a class="dropdown-item" href="/profile"><i class="bi bi-person me-2"></i>Profile</a></li>
                <li><a class="dropdown-item" href="/favorites"><i class="bi bi-heart me-2"></i>My Favorites <span class="badge bg-danger favorites-count">{{ session('user') ? \App\Models\User::find(session('user')['id'])->getFavoritesCount() : 0 }}</span></a></li>
                <li><a class="dropdown-item" href="/payments"><i class="bi bi-credit-card me-2"></i>Payment History</a></li>
                @if(session('user') && \App\Models\User::find(session('user')['id'])->canListRooms())
                  <li><a class="dropdown-item" href="/dashboard"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                  <li><a class="dropdown-item" href="/my-rooms"><i class="bi bi-house me-2"></i>My Rooms</a></li>
                @endif
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="/logout"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
              </ul>
            </div>
          </div>
        @else
          <!-- Guest User - Show both options -->
          <div class="d-flex gap-2">
            <a class="btn btn-outline-primary" href="/login">
              <i class="bi bi-box-arrow-in-right me-1"></i>Login
            </a>
            <a class="btn btn-primary" href="/signup">
              <i class="bi bi-person-plus me-1"></i>Sign Up
            </a>
          </div>
        @endif
      </div>
    </div>
  </div>
</nav> 