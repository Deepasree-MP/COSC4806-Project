<?php require_once 'app/views/templates/header.php'; ?>
<?php error_log("View: movie/top.php loaded"); ?>

<div class="container mt-4">
  <h2 class="mb-4">Top Rated Movies (by User Ratings)</h2>

  <?php if (!empty($topMovies)): ?>
    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Movie Title</th>
            <th scope="col">Average Rating</th>
            <th scope="col">Total Ratings</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($topMovies as $index => $row): ?>
            <tr>
              <td><?= $index + 1 ?></td>
              <td><?= htmlspecialchars($row['movie_title']) ?></td>
              <td><?= round($row['avg_rating'], 2) ?>/5</td>
              <td><?= $row['count'] ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="alert alert-info">No ratings available yet.</div>
  <?php endif; ?>
</div>

<?php require_once 'app/views/templates/footer.php'; ?>
