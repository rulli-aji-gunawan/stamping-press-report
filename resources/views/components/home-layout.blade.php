<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $slot }}</title>

    <!-- Box Icons  -->
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <!-- Styles  -->
    <link rel="shortcut icon" href="kxp_fav.png" type="image/x-icon">
    <!-- Design System (base first) -->
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet"
        href="{{ asset('css/home-style.css') }}?v={{ filemtime(public_path('css/home-style.css')) }}">

    <script>
        // Prevent FOUC for dark mode
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            if (savedTheme === 'dark') {
                document.documentElement.setAttribute('data-theme', 'dark');
            }
        })();
    </script>


</head>

<body>
    <!-- ============ Header ============ -->
    <header>
        <div class="logo-box">
            <i><img src="{{ asset('images/icon-1.png') }}" alt="Logo"></i>
            <div class="logo-name">MMKI-Stamping</div>
        </div>
        <p class="page-title">{{ $slot }}</p>
        <div class="right-header">
            <a href="/login" class="link-login">Login</a>
        </div>
    </header>

</body>
