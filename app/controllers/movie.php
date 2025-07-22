<?php

class Movie extends Controller {

    public function index() {
        error_log("Movie::index() called");
        $this->view('movie/index');
    }

    public function search() {
        error_log("Movie::search() called");

        $title = isset($_GET['title']) ? trim($_GET['title']) : '';
        $movie = null;
        $log = [];
        $review = null;

        if (empty($title)) {
            $log['error'] = "Please enter a movie title.";
        } else {
            $apiKey = getenv('OMDB_API_KEY');
            if (!$apiKey) {
                $log['error'] = "API key is missing.";
            } else {
                $url = "http://www.omdbapi.com/?apikey=$apiKey&t=" . urlencode($title);
                $response = @file_get_contents($url);
                $decoded = json_decode($response, true);

                $log = ['api_key' => $apiKey, 'url' => $url, 'raw_response' => $response];

                if (!$decoded || $decoded['Response'] !== 'True') {
                    $log['error'] = $decoded['Error'] ?? 'Invalid OMDb response.';
                } else {
                    $movie = $decoded;

                    $userModel = $this->model('User');
                    $userModel->logSearch($title);
                    $avgRating = $userModel->getAverageRating($title);

                    
                    $fullPrompt = "Give a short movie review for the movie titled '" . $movie['Title'] . "'.
Released in " . $movie['Year'] . ", directed by " . $movie['Director'] . ", written by " . $movie['Writer'] . ",
and starring " . $movie['Actors'] . ".";

                    require_once 'app/models/Api.php';
                    $review = Api::getGeminiReview($fullPrompt);
                }
            }
        }

        $existingRating = null;
        if (isset($_SESSION['user']['id']) && $movie) {
            $db = db_connect();
            $stmt = $db->prepare("SELECT rating FROM mv_ratings WHERE movie_title = ? AND user_id = ? LIMIT 1");
            $stmt->execute([$title, $_SESSION['user']['id']]);
            $existingRating = $stmt->fetchColumn();
        }

        $this->view('movie/result', [
            'movie' => $movie,
            'log' => $log,
            'title' => $title,
            'avgRating' => $avgRating ?? null,
            'existingRating' => $existingRating,
            'review' => $review
        ]);
    }

    public function rate() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        header('Content-Type: application/json; charset=utf-8');
        ini_set('display_errors', 0); 
        ob_clean(); 

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Invalid request method']);
            exit;
        }

        $movieTitle = trim($_POST['movie_title'] ?? '');
        $rating = (int) ($_POST['rating'] ?? 0);
        $userId = $_SESSION['user']['id'] ?? null;

        if (!$userId || $rating < 1 || $rating > 5 || empty($movieTitle)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Invalid input.']);
            exit;
        }

        try {
            $db = db_connect();
            $stmt = $db->prepare("SELECT id FROM mv_ratings WHERE movie_title = ? AND user_id = ?");
            $stmt->execute([$movieTitle, $userId]);

            if ($stmt->fetchColumn()) {
                $update = $db->prepare("UPDATE mv_ratings SET rating = ?, created_at = CURRENT_TIMESTAMP WHERE movie_title = ? AND user_id = ?");
                $update->execute([$rating, $movieTitle, $userId]);
            } else {
                $insert = $db->prepare("INSERT INTO mv_ratings (movie_title, rating, user_id) VALUES (?, ?, ?)");
                $insert->execute([$movieTitle, $rating, $userId]);
            }

            $userModel = $this->model('User');
            $avgRating = $userModel->getAverageRating($movieTitle);

            echo json_encode([
                'success' => true,
                'avgRating' => round($avgRating, 1),
                'yourRating' => $rating
            ]);
            exit;
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => 'Database error']);
            exit;
        }
    }


    public function top() {
        $userModel = $this->model('User');
        $topMovies = $userModel->getTopRatedMovies(10);
        $this->view('movie/top', ['topMovies' => $topMovies]);
    }

    public function myratings() {
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
        if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            header("Location: /login");
            exit;
        }

        $userModel = $this->model('User');
        $logs = $userModel->getSearchLogs(100);
        $this->view('movie/logs', ['logs' => $logs]);
    }

    public function autocomplete() {
        header('Content-Type: application/json');
        $title = $_GET['title'] ?? '';
        if (!$title) {
            echo json_encode([]);
            exit;
        }
        $apiKey = $_ENV['OMDB_API_KEY'] ?? null;
        $url = 'http://www.omdbapi.com/?apikey=' . $apiKey . '&s=' . urlencode($title) . '&type=movie';
        $response = @file_get_contents($url);
        $data = json_decode($response, true);
        $results = [];
        if ($data && !empty($data['Search'])) {
            $results = array_slice($data['Search'], 0, 7); // Limit to top 7
        }
        echo json_encode($results);
        exit;
    }

}
