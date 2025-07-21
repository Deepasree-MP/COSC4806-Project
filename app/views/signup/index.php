<?php require_once 'app/views/templates/header.php'; ?>
<div class="container mt-5" style="max-width: 400px;">
    <h2 class="mb-4">Sign Up</h2>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form action="/signup/register" method="post" autocomplete="off">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" id="username" class="form-control" required value="<?= htmlspecialchars($username ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="confirm" class="form-label">Confirm Password</label>
            <input type="password" name="confirm" id="confirm" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success w-100">Sign Up</button>
    </form>
    <div class="mt-3 text-center">
        <a href="/login">Already have an account? Log in</a>
    </div>
</div>
<?php require_once 'app/views/templates/footer.php'; ?>
