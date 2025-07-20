<?php require_once 'app/views/templates/header.php'; ?>
<?php error_log("View: movie/logs.php loaded"); ?>

<div class="container mt-4">
  <h2 class="mb-4">Recent Movie Search Logs</h2>

  <?php if (!empty($logs)): ?>
    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Movie Title</th>
            <th>Username</th>
            <th>Timestamp</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($logs as $index => $row): ?>
            <tr>
              <td><?= $index + 1 ?></td>
              <td><?= htmlspecialchars($row['movie_title']) ?></td>
              <td><?= htmlspecialchars($row['username'] ?? 'Guest') ?></td>
              <td><?= date('Y-m-d H:i', strtotime($row['created_at'])) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="alert alert-info">No search logs found.</div>
  <?php endif; ?>
</div>

<?php require_once 'app/views/templates/footer.php'; ?>
