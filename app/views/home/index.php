  <div class="container">

  <?php if (isset($_SESSION['just_logged_in'])): ?>
  <div class="position-fixed top-0 end-0 p-3" style="z-index: 11">
    <div id="loginToast" class="toast align-items-center text-white bg-success border-0"
         role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
      <div class="d-flex">
        <div class="toast-body">
          Welcome back, <?= htmlspecialchars($_SESSION['username']); ?>!
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto"
                data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
  </div>
  <?php endif; ?>
