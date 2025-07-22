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
        <div id="loading-spinner" class="position-absolute top-50 start-50 translate-middle d-none">
          <div class="spinner-border text-warning" role="status"><span class="visually-hidden">Loading...</span></div>
        </div>
      </div>
      <div class="col-md-8">
        <h1><?= htmlspecialchars($movie['Title']) ?></h1>
        <p class="text-muted">
          <?= htmlspecialchars($movie['Year']) ?> • <?= htmlspecialchars($movie['Rated']) ?> • <?= htmlspecialchars($movie['Runtime']) ?>
        </p>
        <div class="d-flex flex-wrap align-items-center mb-3">
          <div class="me-4"><strong>OMDb Rating:</strong> <?= htmlspecialchars($movie['imdbRating']) ?>/10</div>
          <div class="me-4"><strong>Metascore:</strong> <?= htmlspecialchars($movie['Metascore']) ?></div>
          <?php if (isset($avgRating)): ?>
            <div class="me-4"><strong>User Rating:</strong>
              <span id="avg-rating-stars">
              <?php for ($i = 1; $i <= 5; $i++): ?>
                <span style="color: gold; font-size: 1.2rem;">
                  <?= $i <= $avgRating ? '★' : '☆' ?>
                </span>
              <?php endfor; ?>
              </span>
            </div>
          <?php endif; ?>
          <?php if (isset($existingRating)): ?>
            <div class="me-4"><strong>Your Rating:</strong>
              <span id="your-rating-stars">
              <?php for ($i = 1; $i <= 5; $i++): ?>
                <span style="color: gold; font-size: 1.2rem;">
                  <?= $existingRating >= $i ? '★' : '☆' ?>
                </span>
              <?php endfor; ?>
              </span>
            </div>
          <?php endif; ?>
        </div>

        <?php if (isset($_SESSION['user']['id'])): ?>
          <div class="mb-3 d-flex align-items-center">
            <strong class="me-3"><?= isset($existingRating) ? 'Update your rating:' : 'Add your rating:' ?></strong>
            <input type="hidden" id="movieTitle" value="<?= htmlspecialchars($movie['Title']) ?>">
            <div id="starTrigger" class="d-flex align-items-center" style="gap:0.25rem;">
              <?php
                $curr = intval($existingRating ?? 0);
                for ($i = 1; $i <= 5; $i++): ?>
                <span class="trigger-star"
                      data-value="<?= $i ?>"
                      aria-label="Rate <?= $i ?> stars"
                      style="cursor:pointer; font-size: 2rem; color: <?= ($curr >= $i) ? 'gold' : '#ccc' ?>;">
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
            <h5 class="mb-2"> Gemini Review</h5>
            <p class="mb-0"><?= nl2br(htmlspecialchars($review)) ?></p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  <?php else: ?>
    <div class="alert alert-warning">Movie not found.</div>
  <?php endif; ?>
</div>

<!-- Modal -->
<div class="modal fade" id="rateModal" tabindex="-1" aria-labelledby="rateModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-light">
      <div class="modal-header border-0">
        <h5 class="modal-title w-100 text-center" id="rateModalLabel">Your Rating</h5>
      </div>
      <div class="modal-body text-center">
        <div id="modalStars" class="d-flex justify-content-center" style="gap:0.25rem;">
          <?php for ($i = 1; $i <= 5; $i++): ?>
            <span class="modal-star"
                  data-value="<?= $i ?>"
                  style="cursor:pointer; font-size: 2.5rem; color:#ccc;">
              ★
            </span>
          <?php endfor; ?>
        </div>
      </div>
      <div class="modal-footer border-0 justify-content-center">
        <button type="button" class="btn btn-primary" id="confirmRating">Save</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  let modalEl = document.getElementById('rateModal');
  let modal = new bootstrap.Modal(modalEl);
  const starTrigger = document.querySelectorAll('#starTrigger .trigger-star');
  const modalStars = document.querySelectorAll('#modalStars .modal-star');
  const movieTitle = document.getElementById('movieTitle')?.value;
  const banner = document.getElementById('rating-banner');
  const spinner = document.getElementById('loading-spinner');
  let selected = <?= json_encode(intval($existingRating ?? 0)) ?>;
  let temp = selected;

  function setTriggerStars(val) {
    starTrigger.forEach((star, i) => {
      star.style.color = i < val ? 'gold' : '#ccc';
    });
  }
  function setModalStars(val) {
    modalStars.forEach((star, i) => {
      star.style.color = i < val ? 'gold' : '#ccc';
    });
  }

  starTrigger.forEach((star, i) => {
    star.addEventListener('click', () => {
      temp = i + 1;
      setModalStars(temp);
      modal.show();
    });
    star.addEventListener('mouseenter', () => setTriggerStars(i + 1));
    star.addEventListener('mouseleave', () => setTriggerStars(selected));
  });
  setTriggerStars(selected);

  modalStars.forEach((star, i) => {
    star.addEventListener('mouseenter', () => setModalStars(i + 1));
    star.addEventListener('click', () => {
      temp = i + 1;
      setModalStars(temp);
    });
  });
  modalEl.addEventListener('hidden.bs.modal', () => setModalStars(selected));

  document.getElementById('confirmRating').onclick = function() {
    selected = temp;
    setTriggerStars(selected);
    setModalStars(selected);
    modal.hide();
    if (!movieTitle) return;
    spinner?.classList.remove('d-none');
    fetch('/movie/rate', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `movie_title=${encodeURIComponent(movieTitle)}&rating=${selected}`
    })
    .then(res => res.json())
    .then(data => {
      spinner?.classList.add('d-none');
      if (data.success) {
        document.querySelector('#avg-rating-stars').innerHTML =
          [...Array(5)].map((_, i) => `<span style="color: gold; font-size: 1.2rem;">${i < data.avgRating ? '★' : '☆'}</span>`).join('');
        document.querySelector('#your-rating-stars').innerHTML =
          [...Array(5)].map((_, i) => `<span style="color: gold; font-size: 1.2rem;">${i < data.yourRating ? '★' : '☆'}</span>`).join('');
        banner?.classList.remove('d-none');
      } else {
        alert('Rating update failed.');
      }
    })
    .catch(() => {
      spinner?.classList.add('d-none');
      alert('Error connecting to server.');
    });
  };
});
</script>

<?php require_once 'app/views/templates/footer.php'; ?>
