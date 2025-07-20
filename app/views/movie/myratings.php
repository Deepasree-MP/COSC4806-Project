<?php require_once 'app/views/templates/header.php'; ?>
<?php error_log("View: movie/myratings.php loaded"); ?>

<div class="container mt-4">
  <h2 class="mb-4">My Movie Ratings</h2>

  <?php if (!empty($ratings)): ?>
    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Movie Title</th>
            <th>Rating</th>
            <th>Rated On</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($ratings as $index => $row): ?>
            <tr>
              <td><?= $index + 1 ?></td>
              <td><?= htmlspecialchars($row['movie_title']) ?></td>
              <td><?= $row['rating'] ?>/5</td>
              <td><?= date('Y-m-d H:i', strtotime($row['created_at'])) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="alert alert-info">You haven't rated any movies yet.</div>
  <?php endif; ?>
</div>

<?php require_once 'app/views/templates/footer.php'; ?>
