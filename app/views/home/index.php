<?php require_once 'app/views/templates/header.php'; ?>
<div class="container mt-4">
  <?php if (isset($_SESSION['auth']) && $_SESSION['auth'] == 1): ?>
    <div class="alert alert-success">
      Welcome back, <strong><?= htmlspecialchars($_SESSION['user']['username']); ?></strong>!<br>
      You logged in at: <?= date('Y-m-d H:i:s'); ?>
    </div>
  <?php else: ?>
    <div class="alert alert-warning">
      You are not logged in.
    </div>
  <?php endif; ?>

  <div class="mt-4 d-flex gap-3">
    <a href="/login" class="btn btn-primary">
      Go to Login Page
    </a>
  </br>
    <a href="/movie" class="btn btn-success">
       Search Movie
    </a>
  </div>
</div>
<?php require_once 'app/views/templates/footer.php'; ?>

