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
                    'raw_response' => $response
                ];

                if (!$decoded || $decoded['Response'] !== 'True') {
                    $log['error'] = $decoded['Error'] ?? 'Invalid or no response from OMDb.';
                    error_log("Error: " . $log['error']);
                } else {
                    $movie = $decoded;
                    error_log("Movie data successfully decoded: " . print_r($movie, true));
                }
            }
        }

        error_log("Rendering movie/result.php view");
        $this->view('movie/result', ['movie' => $movie, 'log' => $log, 'title' => $title]);
    }
}
