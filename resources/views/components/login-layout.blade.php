<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $slot }}</title>

    <!-- Box Icons  -->
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <!-- Styles  -->
    <link rel="shortcut icon" href="kxp_fav.png" type="image/x-icon">
    <link rel="stylesheet" href="/css/login-style.css">

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
            <a href="/" class="link-to-home">Back to home</a>
        </div>
    </header>

    <!-- Link JS -->
    {{-- <script src="main.js"></script> --}}
</body>
