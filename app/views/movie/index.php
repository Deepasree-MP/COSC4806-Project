<?php require_once 'app/views/templates/header.php'; ?>
<?php error_log("View: movie/index.php loaded"); ?>

<div class="container mt-4">
  <h2>Search for a Movie</h2>

  <?php if (!empty($_SESSION['error'])): ?>
    <?php error_log("View: index.php - session error = " . $_SESSION['error']); ?>
    <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
  <?php endif; ?>

  <form method="GET" action="/movie/search">
    <div class="mb-3">
      <input type="text" name="title" class="form-control" placeholder="Enter movie title" required />
    </div>
    <button class="btn btn-primary">Search</button>
  </form>
</div>
<?php require_once 'app/views/templates/footer.php'; ?>
