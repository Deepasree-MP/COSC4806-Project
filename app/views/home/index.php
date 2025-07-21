<?php require_once 'app/views/templates/header.php'; ?>
<div class="mt-4">
    <h2 class="mb-3">
        <?php
        if (!empty($_SESSION['auth']) && !empty($_SESSION['user']['username'])) {
            echo "Welcome, " . htmlspecialchars($_SESSION['user']['username']);
        } else {
            echo "Welcome Guest";
        }
        ?>
    </h2>

    <div class="mb-4">
        <p>
            This Movie App helps you search for any movie using a simple search box.<br>
            You do not need to log in just to look up movies.<br>
            You can see key details about each movie, like title, year, ratings, and more.<br>
            If you create an account and log in, you can rate movies from 1 to 5.<br>
            Your ratings are saved so you can see them again any time.<br>
            The app uses AI to generate movie reviews based on your ratings.<br>
            You can read your own reviews or see reviews for popular movies.<br>
            The app is easy to use, works on phones and computers, and is made for everyone.<br>
            You do not need any tech skills to use this app.<br>
            It’s a great way to discover, rate, and review your favorite films quickly and easily.
        </p>
    </div>

    <?php if (empty($_SESSION['auth'])): ?>
        <div class="alert alert-warning" role="alert">
            You are not logged in.
        </div>
        <a href="/login" class="btn btn-primary mb-2">Go to Login Page</a>
    <?php endif; ?>
    <a href="/movie" class="btn btn-success mb-2">Search Movie</a>
</div>

<?php require_once 'app/views/templates/footer.php'; ?>

