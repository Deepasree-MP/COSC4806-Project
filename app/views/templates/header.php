  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Movie App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  </head>
  <body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark" aria-label="Main Navigation" role="navigation" aria-label="Main navigation">
    <div class="container-fluid">
      <a class="navbar-brand" href="/">MovieApp</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navMenu">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="/movie">Search Movies</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/movie/top">Top Rated</a>
          </li>
          <?php if (!empty($_SESSION['auth'])): ?>
            <li class="nav-item">
              <a class="nav-link" href="/movie/myratings">My Ratings</a>
            </li>
            <?php if (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
              <li class="nav-item">
                <a class="nav-link" href="/movie/logs">Admin Logs</a>
              </li>
            <?php endif; ?>
          <?php endif; ?>
        </ul>
        <ul class="navbar-nav mb-2 mb-lg-0">
          <?php if (!empty($_SESSION['auth'])): ?>
            <li class="nav-item">
              <!--<span class="navbar-text text-light me-2">Welcome, <?= htmlspecialchars($_SESSION['user']['username']) ?></span>-->
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/login/logout">Logout</a>
            </li>
          <?php else: ?>
            <li class="nav-item">
              <a class="nav-link" href="/login">Login</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
