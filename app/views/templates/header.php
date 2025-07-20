<?php error_log("Header loaded"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Movie Rating App - COSC4806 Final Project" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <title>Movie App</title>
</head>
<body>
<header class="navbar navbar-expand-lg navbar-dark bg-dark" role="banner">
  <div class="container-fluid">
    <a class="navbar-brand" href="/movie" tabindex="0">MovieApp</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNavbar">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0" role="navigation">
        <li class="nav-item"><a class="nav-link" href="/movie" tabindex="0">Search Movies</a></li>
        <li class="nav-item"><a class="nav-link" href="/movie/recent" tabindex="0">Recent Releases</a></li>
        <li class="nav-item"><a class="nav-link" href="/movie/top" tabindex="0">Top 10 by Rating</a></li>
      </ul>
      <ul class="navbar-nav ms-auto">
        <?php if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== 1): ?>
          <li class="nav-item">
            <a class="btn btn-outline-light" href="/login" role="button" tabindex="0">Sign In</a>
          </li>
        <?php else: ?>
          <li class="nav-item me-2 text-white mt-2">
            <span aria-label="Signed in as"><?= htmlspecialchars($_SESSION['user']['username']) ?></span>
          </li>
          <li class="nav-item">
            <a class="btn btn-danger" href="/login/logout" role="button" tabindex="0">Sign Out</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</header>
<main class="container-fluid mt-4" role="main" aria-label="Main content">
