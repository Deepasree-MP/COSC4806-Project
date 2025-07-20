<?php error_log("View: movie/result.php loaded"); ?>

<div class="container mt-4">
  <a href="/movie" class="btn btn-secondary mb-3">← Back to Search</a>

  <?php if (!empty($log['error'])): ?>
    <?php error_log("View: result.php - Error: " . $log['error']); ?>
    <div class="alert alert-danger">
      <?= htmlspecialchars($log['error']) ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($movie) && $movie['Response'] === 'True'): ?>
    <?php error_log("View: result.php - Displaying movie: " . $movie['Title']); ?>
    <div class="card">
      <div class="card-body">
        <h3><?= htmlspecialchars($movie['Title']) ?> (<?= $movie['Year'] ?>)</h3>
        <p><strong>IMDb Rating:</strong> <?= $movie['imdbRating'] ?></p>
        <p><strong>Metascore:</strong> <?= $movie['Metascore'] ?></p>
        <p><strong>Plot:</strong> <?= $movie['Plot'] ?></p>
        <img src="<?= $movie['Poster'] ?>" alt="Poster" class="img-thumbnail mt-2" width="200">
      </div>
    </div>
  <?php else: ?>
    <?php error_log("View: result.php - No valid movie object to display"); ?>
  <?php endif; ?>

  <?php if (!empty($log)): ?>
    <hr>
    <h5 class="mt-4">Debug Info</h5>
    <pre style="background-color:#f8f9fa; padding: 1rem; border: 1px solid #ccc;">

Raw Response:
<?= htmlspecialchars($log['raw_response'] ?? '') ?>
    </pre>
  <?php endif; ?>
</div>
