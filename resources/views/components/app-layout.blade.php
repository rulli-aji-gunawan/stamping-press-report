<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $slot }} — MMKI Stamping</title>

    <!-- Box Icons -->
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <!-- Design System (base first) -->
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet"
        href="{{ asset('css/app-layout.css') }}?v={{ filemtime(public_path('css/app-layout.css')) }}">

    <script>
        // Apply dark theme instantly before rendering to avoid FOUC
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            if (savedTheme === 'dark') {
                document.documentElement.setAttribute('data-theme', 'dark');
            }
        })();
    </script>
</head>

<body>

    {{-- ── Flash Messages ── --}}
    @if (session('success'))
        <div class="alert alert-success" role="alert" id="flash-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger" role="alert" id="flash-error">
            {{ session('error') }}
        </div>
    @endif

    {{-- ── Header ── --}}
    <header>
        {{-- Mobile Hamburger --}}
        <button id="mobile-menu-toggle" aria-label="Buka menu navigasi" aria-expanded="false">
            <i class='bx bx-menu'></i>
        </button>

        <p class="welcome" aria-hidden="true">Hi {{ auth()->user()->name }}</p>
        <p class="page-title">{{ $slot }}</p>

        <div class="right-header">
            {{-- Dark Mode Toggle --}}
            <button id="theme-toggle" class="theme-toggle" aria-label="Toggle Dark Mode">
                <i class='bx bx-moon' id="theme-toggle-icon"></i>
            </button>

            {{-- Logout --}}
            <form id="link-logout" action="{{ route('logout') }}" method="post">
                @csrf
                <button class="link-logout" type="submit">Logout</button>
            </form>
        </div>
    </header>

    {{-- ── Access Denied Popup ── --}}
    @if (session('error_popup'))
        <div id="adminErrorPopup" class="error-popup-overlay" role="dialog" aria-modal="true"
            aria-labelledby="errorPopupTitle">
            <div class="error-popup-content">
                <div class="error-popup-header">
                    <i class='bx bx-error-circle' aria-hidden="true"></i>
                    <h3 id="errorPopupTitle">Access Denied</h3>
                    <span class="close-popup-btn" onclick="closeErrorPopup()" aria-label="Tutup">&times;</span>
                </div>
                <div class="error-popup-body">
                    <p>{{ session('error_popup') }}</p>
                </div>
                <div class="error-popup-footer">
                    <button type="button" class="btn-error-ok" onclick="closeErrorPopup()">OK</button>
                </div>
            </div>
        </div>
    @endif

    {{-- ── Mobile Overlay ── --}}
    <div id="sidebar-overlay" aria-hidden="true"></div>

    {{-- ── Sidebar ── --}}
    <div class="sidebar close" id="sidebar" role="navigation" aria-label="Menu utama">

        {{-- Logo --}}
        <a href="#" class="logo-box" aria-label="MMKI Stamping">
            <img src="{{ asset('images/icon-1.png') }}" alt="Logo MMKI Stamping">
            <div class="logo-name">MMKI Stamping</div>
        </a>

        {{-- Navigation List --}}
        <ul class="sidebar-list">

            {{-- Dashboard --}}
            <li>
                <div class="title">
                    <a href="/dashboard" class="link" aria-label="Dashboard">
                        <i class='bx bx-grid-alt' aria-hidden="true"></i>
                        <span class="name">Dashboard</span>
                    </a>
                </div>
                <div class="submenu">
                    <a href="/dashboard" class="submenu-title">Dashboard</a>
                </div>
            </li>

            {{-- Input Report --}}
            <li class="dropdown">
                <div class="title">
                    <a href="#" class="link" aria-label="Input Report">
                        <i class='bx bx-task' aria-hidden="true"></i>
                        <span class="name">Input Report</span>
                    </a>
                    <i class='bx bxs-chevron-down' aria-hidden="true"></i>
                </div>
                <div class="submenu">
                    <a href="/input-report" class="submenu-title">Input Report</a>
                    <a href="/input-report/production" class="link">Production</a>
                </div>
            </li>

            {{-- Data Table --}}
            <li class="dropdown">
                <div class="title">
                    <a href="#" class="link" aria-label="Data Table">
                        <i class='bx bx-table' aria-hidden="true"></i>
                        <span class="name">Data Table</span>
                    </a>
                    <i class='bx bxs-chevron-down' aria-hidden="true"></i>
                </div>
                <div class="submenu">
                    <a href="#" class="submenu-title">Data Table</a>
                    <a href="{{ route('table_production') }}" class="link">Tabel Production</a>
                    <a href="{{ route('table_downtime') }}" class="link">Tabel Downtime</a>
                    <a href="{{ route('table_defect') }}" class="link">Tabel Defect</a>
                </div>
            </li>

            {{-- Master Data --}}
            <li class="dropdown">
                <div class="title">
                    <a href="#" class="link" aria-label="Master Data">
                        <i class='bx bx-key' aria-hidden="true"></i>
                        <span class="name">Master Data</span>
                    </a>
                    <i class='bx bxs-chevron-down' aria-hidden="true"></i>
                </div>
                <div class="submenu">
                    <a href="#" class="submenu-title">Master Data</a>
                    <a href="{{ route('users') }}" class="link">Data Users</a>
                    <a href="{{ route('models') }}" class="link">List Model Items</a>
                    <a href="{{ route('process') }}" class="link">Process Name</a>
                    <a href="{{ route('downtime_categories') }}" class="link">DT Category</a>
                    <a href="{{ route('dt_classifications') }}" class="link">DT Classification</a>
                </div>
            </li>

        </ul>
    </div>

</body>

<script>
    // ── Sidebar Toggle (Desktop) ──
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const hideTog = document.getElementById('hide-toggle');
        const showTog = document.getElementById('show-toggle');
        const menuItems = document.querySelectorAll('.sidebar-list li.dropdown');
        const mobileBtn = document.getElementById('mobile-menu-toggle');
        const overlay = document.getElementById('sidebar-overlay');

        // Desktop collapse toggle (bx-x-circle / bx-menu in .home section)
        function setupDesktopToggle() {
            if (hideTog) {
                hideTog.addEventListener('click', function() {
                    sidebar.classList.add('close');
                });
            }
            if (showTog) {
                showTog.addEventListener('click', function() {
                    sidebar.classList.remove('close');
                });
            }
        }
        setupDesktopToggle();

        // ── Mobile Sidebar Toggle ──
        function openMobileSidebar() {
            sidebar.classList.add('mobile-open');
            sidebar.classList.remove('close');
            overlay.classList.add('active');
            mobileBtn.setAttribute('aria-expanded', 'true');
            document.body.style.overflow = 'hidden';
        }

        function closeMobileSidebar() {
            sidebar.classList.remove('mobile-open');
            sidebar.classList.add('close');
            overlay.classList.remove('active');
            mobileBtn.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = '';
        }

        if (mobileBtn) {
            mobileBtn.addEventListener('click', function() {
                const isOpen = sidebar.classList.contains('mobile-open');
                isOpen ? closeMobileSidebar() : openMobileSidebar();
            });
        }

        if (overlay) {
            overlay.addEventListener('click', closeMobileSidebar);
        }

        // Close mobile sidebar on window resize to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                closeMobileSidebar();
                document.body.style.overflow = '';
            }
        });

        // ── Dropdown Menus ──
        menuItems.forEach(item => {
            const titleDiv = item.querySelector('.title');
            if (titleDiv) {
                titleDiv.addEventListener('click', function() {
                    const isActive = item.classList.contains('active');
                    // Close all
                    menuItems.forEach(i => i.classList.remove('active'));
                    if (!isActive) item.classList.add('active');
                });
            }
        });
    });

    // ── Auth Check Before Logout ──
    document.getElementById('link-logout').addEventListener('submit', function(e) {
        fetch('/api/check-auth', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content')
                }
            })
            .then(r => r.json())
            .then(data => {
                if (!data.authenticated) {
                    e.preventDefault();
                    window.location.href = '/';
                }
            })
            .catch(() => {
                e.preventDefault();
                window.location.href = '/';
            });
    });

    // ── Error Popup ──
    function closeErrorPopup() {
        const popup = document.getElementById('adminErrorPopup');
        if (popup) {
            popup.style.opacity = '0';
            popup.style.transition = 'opacity 0.2s ease';
            setTimeout(() => popup.remove(), 220);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Auto-close flash messages
        ['flash-success', 'flash-error'].forEach(id => {
            const el = document.getElementById(id);
            if (el) setTimeout(() => {
                el.style.opacity = '0';
                el.style.transition = 'opacity 0.4s';
                setTimeout(() => el.remove(), 400);
            }, 4000);
        });

        // Auto-close error popup
        const popup = document.getElementById('adminErrorPopup');
        if (popup) setTimeout(closeErrorPopup, 5000);
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('error-popup-overlay')) closeErrorPopup();
    });

    // ── Dark Mode Toggle ──
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('theme-toggle');
        const icon = document.getElementById('theme-toggle-icon');

        function updateIcon(theme) {
            icon.className = theme === 'dark' ? 'bx bx-sun' : 'bx bx-moon';
        }

        const currentTheme = localStorage.getItem('theme') || 'light';
        updateIcon(currentTheme);

        toggleBtn.addEventListener('click', function() {
            const cur = localStorage.getItem('theme') || 'light';
            const next = cur === 'light' ? 'dark' : 'light';
            if (next === 'dark') {
                document.documentElement.setAttribute('data-theme', 'dark');
            } else {
                document.documentElement.removeAttribute('data-theme');
            }
            localStorage.setItem('theme', next);
            updateIcon(next);
            window.dispatchEvent(new CustomEvent('theme-changed', {
                detail: {
                    theme: next
                }
            }));
        });
    });
</script>
