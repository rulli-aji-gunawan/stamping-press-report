<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $slot }}</title>

    <!-- Box Icons  -->
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <!-- Styles  -->
    {{-- <link rel="shortcut icon" href="kxp_fav.png" type="image/x-icon"> --}}
    <link rel="stylesheet" href={{ asset('css/app-layout.css') }}>

</head>

<body>
    <!-- ============ Header ============ -->

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <header>
        <button id="mobile-sidebar-toggle" class="mobile-toggle-btn">
            <i class='bx bx-menu'></i>
        </button>
        <p class="welcome">Hi {{ auth()->user()->name }}</p>
        <p class="page-title">{{ $slot }}</p>
        <div class="right-header">
            <form id="link-logout" action="{{ route('logout') }}" method="post">
                @csrf
                <button class="link-logout">Logout</button>
            </form>
        </div>
    </header>

    <!-- Admin Error Popup -->
    @if (session('error_popup'))
        <div id="adminErrorPopup" class="error-popup-overlay">
            <div class="error-popup-content">
                <div class="error-popup-header">
                    <i class='bx bx-error-circle'></i>
                    <h3>Access Denied</h3>
                    <span class="close-popup" onclick="closeErrorPopup()">&times;</span>
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

    <div class="sidebar close">
        <!-- ========== Logo ============  -->
        <a href="#" class="logo-box">
            <i><img src="{{ asset('images/icon-1.png') }}" alt="Logo"></i>
            <div class="logo-name">MMKI Stamping</div>
        </a>

        <!-- ========== List ============  -->
        <ul class="sidebar-list">
            <!-- -------- Non Dropdown List Item ------- -->
            <li>
                <div class="title">
                    <a href="/dashboard" class="link">
                        <i class='bx bx-grid-alt'></i>
                        <span class="name">Dashboard</span>
                    </a>
                    <!-- <i class='bx bxs-chevron-down'></i> -->
                </div>
                <div class="submenu">
                    <a href="/dashboard" class="submenu-title">Dashboard</a>
                    <!-- submenu links here  -->
                </div>
            </li>


            <!-- -------- Dropdown List Item ------- -->
            <li class="dropdown">
                <div class="title">
                    <a href="#" class="link">
                        {{-- <i class='bx bxs-keyboard'></i> --}}
                        <i class='bx bx-task'></i>
                        <span class="name">Input Report</span>
                    </a>
                    <i class='bx bxs-chevron-down'></i>
                </div>
                <div class="submenu">
                    <a href="/input-report" class="submenu-title">Input Report</a>
                    <a href="/input-report/production" class="link">Production</a>
                    {{-- <a href="#" class="disabled-link" style="text-decoration: line-through">Tooling</a> --}}
                </div>
            </li>

            <!-- -------- Dropdown List Item ------- -->
            <li class="dropdown">
                <div class="title">
                    <a href="#" class="link">
                        <i class='bx bx-table'></i>
                        <span class="name">Data Table</span>
                    </a>
                    <i class='bx bxs-chevron-down'></i>
                </div>
                <div class="submenu">
                    <a href="#" class="submenu-title">Data Table</a>
                    <a href="{{ route('table_production') }}" class="link">Tabel Production</a>
                    <a href="{{ route('table_downtime') }}" class="link">Tabel Downtime</a>
                    <a href="{{ route('table_defect') }}" class="link">Tabel Defect</a>

                    {{-- <a href="#" class="disabled-link" style="text-decoration: line-through">Tabel Tooling</a> --}}
                </div>
            </li>


            <!-- -------- Dropdown List Item ------- -->
            <li class="dropdown">
                <div class="title">
                    <a href="#" class="link">
                        <i class='bx bx-cog'></i>
                        <span class="name">Master Data</span>
                    </a>
                    <i class='bx bxs-chevron-down'></i>
                </div>
                <div class="submenu">
                    <a href="#" class="submenu-title">Master Data</a>
                    <a href="{{ route('users') }}" class="link">Data Users</a>
                    <a href="{{ route('models') }}" class="link">List Model Items</a>
                    <a href="{{ route('process') }}" class="link">Process Name</a>
                    <a href="{{ route('downtime_categories') }}" class="link">DT Category</a>
                    <a href="{{ route('dt_classifications') }}" class="link">DT Classification</a>
                    {{-- <a href="#" class="disabled-link" style="text-decoration: line-through">Defects
                        Category</a> --}}
                </div>
            </li>
        </ul>
    </div>

    <div id="sidebar-overlay" class="sidebar-overlay"></div>

</body>


// Mendeteksi apakah session masih aktif sebelum melakukan proses logout
<script>
    document.getElementById('link-logout').addEventListener('submit', function(e) {
        // Cek apakah user masih terautentikasi
        fetch('/api/check-auth', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (!data.authenticated) {
                    // Jika tidak terautentikasi, batalkan form submission dan redirect ke home
                    e.preventDefault();
                    window.location.href = '/';
                }
                // Jika terautentikasi, biarkan form submit berjalan normal
            })
            .catch(() => {
                // Jika terjadi error, arahkan ke home
                e.preventDefault();
                window.location.href = '/';
            });
    });
</script>

<script>
    function closeErrorPopup() {
        const popup = document.getElementById('adminErrorPopup');
        if (popup) {
            popup.style.opacity = '0';
            setTimeout(() => {
                popup.remove();
            }, 200);
        }
    }

    // Auto-close after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const popup = document.getElementById('adminErrorPopup');
        if (popup) {
            setTimeout(() => {
                closeErrorPopup();
            }, 5000);
        }
    });

    // Close on overlay click
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('error-popup-overlay')) {
            closeErrorPopup();
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.querySelector('.sidebar');
        const toggleBtn = document.getElementById('mobile-sidebar-toggle');
        const overlay = document.getElementById('sidebar-overlay');

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('mobile-open');
                overlay.classList.toggle('active');
            });
        }

        if (overlay) {
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('mobile-open');
                overlay.classList.remove('active');
            });
        }

        // Tutup sidebar mobile otomatis kalau user resize ke desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth > 774) {
                sidebar.classList.remove('mobile-open');
                overlay.classList.remove('active');
            }
        });
    });
</script>
