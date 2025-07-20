<?php require_once 'app/views/templates/header.php'; ?>
<?php error_log("View: movie/result.php loaded"); ?>

<div class="container mt-4" role="main">
  <a href="/movie" class="btn btn-secondary mb-3">← Back to Search</a>

  <?php if (!empty($log['error'])): ?>
    <?php error_log("View: result.php - Error: " . $log['error']); ?>
    <div class="alert alert-danger" role="alert">
      <?= htmlspecialchars($log['error']) ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($movie) && $movie['Response'] === 'True'): ?>
    <?php error_log("View: result.php - Displaying movie: " . $movie['Title']); ?>

    <div class="row">
      <div class="col-md-4 text-center">
        <img src="<?= $movie['Poster'] ?>" alt="Poster for <?= htmlspecialchars($movie['Title']) ?>" class="img-fluid rounded shadow-sm" aria-label="Movie poster">
      </div>

      <div class="col-md-8">
        <h1><?= htmlspecialchars($movie['Title']) ?></h1>
        <p class="text-muted">
          <?= htmlspecialchars($movie['Year']) ?> • <?= htmlspecialchars($movie['Rated']) ?> • <?= htmlspecialchars($movie['Runtime']) ?>
        </p>

        <div class="d-flex align-items-center mb-2">
          <div class="me-4" aria-label="OMDb rating">
            <strong>OMDb Rating:</strong> <?= htmlspecialchars($movie['imdbRating']) ?>/10
          </div>
          <div aria-label="Metascore">
            <strong>Metascore:</strong> <?= htmlspecialchars($movie['Metascore']) ?>
          </div>
        </div>

        <?php if (isset($avgRating)): ?>
          <div aria-label="Average user rating">
            <strong>Avg User Rating:</strong> <?= $avgRating ?>/5
          </div>
        <?php else: ?>
          <div><em>No ratings yet</em></div>
        <?php endif; ?>

        <p class="mt-3" aria-label="Movie plot"><?= htmlspecialchars($movie['Plot']) ?></p>

        <ul class="list-unstyled">
          <li><strong>Director:</strong> <?= htmlspecialchars($movie['Director']) ?></li>
          <li><strong>Writer:</strong> <?= htmlspecialchars($movie['Writer']) ?></li>
          <li><strong>Stars:</strong> <?= htmlspecialchars($movie['Actors']) ?></li>
        </ul>

        <hr />

        <h4 class="mt-4">Rate this movie</h4>
        <?php if (isset($_SESSION['user']['id'])): ?>
          <?php
            $db = db_connect();
            $stmt = $db->prepare("SELECT rating FROM mv_ratings WHERE movie_title = ? AND user_id = ? LIMIT 1");
            $stmt->execute([$movie['Title'], $_SESSION['user']['id']]);
            $existingRating = $stmt->fetchColumn();
          ?>

          <form method="POST" action="/movie/rate" aria-label="Rate form">
            <input type="hidden" name="movie_title" value="<?= htmlspecialchars($movie['Title']) ?>">
            <div class="mb-3">
              <label for="rating" class="form-label">
                <?= $existingRating ? 'Update Your Rating' : 'Your Rating (1 to 5)' ?>:
              </label>
              <select name="rating" id="rating" class="form-select" required>
                <option value="">Select rating</option>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                  <option value="<?= $i ?>" <?= ($existingRating == $i) ? 'selected' : '' ?>><?= $i ?></option>
                <?php endfor; ?>
              </select>
            </div>
            <button class="btn btn-success">
              <?= $existingRating ? 'Update Rating' : 'Submit Rating' ?>
            </button>
          </form>
        <?php else: ?>
          <div class="alert alert-warning">Please <a href="/login">sign in</a> to rate this movie.</div>
        <?php endif; ?>
      </div>
    </div>
  <?php else: ?>
    <?php error_log("View: result.php - No valid movie object to display"); ?>
  <?php endif; ?>

  <?php if (!empty($log)): ?>
    <hr>
    <h5 class="mt-4">Debug Info</h5>
    <pre class="bg-light p-3 border">
API Key: <?= htmlspecialchars($log['api_key'] ?? 'N/A') ?>


Request URL:
<?= htmlspecialchars($log['url'] ?? '') ?>


Raw Response:
<?= htmlspecialchars($log['raw_response'] ?? '') ?>
    </pre>
  <?php endif; ?>
</div>

<?php require_once 'app/views/templates/footer.php'; ?>
