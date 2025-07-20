<?php

class Movie extends Controller {

    public function index() {
        error_log("Movie::index() called");
        $this->view('movie/index');
    }

    public function search() {
        error_log("Movie::search() called");

        $title = isset($_GET['title']) ? trim($_GET['title']) : '';
        error_log("Title received: " . $title);

        $movie = null;
        $log = [];

        if (empty($title)) {
            $log['error'] = "Please enter a movie title.";
            error_log("Error: " . $log['error']);
        } else {
            $apiKey = getenv('OMDB_API_KEY');
            error_log("OMDB_API_KEY fetched: " . ($apiKey ? 'Exists' : 'Missing'));

            if (!$apiKey) {
                $log['error'] = "API key is missing.";
                error_log("Error: " . $log['error']);
            } else {
                $url = "http://www.omdbapi.com/?apikey=$apiKey&t=" . urlencode($title);
                error_log("Requesting URL: $url");

                $response = @file_get_contents($url);
                error_log("Raw API response: " . $response);

                $decoded = json_decode($response, true);

                $log = [
                    'api_key' => $apiKey,
                    'url' => $url,
                    'raw_response' => $response
                ];

                if (!$decoded || $decoded['Response'] !== 'True') {
                    $log['error'] = $decoded['Error'] ?? 'Invalid or no response from OMDb.';
                    error_log("Error: " . $log['error']);
                } else {
                    $movie = $decoded;
                    error_log("Movie data successfully decoded: " . print_r($movie, true));

                    $userModel = $this->model('User');
                    $userModel->logSearch($title);
                    error_log("Search logged: " . $title);

                    $avgRating = $userModel->getAverageRating($title);
                    error_log("Avg rating for $title: " . ($avgRating ?? 'none'));
                }
            }
        }

        $this->view('movie/result', [
            'movie' => $movie,
            'log' => $log,
            'title' => $title,
            'avgRating' => $avgRating ?? null
        ]);
    }

    public function rate() {
        error_log("Movie::rate() called");

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            error_log("Invalid method for rating");
            header("Location: /movie");
            exit;
        }

        $movieTitle = trim($_POST['movie_title'] ?? '');
        $rating = (int) ($_POST['rating'] ?? 0);
        $userId = $_SESSION['user']['id'] ?? null;

        error_log("POST Data - Title: $movieTitle | Rating: $rating | User ID: " . ($userId ?? 'guest'));

        if ($rating < 1 || $rating > 5 || empty($movieTitle)) {
            error_log("Invalid rating or empty title");
            $_SESSION['error'] = "Invalid input.";
            header("Location: /movie");
            exit;
        }

        try {
            $db = db_connect();
            $stmt = $db->prepare("INSERT INTO mv_ratings (movie_title, rating, user_id) VALUES (?, ?, ?)");
            $stmt->execute([$movieTitle, $rating, $userId]);

            error_log("Rating INSERT successful.");
            $_SESSION['success'] = "Thanks for rating!";
        } catch (PDOException $e) {
            error_log("Rating INSERT failed: " . $e->getMessage());
            $_SESSION['error'] = "Failed to submit rating.";
        }

        header("Location: /movie?rated=" . urlencode($movieTitle));
        exit;
    }
}
