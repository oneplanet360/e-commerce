<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aura | Discover the Extraordinary</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --brand-red: #f53003;
            --bg-dark: #0a0a0a;
            --card-dark: #161615;
            --text-main: #ededec;
            --text-muted: #a1a09a;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-main);
            overflow-x: hidden;
        }

        .navbar {
            background: rgba(10, 10, 10, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            padding: 20px 0;
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: -1px;
            font-size: 1.5rem;
            color: var(--text-main) !important;
        }

        .hero-section {
            padding: 80px 0 60px;
            text-align: center;
            position: relative;
            min-height: 80vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .hero-video-bg {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            z-index: -1;
            filter: brightness(0.4) grayscale(0.2);
            opacity: 0.6;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: clamp(3rem, 10vw, 6rem);
            font-weight: 700;
            letter-spacing: -3px;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #fff 0%, #a1a09a 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .empty-state {
            padding: 40px;
            border: 1px dashed rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            color: var(--text-muted);
        }

        /* before/after removed */
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="/">AURA</a>
            <div class="ms-auto">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/admin/dashboard') }}" class="btn btn-outline-light btn-sm px-4 rounded-pill">Dashboard</a>
                    @else
                        <a href="{{ route('admin.login') }}" class="btn btn-outline-light btn-sm px-4 rounded-pill">Admin Login</a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <main class="container">
        <header class="hero-section">
            <div class="hero-content">
                <h6 class="text-uppercase tracking-widest mb-3" style="letter-spacing: 0.5rem; color: var(--brand-red);">Discover</h6>
                <h1 class="hero-title">Experience Aura</h1>
                <p class="lead text-muted mx-auto mb-4" style="max-width: 600px;">
                    Where luxury meets technology. Discover our latest collections and transformation results.
                </p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="#collection" class="btn btn-outline-light px-5 py-3 rounded-pill">View Collection</a>
                </div>
            </div>
        </header>

        <!-- before/after section removed -->

        <!-- Product Grid (Placeholder) -->
        <section class="mb-5">
            <h4 class="fw-bold mb-4">Featured Collection</h4>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card bg-dark border-0 h-100 p-4 rounded-4" style="background: var(--card-dark) !important;">
                        <div class="aspect-square bg-secondary rounded-4 mb-3 opacity-25"></div>
                        <h6 class="fw-bold mb-1">Aura Signature S1</h6>
                        <p class="text-muted small">Elevate your daily routine with premium aesthetics.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-dark border-0 h-100 p-4 rounded-4" style="background: var(--card-dark) !important;">
                        <div class="aspect-square bg-secondary rounded-4 mb-3 opacity-25"></div>
                        <h6 class="fw-bold mb-1">Aura Vision X</h6>
                        <p class="text-muted small">Beyond sight. Beyond sound. Pure luxury.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-dark border-0 h-100 p-4 rounded-4" style="background: var(--card-dark) !important;">
                        <div class="aspect-square bg-secondary rounded-4 mb-3 opacity-25"></div>
                        <h6 class="fw-bold mb-1">Aura Connect</h6>
                        <p class="text-muted small">Seamless integration for the modern workspace.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- before/after scripts removed -->
</body>
</html>
