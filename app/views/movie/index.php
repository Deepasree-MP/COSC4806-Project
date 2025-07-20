<div class="container mt-4">
  <h2>🎬 Search for a Movie</h2>

  <form method="GET" action="/movie/search">
    <div class="mb-3">
      <input type="text" name="title"
             value="<?= htmlspecialchars($title ?? '') ?>"
             class="form-control"
             placeholder="Enter movie title"
             required />
    </div>
    <button class="btn btn-primary">Search</button>
  </form>

  <?php if (!empty($log['error'])): ?>
    <div class="alert alert-danger mt-3">
      ❌ <?= htmlspecialchars($log['error']) ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($movie) && $movie['Response'] === 'True'): ?>
    <div class="card mt-4">
      <div class="card-body">
        <h3><?= htmlspecialchars($movie['Title']) ?> (<?= $movie['Year'] ?>)</h3>
        <p><strong>IMDb Rating:</strong> <?= $movie['imdbRating'] ?></p>
        <p><strong>Metascore:</strong> <?= $movie['Metascore'] ?></p>
        <p><strong>Plot:</strong> <?= $movie['Plot'] ?></p>
        <img src="<?= $movie['Poster'] ?>" alt="Poster" class="img-thumbnail mt-2" width="200">
      </div>
    </div>
  <?php endif; ?>

  <?php if (!empty($log)): ?>
    <hr>
    <h5 class="mt-4">🔍 Debug Info</h5>
    <pre style="background-color:#f8f9fa; padding: 1rem; border: 1px solid #ccc;">
OMDB_API_KEY: <?= htmlspecialchars($log['api_key'] ?? 'N/A') ?>


Request URL:
<?= htmlspecialchars($log['url'] ?? '') ?>


Raw Response:
<?= htmlspecialchars($log['raw_response'] ?? '') ?>
    </pre>
  <?php endif; ?>
</div>
