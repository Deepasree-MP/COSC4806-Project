<?php

class Movie extends Controller {
    public function index() {
        $this->view('movie/index');
    }

    public function search() {
        if (isset($_GET['title']) && !empty($_GET['title'])) {
            $title = urlencode($_GET['title']);
            $apiKey = getenv('OMDB_API_KEY');

            // DEBUG: If secret is missing
            if (!$apiKey) {
                $this->view('movie/result', ['movie' => null, 'log' => ['error' => 'API key is missing from environment.']]);
                return;
            }

            $url = "http://www.omdbapi.com/?apikey=$apiKey&t=$title";

            $response = @file_get_contents($url);  // suppress warning
            $movie = json_decode($response, true);

            // Check if the response is valid JSON
            if (!$movie || !isset($movie['Response'])) {
                $this->view('movie/result', [
                    'movie' => null,
                    'log' => [
                        'api_key' => $apiKey,
                        'url' => $url,
                        'raw_response' => $response,
                        'error' => 'Invalid or no response from OMDb API.'
                    ]
                ]);
                return;
            }

            // Success
            $log = [
                'api_key' => $apiKey,
                'url' => $url,
                'raw_response' => $response
            ];

            $this->view('movie/result', ['movie' => $movie, 'log' => $log]);
        } else {
            $_SESSION['error'] = "Please enter a movie title.";
            header('Location: /movie');
            exit;
        }
    }

}
