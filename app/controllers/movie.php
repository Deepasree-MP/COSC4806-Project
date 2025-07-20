<?php

class Movie extends Controller {
    public function index() {
        $this->view('movie/index');
    }

    public function search() {
        $title = isset($_GET['title']) ? trim($_GET['title']) : '';
        $movie = null;
        $log = [];

        if (empty($title)) {
            $log['error'] = "Please enter a movie title.";
        } else {
            $apiKey = getenv('OMDB_API_KEY');

            if (!$apiKey) {
                $log['error'] = "API key is missing from environment.";
            } else {
                $url = "http://www.omdbapi.com/?apikey=$apiKey&t=" . urlencode($title);
                $response = @file_get_contents($url);
                $decoded = json_decode($response, true);

                $log = [
                    'api_key' => $apiKey,
                    'url' => $url,
                    'raw_response' => $response
                ];

                if (!$decoded || $decoded['Response'] !== 'True') {
                    $log['error'] = $decoded['Error'] ?? 'Invalid or no response from OMDb.';
                } else {
                    $movie = $decoded;
                }
            }
        }

        $this->view('movie/index', ['movie' => $movie, 'log' => $log, 'title' => $title]);
    }


}
