<?php require_once 'app/views/templates/header.php'; ?>
<?php error_log("View: movie/index.php loaded"); ?>

<div class="container mt-4">

  
  <h4 class="mb-4">
    <?php if (!empty($_SESSION['auth'])): ?>
      Welcome, <strong><?= htmlspecialchars($_SESSION['user']['username']) ?></strong>!
    <?php else: ?>
      Welcome, <strong>Guest</strong>!
    <?php endif; ?>
  </h4>

  
  <h2>Search for a Movie</h2>

  <?php if (!empty($_SESSION['error'])): ?>
    <?php error_log("View: index.php - session error = " . $_SESSION['error']); ?>
    <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
  <?php endif; ?>

  <form method="GET" action="/movie/search">
    <div class="mb-3 position-relative">
        <input type="text" name="title" id="movieSearchInput" class="form-control" placeholder="Enter movie title" autocomplete="off" required />
        <div id="movieSuggestions" class="list-group position-absolute w-100" style="z-index:10;"></div>
    </div>
    <button class="btn btn-primary">Search</button>

  </form>
  <script>
    const input = document.getElementById('movieSearchInput');
    const suggestionsBox = document.getElementById('movieSuggestions');
    let debounceTimeout = null;

    input.addEventListener('input', function () {
        const query = this.value.trim();
        clearTimeout(debounceTimeout);
        if (query.length < 2) {
            suggestionsBox.innerHTML = '';
            suggestionsBox.style.display = 'none';
            return;
        }
        suggestionsBox.innerHTML = '<div class="list-group-item">Searching…</div>';
        suggestionsBox.style.display = 'block';
        debounceTimeout = setTimeout(() => {
            fetch('/movie/autocomplete?title=' + encodeURIComponent(query))
                .then(res => res.json())
                .then(data => {
                    suggestionsBox.innerHTML = '';
                    if (data && Array.isArray(data) && data.length) {
                        data.forEach(movie => {
                            const item = document.createElement('button');
                            item.type = 'button';
                            item.className = 'list-group-item list-group-item-action';
                            item.textContent = movie.Title + ' (' + movie.Year + ')';
                            item.onclick = () => {
                                input.value = movie.Title;
                                suggestionsBox.innerHTML = '';
                                suggestionsBox.style.display = 'none';
                            };
                            suggestionsBox.appendChild(item);
                        });
                        suggestionsBox.style.display = 'block';
                    } else {
                        suggestionsBox.innerHTML = '<div class="list-group-item">No matches found</div>';
                        suggestionsBox.style.display = 'block';
                    }
                })
                .catch(() => {
                    suggestionsBox.innerHTML = '<div class="list-group-item">Error searching</div>';
                    suggestionsBox.style.display = 'block';
                });
        }, 150);
    });
    document.addEventListener('click', function(e) {
        if (!suggestionsBox.contains(e.target) && e.target !== input) {
            suggestionsBox.innerHTML = '';
            suggestionsBox.style.display = 'none';
        }
    });
  </script>
</div>

<?php require_once 'app/views/templates/footer.php'; ?>
