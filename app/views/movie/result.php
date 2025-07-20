<div class="container mt-4">
  <?php if (isset($movie) && $movie['Response'] === 'True'): ?>
    <h3><?= htmlspecialchars($movie['Title']) ?> (<?= $movie['Year'] ?>)</h3>
    <p><strong>IMDb Rating:</strong> <?= $movie['imdbRating'] ?></p>
    <p><strong>Metascore:</strong> <?= $movie['Metascore'] ?></p>
    <p><strong>Plot:</strong> <?= $movie['Plot'] ?></p>
    <img src="<?= $movie['Poster'] ?>" alt="Poster" class="img-thumbnail" width="200">
  <?php else: ?>
    <div class="alert alert-danger">
      ❌ Movie not found.
      <?php if (isset($log['error'])): ?>
        <br><small>Error: <?= htmlspecialchars($log['error']) ?></small>
      <?php endif; ?>
    </div>
  <?php endif; ?>

  <a href="/movie" class="btn btn-secondary mt-3">🔙 Search Another</a>

  <?php if (isset($log)): ?>
    <hr>
    <h5 class="mt-4">🔍 Debug Info</h5>
    <pre style="background-color:#f8f9fa; padding: 1rem; border: 1px solid #ccc;">
OMDB_API_KEY: <?= htmlspecialchars($log['api_key'] ?? 'Not set') ?>

Request URL:
<?= htmlspecialchars($log['url'] ?? 'N/A') ?>


Raw Response:
<?= htmlspecialchars($log['raw_response'] ?? 'N/A') ?>
    </pre>
  <?php endif; ?>
</div>
