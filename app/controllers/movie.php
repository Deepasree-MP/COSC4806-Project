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
        $review = null;

        if (empty($title)) {
            $log['error'] = "Please enter a movie title.";
            error_log("Error: " . $log['error']);
        } else {
            $apiKey = $_ENV['OMDB_API_KEY'] ?? null;
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

                    // ✅ Always trigger Gemini review if rating exists
                    if (isset($_SESSION['user']['id'])) {
                        $db = db_connect();
                        $stmt = $db->prepare("SELECT rating FROM mv_ratings WHERE movie_title = ? AND user_id = ? LIMIT 1");
                        $stmt->execute([$title, $_SESSION['user']['id']]);
                        $userRating = $stmt->fetchColumn();

                        error_log("User rating for Gemini: " . ($userRating ?? 'none'));

                        if ($userRating) {
                            require_once 'app/models/Api.php';
                            $review = Api::getGeminiReview($title, $userRating);
                            error_log("Gemini review generated.");
                        }
                    }
                }
            }
        }

        $this->view('movie/result', [
            'movie' => $movie,
            'log' => $log,
            'title' => $title,
            'avgRating' => $avgRating ?? null,
            'review' => $review
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
            $stmt = $db->prepare("SELECT id FROM mv_ratings WHERE movie_title = ? AND user_id = ?");
            $stmt->execute([$movieTitle, $userId]);

            if ($stmt->fetchColumn()) {
                $update = $db->prepare("UPDATE mv_ratings SET rating = ?, created_at = CURRENT_TIMESTAMP WHERE movie_title = ? AND user_id = ?");
                $update->execute([$rating, $movieTitle, $userId]);
                error_log("Rating updated for $movieTitle");
                $_SESSION['success'] = "Rating updated!";
            } else {
                $insert = $db->prepare("INSERT INTO mv_ratings (movie_title, rating, user_id) VALUES (?, ?, ?)");
                $insert->execute([$movieTitle, $rating, $userId]);
                error_log("New rating inserted for $movieTitle");
                $_SESSION['success'] = "Thanks for rating!";
            }
        } catch (PDOException $e) {
            error_log("Rating error: " . $e->getMessage());
            $_SESSION['error'] = "Rating failed.";
        }

        header("Location: /movie?title=" . urlencode($movieTitle) . "&rated=1");
        exit;
    }

    public function top() {
        error_log("Movie::top() called");
        $userModel = $this->model('User');
        $topMovies = $userModel->getTopRatedMovies(10);
        $this->view('movie/top', ['topMovies' => $topMovies]);
    }

    public function myratings() {
        error_log("Movie::myratings() called");

        if (!isset($_SESSION['user']['id'])) {
            $_SESSION['error'] = "Please log in to view your ratings.";
            header("Location: /login");
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $userModel = $this->model('User');
        $ratings = $userModel->getRatingsByUser($userId);

        $this->view('movie/myratings', ['ratings' => $ratings]);
    }

    public function logs() {
        error_log("Movie::logs() called");

        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            error_log("Unauthorized access to logs()");
            header("Location: /login");
            exit;
        }

        $userModel = $this->model('User');
        $logs = $userModel->getSearchLogs(100);

        $this->view('movie/logs', ['logs' => $logs]);
    }
}
