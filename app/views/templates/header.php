<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Movie App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        html, body { height: 100%; }
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: radial-gradient(circle at 20% 30%, #181e33 0%, #253060 60%, #10121a 100%);
            color: #f1f7fa !important;
            overflow-x: hidden;
            position: relative;
        }
        .main-content { flex: 1 0 auto; z-index: 1; }
        .star-bg {
            position: fixed; width: 100vw; height: 100vh; z-index: 0; pointer-events: none; left: 0; top: 0;
        }
        .star {
            position: absolute; border-radius: 50%; background: #fff; opacity: .7;
            box-shadow: 0 0 8px 2px #fff, 0 0 3px 1px #59f;
            animation: twinkle 2.5s infinite alternate;
        }
        @keyframes twinkle { 0% { opacity: .7; } 100% { opacity: 1; } }
        .navbar, footer.bg-dark { background: #181e1e !important; }
        .card, .rating-card {
            background: rgba(18,21,45,0.98) !important; color: #f1f7fa !important; border-radius: 1.2rem !important;
            box-shadow: 0 2px 16px 0 rgba(120,140,255,0.10), 0 1px 4px 0 rgba(50,60,100,0.23) !important;
        }
        .card .card-title, .rating-card .card-title { color: #b4cdff !important; font-weight: 600; font-size: 1.18rem; margin-bottom: .5rem; }
        .card:hover, .rating-card:hover, .card:focus, .rating-card:focus { transform: translateY(-10px) scale(1.04) rotateZ(-1deg); box-shadow: 0 10px 40px 0 rgba(120,140,255,0.30), 0 2px 12px 0 rgba(0,0,0,0.25) !important; }
        .container, main, .table, .table th, .table td { color: #f1f7fa !important; background: transparent !important; }
        .navbar-dark .navbar-nav .nav-link, .navbar-dark .navbar-brand, .navbar-dark .navbar-text { color: #f1f7fa !important; }
        h1, h2, h3, h4, h5, h6, label, .form-label { color: #b4cdff !important; }
        input, textarea, select, .form-control {
            background: #232b45 !important; color: #f1f7fa !important; border: 1px solid #6375ab !important;
        }
        input::placeholder, textarea::placeholder { color: #b4cdff !important; opacity: 0.85; }
        .btn-primary, .btn-outline-secondary, .btn-link { color: #fff !important; }
        .btn-primary { background: #475fff !important; border-color: #475fff !important; }
        .btn-outline-secondary { border-color: #b4cdff !important; color: #b4cdff !important; }
        .btn-link { color: #97bfff !important; }
        .table, .table th, .table td { color: #f1f7fa !important; background: transparent !important; }
        .alert-info, .alert-warning, .alert-danger, .alert-success { color: #fff !important; background: rgba(40,60,120,0.7) !important; border: 1px solid #6375ab !important; }
        .list-group-item { background: rgba(18,21,45,0.97) !important; color: #f1f7fa !important; border-color: #6375ab !important; }
        a, a:visited, a.nav-link { color: #97bfff !important; }
        .card-text, .small, .text-muted { color: #d9e5f6 !important; }
    </style>
</head>
<body>
<div class="star-bg">
    <?php for ($i=0; $i<32; $i++) {
        $size = rand(2,6); $x = rand(0,100); $y = rand(0,100); $d = rand(1,3);
        echo "<div class='star' style='width:{$size}px;height:{$size}px;top:{$y}vh;left:{$x}vw;animation-delay:-".($d*rand(0,10))."s'></div>";
    } ?>
</div>
<nav class="navbar navbar-expand-lg navbar-dark" aria-label="Main Navigation" role="navigation">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">MovieApp</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="/movie">Search Movies</a></li>
                <li class="nav-item"><a class="nav-link" href="/movie/top">Top Rated</a></li>
                <?php if (!empty($_SESSION['auth'])): ?>
                    <li class="nav-item"><a class="nav-link" href="/movie/myratings">My Ratings</a></li>
                    <?php if (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <li class="nav-item"><a class="nav-link" href="/movie/logs">Admin Logs</a></li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav mb-2 mb-lg-0 align-items-center">
                <?php if (!empty($_SESSION['auth']) && !empty($_SESSION['user']['username'])): ?>
                    <li class="nav-item"><a class="nav-link disabled" tabindex="-1" aria-disabled="true"><?= htmlspecialchars($_SESSION['user']['username']) ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="/login/logout">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link disabled" tabindex="-1" aria-disabled="true">Guest</a></li>
                    <li class="nav-item"><a class="nav-link" href="/login">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<main class="container main-content mt-4" role="main">
