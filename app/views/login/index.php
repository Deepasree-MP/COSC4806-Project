<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f6fa;
        }
        .login-card {
            max-width: 380px;
            margin: 70px auto;
            padding: 2.5rem 2rem 2rem 2rem;
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 2px 24px 0 rgba(80,110,180,0.07), 0 1.5px 8px 0 rgba(80,110,180,0.13);
        }
        .login-card h2 {
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .login-card .btn {
            min-width: 120px;
        }
        .login-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }
    </style>
</head>
<body>
    <?php require_once 'app/views/templates/header.php'; ?>
    <div class="login-card">
        <h2>Login</h2>
        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form method="POST" action="/login/authenticate" autocomplete="off">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required autofocus />
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required />
            </div>
            <div class="login-actions mt-4">
                <button type="submit" class="btn btn-primary">Login</button>
                <a href="/signup" class="btn btn-outline-secondary">Sign Up</a>
            </div>
        </form>
        <div class="text-center mt-3">
            <a href="/movie" class="btn btn-link">Back to Search</a>
        </div>
    </div>
    <?php require_once 'app/views/templates/footer.php'; ?>
</body>
</html>
