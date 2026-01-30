<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - WaSender</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="sidebar">
            <div class="sidebar-header">
                <i class="fab fa-whatsapp" style="margin-right: 10px;"></i> WaSender
            </div>
            <nav class="sidebar-nav">
                <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-tachometer-alt"></i></span> Dashboard
                </a>
                <a href="{{ route('admin.wa-accounts') }}" class="nav-item {{ request()->routeIs('admin.wa-accounts') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-users"></i></span> WA Accounts
                </a>
                <a href="{{ route('admin.bots') }}" class="nav-item {{ request()->routeIs('admin.bots') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-robot"></i></span> Bots
                </a>
                <a href="{{ route('admin.campaigns') }}" class="nav-item {{ request()->routeIs('admin.campaigns') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-paper-plane"></i></span> Campaigns
                </a>
                <a href="{{ route('admin.messages') }}" class="nav-item {{ request()->routeIs('admin.messages') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-envelope"></i></span> Messages
                </a>
                <a href="{{ route('admin.templates') }}" class="nav-item {{ request()->routeIs('admin.templates') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-file-alt"></i></span> Templates
                </a>
                <a href="{{ route('admin.webhooks') }}" class="nav-item {{ request()->routeIs('admin.webhooks') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-globe"></i></span> Webhooks
                </a>
                <a href="{{ route('admin.system-logs') }}" class="nav-item {{ request()->routeIs('admin.system-logs') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-list"></i></span> System Logs
                </a>
                <a href="{{ route('admin.settings') }}" class="nav-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-cog"></i></span> Settings
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <!-- Header -->
            <header class="admin-header">
                <div class="d-flex align-center">
                    <button id="sidebar-toggle" class="btn btn-outline" style="margin-right: 1rem; display: none;">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h2 class="header-title">@yield('title')</h2>
                </div>
                
                <div class="header-actions">
                    <span class="text-muted">Admin</span>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline" style="border: none;">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </header>

            <!-- Content -->
            <div class="admin-content">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        // Mobile Sidebar Toggle
        const toggleBtn = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        
        // Show toggle on mobile
        function checkResize() {
            if (window.innerWidth <= 992) {
                toggleBtn.style.display = 'inline-flex';
            } else {
                toggleBtn.style.display = 'none';
                sidebar.classList.remove('open');
            }
        }
        
        window.addEventListener('resize', checkResize);
        checkResize();

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('open');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 992 && 
                sidebar.classList.contains('open') && 
                !sidebar.contains(e.target) && 
                e.target !== toggleBtn &&
                !toggleBtn.contains(e.target)) {
                sidebar.classList.remove('open');
            }
        });
    </script>
</body>
</html>
