<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>StayRoomz - @yield('title', 'Find your space. Without the hassle.')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        body { 
            min-height: 100vh; 
            display: flex; 
            flex-direction: column; 
            overflow-x: hidden;
        }
        main { flex: 1; }
        
        /* Smooth page transitions */
        .page-transition {
            animation: fadeInUp 0.4s ease-out;
        }
        
        /* Disable page transitions on auth pages */
        .auth-page .page-transition {
            animation: none;
        }
        
        /* Navbar animations */
        .navbar {
            animation: slideDown 0.5s ease-out;
        }
        
        .navbar-brand {
            transition: transform 0.3s ease;
        }
        
        .navbar-brand:hover {
            transform: scale(1.05);
        }
        
        .nav-link {
            position: relative;
            transition: color 0.3s ease;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 50%;
            background-color: #007bff;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        .nav-link:hover::after {
            width: 100%;
        }
        
        /* Button animations */
        .btn {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        }
        
        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn:hover::before {
            left: 100%;
        }
        
        /* Card animations */
        .card {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .card-img-top {
            transition: transform 0.3s ease;
        }
        
        .card:hover .card-img-top {
            transform: scale(1.05);
        }
        
        /* Modal animations */
        .modal.fade .modal-dialog {
            transition: transform 0.3s ease-out;
            transform: scale(0.8);
        }
        
        .modal.show .modal-dialog {
            transform: scale(1);
        }
        
        /* Hero section animations */
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            animation: float 6s ease-in-out infinite;
        }
        
        /* Loading animations */
        .loading-spinner {
            animation: spin 1s linear infinite;
        }
        
        /* Form animations */
        .form-control, .form-select {
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            transform: scale(1.02);
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        
        /* Badge animations */
        .badge {
            transition: all 0.3s ease;
        }
        
        .badge:hover {
            transform: scale(1.1);
        }
        
        /* Alert animations */
        .alert {
            animation: slideInRight 0.5s ease-out;
        }
        
        /* Dropdown animations */
        .dropdown-menu {
            animation: fadeInDown 0.3s ease-out;
        }
        
        /* Keyframe animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes slideOutRight {
            from {
                opacity: 1;
                transform: translateX(0);
            }
            to {
                opacity: 0;
                transform: translateX(100%);
            }
        }
        
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Pulse animation for important elements */
        .pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        /* Bounce animation for notifications */
        .bounce {
            animation: bounce 0.6s ease-out;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }
        
        /* Shimmer effect for loading states */
        .shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }
        
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        
        /* Auth pages specific styles */
        .auth-page {
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        
        .auth-page .card {
            animation: none !important;
            transform: none !important;
            max-width: 100%;
        }
        
        /* Dashboard pages specific styles */
        .dashboard-page {
            animation: none !important;
        }
        
        .dashboard-page .card {
            animation: none !important;
            transform: none !important;
        }
        
        /* My-rooms pages specific styles */
        .my-rooms-page {
            animation: none !important;
        }
        
        .my-rooms-page .card {
            animation: none !important;
            transform: none !important;
        }
        
        /* List-room pages specific styles */
        .list-room-page {
            animation: none !important;
        }
        
        .list-room-page .card {
            animation: none !important;
            transform: none !important;
        }
        

        
        /* Prevent scroll jumping on all pages */
        body {
            overflow-x: hidden;
        }
        
        /* Ensure content is always visible - prevent AOS hiding issues */
        .aos-item {
            opacity: 1 !important;
            visibility: visible !important;
        }
        
        .aos-item[data-aos] {
            opacity: 1 !important;
            visibility: visible !important;
        }
        
        /* Force visibility on problematic pages */
        .auth-page .aos-item,
        .dashboard-page .aos-item,
        .my-rooms-page .aos-item,
        .list-room-page .aos-item {
            opacity: 1 !important;
            visibility: visible !important;
            transform: none !important;
        }
        
        /* Ensure list-room page content is always visible */
        .list-room-page {
            opacity: 1 !important;
            visibility: visible !important;
        }
        
        .list-room-page .card,
        .list-room-page .form-control,
        .list-room-page .form-select,
        .list-room-page .btn {
            opacity: 1 !important;
            visibility: visible !important;
            transform: none !important;
        }
        
        /* Ensure smooth page loads */
        .page-transition {
            min-height: 100vh;
        }
        
        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>
<body>
    @include('partials.navbar')
    <main class="container my-4 page-transition" id="mainContent">
        @yield('content')
    </main>
    @include('partials.footer')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS only on pages that need it
        const isAuthPage = document.querySelector('.auth-page');
        const isDashboardPage = document.querySelector('.dashboard-page');
        const isMyRoomsPage = document.querySelector('.my-rooms-page');
        const isListRoomPage = document.querySelector('.list-room-page');
        
        if (!isAuthPage && !isDashboardPage && !isMyRoomsPage && !isListRoomPage) {
            AOS.init({
                duration: 800,
                easing: 'ease-out-cubic',
                once: true,
                offset: 100,
                disable: 'mobile'
            });
        }
        
        // Add loading animation to buttons
        document.addEventListener('DOMContentLoaded', function() {
            const primaryButtons = document.querySelectorAll('.btn-primary');
            primaryButtons.forEach(btn => {
                btn.addEventListener('mouseenter', function() {
                    this.classList.add('pulse');
                });
                btn.addEventListener('mouseleave', function() {
                    this.classList.remove('pulse');
                });
            });
            
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.classList.add('bounce');
            });
        });
    </script>
    @yield('scripts')
</body>
</html> 