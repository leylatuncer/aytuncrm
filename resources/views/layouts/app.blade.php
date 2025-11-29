<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AYTUN CRM Sistemi')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Sidebar Stiller */
        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: #f8f9fa;
            border-right: 1px solid #dee2e6;
            position: fixed;
            top: 56px;
            left: 0;
            width: 250px;
            z-index: 1000;
            overflow-y: auto;
            transition: transform 0.3s ease-in-out;
        }

        /* Mobil için sidebar gizli */
        @media (max-width: 767.98px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            /* Overlay */
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 56px;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 999;
            }
            .sidebar-overlay.show {
                display: block;
            }
        }

        /* Desktop için sidebar her zaman görünür */
        @media (min-width: 768px) {
            .sidebar {
                transform: translateX(0) !important;
            }
        }

        .sidebar .nav-link {
            color: #495057;
            padding: 0.75rem 1rem;
            border-radius: 0.25rem;
            margin-bottom: 0.25rem;
        }
        .sidebar .nav-link:hover {
            background-color: #e9ecef;
            color: #0d6efd;
        }
        .sidebar .nav-link.active {
            background-color: #0d6efd;
            color: white;
        }
        .sidebar .nav-link i {
            width: 20px;
        }

        /* Main content ayarları */
        .main-content {
            flex: 1;
            margin-left: 0;
            padding-top: 1rem;
        }

        @media (min-width: 768px) {
            .main-content {
                margin-left: 250px;
            }
        }

        /* Navbar responsive */
        .navbar-brand {
            font-size: 1.1rem;
        }

        @media (max-width: 575.98px) {
            .navbar-brand {
                font-size: 0.95rem;
            }
            .navbar .btn {
                font-size: 0.85rem;
                padding: 0.25rem 0.5rem;
            }
        }

        /* Kullanıcı bilgisi mobilde kısalt */
        @media (max-width: 767.98px) {
            .user-info-text {
                display: none;
            }
            .user-info-icon {
                display: inline-block;
            }
        }

        @media (min-width: 768px) {
            .user-info-text {
                display: inline;
            }
            .user-info-icon {
                display: none;
            }
        }

        /* Footer */
        footer {
            margin-left: 0;
        }

        @media (min-width: 768px) {
            footer {
                margin-left: 250px;
            }
        }

        /* Sidebar toggle butonu */
        .sidebar-toggle {
            display: none;
        }

        @media (max-width: 767.98px) {
            .sidebar-toggle {
                display: inline-block;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container-fluid">
            @auth
                <!-- Mobil Sidebar Toggle Butonu -->
                <button class="btn btn-outline-light btn-sm sidebar-toggle me-2" type="button" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
            @endauth

            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="bi bi-shield-check"></i> AYTUN CRM
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item d-flex align-items-center text-white me-2 me-lg-3">
                            <!-- Mobil: Sadece ikon -->
                            <span class="user-info-icon">
                                <i class="bi bi-person-circle fs-5"></i>
                                @if(auth()->user()->role === 'admin')
                                    <span class="badge bg-warning text-dark">A</span>
                                @endif
                            </span>
                            <!-- Desktop: Tam bilgi -->
                            <span class="user-info-text">
                                <i class="bi bi-person-circle me-2"></i>
                                <span>{{ auth()->user()->name }}</span>
                                @if(auth()->user()->role === 'admin')
                                    <span class="badge bg-warning text-dark ms-2">Admin</span>
                                @endif
                            </span>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-light btn-sm">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span class="d-none d-sm-inline">Çıkış</span>
                                </button>
                            </form>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Spacer for fixed navbar -->
    <div style="height: 56px;"></div>

    <!-- Sidebar Overlay (Sadece mobil) -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar + Main Content -->
    @auth
    <!-- Sol Panel (Sidebar) -->
    <nav class="sidebar p-3" id="sidebar">
        <div class="position-sticky">
            <ul class="nav flex-column">
                <!-- Dashboard - Herkes görebilir -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                       href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <!-- Hatırlatmalar - Herkes görebilir -->
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('reminders.*') ? 'active' : '' }}"
           href="{{ route('reminders.index') }}">
            <i class="bi bi-bell"></i> Hatırlatmalar
            @php
                $urgentCount = \App\Models\Reminder::getUrgentCount();
            @endphp
            @if($urgentCount > 0)
                <span class="badge bg-danger rounded-pill ms-2">{{ $urgentCount }}</span>
            @endif
        </a>
    </li>

                <!-- Kullanıcılar - Sadece Admin -->
                @if(auth()->user()->role === 'admin')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                           href="{{ route('users.index') }}">
                            <i class="bi bi-people"></i> Kullanıcılar
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </nav>

    <!-- Ana İçerik -->
    <main class="main-content px-3 px-md-4">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i>
                <strong>Hata!</strong> Lütfen formu kontrol edin.
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Page Content -->
        @yield('content')
    </main>
    @else
        <!-- Giriş yapılmamışsa sidebar yok -->
        <main class="py-4">
            <div class="container-fluid px-3">
                @yield('content')
            </div>
        </main>
    @endauth

    <!-- Footer -->
    <footer class="bg-light text-center py-3 mt-auto">
        <div class="container">
            <span class="text-muted small">© {{ date('Y') }} AYTUN CRM Sistemi</span>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Sidebar Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            if (sidebarToggle && sidebar && overlay) {
                // Toggle sidebar
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                    overlay.classList.toggle('show');
                });

                // Overlay'e tıklandığında sidebar'ı kapat
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                });

                // Sidebar içindeki linklere tıklandığında mobilde kapat
                if (window.innerWidth < 768) {
                    const sidebarLinks = sidebar.querySelectorAll('.nav-link');
                    sidebarLinks.forEach(link => {
                        link.addEventListener('click', function() {
                            sidebar.classList.remove('show');
                            overlay.classList.remove('show');
                        });
                    });
                }
            }
        });
    </script>

    @stack('scripts')
</body>
</html>