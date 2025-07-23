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
            This Movie App provides a very easy way to find any movie you'd like to watch.<br>
            Just type it in the search box no login required!<br>
            You can directly see vital details for each movie, like the title, year, ratings, etc.<br>
            If you sign up and sign in, you are able to rate movies from 1 to 5 stars.<br> 
            Your ratings are remembered, so you can see them at any time.<br>
            Want to read reviews of movies? <br>
            The app generates reviews for you through AI based on your ratings.<br> 
            You can also read reviews of trending movies.<br>
            It runs on computers and phones, and no technical expertise is required on your part to utilize it.<br> 
            The application is designed for all people easy, quick, and enjoyable.<br>
            Discover, rate, and review your favorite movies with just a few clicks!
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

