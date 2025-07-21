<?php require_once 'app/views/templates/header.php'; ?>
<div class="container mt-4">
  <h2 class="mb-4">Top Rated Movies (by User Ratings)</h2>
  <?php if (!empty($topMovies)): ?>
    <div class="row g-4">
      <?php foreach ($topMovies as $row): ?>
        <?php
        $omdb = null;
        if (!empty($row['movie_title'])) {
            $apiKey = $_ENV['OMDB_API_KEY'] ?? null;
            $url = 'http://www.omdbapi.com/?apikey=' . $apiKey . '&t=' . urlencode($row['movie_title']);
            $response = @file_get_contents($url);
            $data = json_decode($response, true);
            if (!empty($data) && $data['Response'] === 'True') {
                $omdb = $data;
            }
        }
        ?>
        <div class="col-md-4 col-lg-3">
          <div class="card h-100 shadow-sm">
            <?php if ($omdb && !empty($omdb['Poster']) && $omdb['Poster'] !== 'N/A'): ?>
              <img src="<?= htmlspecialchars($omdb['Poster']) ?>" class="card-img-top" alt="Poster for <?= htmlspecialchars($row['movie_title']) ?>">
            <?php else: ?>
              <div class="bg-secondary text-center text-white d-flex align-items-center justify-content-center" style="height:320px;">No Image</div>
            <?php endif; ?>
            <div class="card-body d-flex flex-column">
              <h5 class="card-title"><?= htmlspecialchars($row['movie_title']) ?></h5>
              <div class="mb-2">
                <span class="fw-bold">User Avg: <?= round($row['avg_rating'], 2) ?>/5</span>
                <span class="text-muted ms-2">(<?= $row['count'] ?> ratings)</span>
              </div>
              <?php if ($omdb && !empty($omdb['Plot']) && $omdb['Plot'] !== 'N/A'): ?>
                <p class="card-text small"><?= htmlspecialchars($omdb['Plot']) ?></p>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="alert alert-info">No ratings available yet.</div>
  <?php endif; ?>
</div>
<?php require_once 'app/views/templates/footer.php'; ?>
