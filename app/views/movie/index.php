<div class="container mt-4">
  <h2>🎬 Search for a Movie</h2>

  <?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
  <?php endif; ?>

  <form method="GET" action="/movie/search">
    <div class="mb-3">
      <input type="text" name="title" class="form-control" placeholder="Enter movie title" required />
    </div>
    <button class="btn btn-primary">Search</button>
  </form>
</div>
