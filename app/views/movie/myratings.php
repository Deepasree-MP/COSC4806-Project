<?php require_once 'app/views/templates/header.php'; ?>
<div class="container mt-4">
  <h2 class="mb-4 text-white">My Movie Ratings</h2>
  <?php if (!empty($ratings)): ?>
    <div class="row g-4">
      <?php foreach ($ratings as $row): ?>
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
        $img = ($omdb && !empty($omdb['Poster']) && $omdb['Poster'] !== 'N/A') ? $omdb['Poster'] : '';
        $plot = ($omdb && !empty($omdb['Plot']) && $omdb['Plot'] !== 'N/A') ? $omdb['Plot'] : '';
        $movieUrl = '/movie/search?title=' . urlencode($row['movie_title']);
        ?>
        <div class="col-md-4 col-lg-3">
          <a href="<?= $movieUrl ?>" style="text-decoration:none; color:inherit;" tabindex="0">
            <div class="card rating-card h-100 shadow-lg" style="cursor:pointer;">
              <?php if ($img): ?>
                <img src="<?= htmlspecialchars($img) ?>" class="card-img-top" alt="Poster for <?= htmlspecialchars($row['movie_title']) ?>">
              <?php else: ?>
                <div class="bg-secondary text-center text-white d-flex align-items-center justify-content-center" style="height:320px;">No Image</div>
              <?php endif; ?>
              <div class="card-body d-flex flex-column">
                <div class="card-title"><?= htmlspecialchars($row['movie_title']) ?></div>
                <div>
                  <a href="<?= $movieUrl ?>" class="btn btn-link p-0" style="font-size:.98rem; color:#a3bfff;">
                    View Details &raquo;
                  </a>
                </div>
                <div class="movie-rating mb-2">Your Rating: <?= $row['rating'] ?>/5</div>
                <div class="text-muted mb-2" style="font-size:.97rem;">Rated On: <?= date('Y-m-d', strtotime($row['created_at'])) ?></div>
                <?php if ($plot): ?>
                  <div class="card-text small" style="color:#d9e5f6;"><?= htmlspecialchars($plot) ?></div>
                <?php endif; ?>
              </div>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="alert alert-info">You haven't rated any movies yet.</div>
  <?php endif; ?>
</div>
<?php require_once 'app/views/templates/footer.php'; ?>
