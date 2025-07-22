<?php require_once 'app/views/templates/header.php'; ?>

<div class="container mt-4" role="main">
  <a href="/movie" class="btn btn-secondary mb-3">← Back to Search</a>
  <div id="rating-banner" class="alert alert-success mb-3 text-center fw-bold d-none">Rating updated!</div>

  <?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger mb-3"><?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>

  <?php if (!empty($movie) && $movie['Response'] === 'True'): ?>
    <div class="row">
      <div class="col-md-4 text-center position-relative">
        <img src="<?= $movie['Poster'] ?>" alt="Poster for <?= htmlspecialchars($movie['Title']) ?>" class="img-fluid rounded shadow-sm mb-3">
        <div id="loading-spinner" class="position-absolute top-50 start-50 translate-middle d-none" style="z-index:5">
          <div class="spinner-border text-warning" role="status"><span class="visually-hidden">Loading...</span></div>
        </div>
      </div>
      <div class="col-md-8">
        <h1><?= htmlspecialchars($movie['Title']) ?></h1>
        <p class="text-muted"><?= htmlspecialchars($movie['Year']) ?> • <?= htmlspecialchars($movie['Rated']) ?> • <?= htmlspecialchars($movie['Runtime']) ?></p>
        <div class="d-flex flex-wrap align-items-center mb-3">
          <div class="me-4"><strong>OMDb Rating:</strong> <?= htmlspecialchars($movie['imdbRating']) ?>/10</div>
          <div class="me-4"><strong>Metascore:</strong> <?= htmlspecialchars($movie['Metascore']) ?></div>
          <?php if (isset($avgRating)): ?>
            <div class="me-4"><strong>User Rating:</strong>
              <span id="avg-rating-stars">
              <?php for ($i = 1; $i <= 5; $i++): ?>
                <span style="color: gold; font-size: 1.2rem;"><?= $i <= $avgRating ? '★' : '☆' ?></span>
              <?php endfor; ?>
              </span>
            </div>
          <?php endif; ?>
          <?php if (isset($existingRating)): ?>
            <div class="me-4"><strong>Your Rating:</strong>
              <span id="your-rating-stars">
              <?php for ($i = 1; $i <= 5; $i++): ?>
                <span style="color: gold; font-size: 1.2rem;"><?= $existingRating >= $i ? '★' : '☆' ?></span>
              <?php endfor; ?>
              </span>
            </div>
          <?php endif; ?>
        </div>

        <?php if (isset($_SESSION['user']['id'])): ?>
          <div class="mb-3 d-flex align-items-center">
            <strong class="me-3"><?= isset($existingRating) ? 'Update your rating:' : 'Add your rating:' ?></strong>
            <input type="hidden" id="movieTitle" value="<?= htmlspecialchars($movie['Title']) ?>">
            <div id="starTrigger" class="d-flex align-items-center" style="gap:0.25rem; position:relative;">
              <?php $curr = intval($existingRating ?? 0); ?>
              <?php for ($i = 1; $i <= 5; $i++): ?>
                <span class="trigger-star" data-value="<?= $i ?>" aria-label="Rate <?= $i ?> stars"
                  style="cursor:pointer; font-size: 2rem; color:<?= ($curr >= $i) ? 'gold' : '#ccc' ?>;">
                  ★
                </span>
              <?php endfor; ?>
            </div>
          </div>
        <?php endif; ?>

        <p><?= htmlspecialchars($movie['Plot']) ?></p>
        <ul class="list-unstyled">
          <li><strong>Director:</strong> <?= htmlspecialchars($movie['Director']) ?></li>
          <li><strong>Writer:</strong> <?= htmlspecialchars($movie['Writer']) ?></li>
          <li><strong>Stars:</strong> <?= htmlspecialchars($movie['Actors']) ?></li>
        </ul>

        <?php if (!empty($review)): ?>
          <div class="alert alert-info mt-4 p-4 w-100">
            <h5 class="mb-2">Gemini Review</h5>
            <p class="mb-0"><?= nl2br(htmlspecialchars($review)) ?></p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  <?php else: ?>
    <div class="alert alert-warning">Movie not found.</div>
  <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const starTrigger = document.querySelectorAll('#starTrigger .trigger-star');
  const movieTitle = document.getElementById('movieTitle')?.value;
  const banner = document.getElementById('rating-banner');
  const spinner = document.getElementById('loading-spinner');
  let selected = <?= json_encode(intval($existingRating ?? 0)) ?>;

  function setTriggerStars(val) {
    starTrigger.forEach((star, i) => {
      star.style.color = i < val ? 'gold' : '#ccc';
    });
  }

  setTriggerStars(selected);

  starTrigger.forEach((star, i) => {
    star.addEventListener('mouseenter', () => setTriggerStars(i + 1));
    star.addEventListener('mouseleave', () => setTriggerStars(selected));
    star.addEventListener('click', () => {
      const value = i + 1;
      if (!movieTitle) return;
      spinner?.classList.remove('d-none');
      // Disable interaction while waiting
      starTrigger.forEach(s => s.style.pointerEvents = 'none');

      fetch('/movie/rate', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `movie_title=${encodeURIComponent(movieTitle)}&rating=${value}`
      })
      .then(res => res.json())
      .then(data => {
        spinner?.classList.add('d-none');
        // Re-enable
        starTrigger.forEach(s => s.style.pointerEvents = '');

        if (data.success) {
          selected = value;
          setTriggerStars(selected);

          // update user & avg rating stars:
          if (document.querySelector('#your-rating-stars')) {
            document.querySelector('#your-rating-stars').innerHTML =
              [...Array(5)].map((_, i) => `<span style="color: gold; font-size: 1.2rem;">${i < data.yourRating ? '★' : '☆'}</span>`).join('');
          }
          if (document.querySelector('#avg-rating-stars')) {
            document.querySelector('#avg-rating-stars').innerHTML =
              [...Array(5)].map((_, i) => `<span style="color: gold; font-size: 1.2rem;">${i < data.avgRating ? '★' : '☆'}</span>`).join('');
          }
          banner?.classList.remove('d-none');
        } else {
          alert('Rating update failed.');
        }
      })
      .catch(() => {
        spinner?.classList.add('d-none');
        starTrigger.forEach(s => s.style.pointerEvents = '');
        alert('Error connecting to server.');
      });
    });
  });
});
</script>

<?php require_once 'app/views/templates/footer.php'; ?>
