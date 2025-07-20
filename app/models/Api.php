<?php

class Api
{
    public static function searchMovie($title)
    {
        $apiKey = $_ENV['OMDB_API_KEY'] ?? null;

        if (!$apiKey || empty($title)) {
            error_log("OMDB key missing or title empty.");
            return null;
        }

        $url = "http://www.omdbapi.com/?apikey=" . $apiKey . "&t=" . urlencode($title);
        $response = @file_get_contents($url);
        $data = json_decode($response, true);

        if (!$data || $data['Response'] !== 'True') {
            error_log("OMDb search failed: " . print_r($data, true));
            return null;
        }

        return $data;
    }

    public static function getGeminiReview($movieTitle, $rating = null)
    {
        $apiKey = $_ENV['GEMINI_API_KEY'] ?? '';
        if (empty($apiKey)) {
            error_log("❌ Gemini API key is missing.");
            return "Gemini API key not set.";
        }

        $prompt = "Please give a movie review for '$movieTitle'";
        if ($rating !== null) {
            $prompt .= " from someone who rated it $rating out of 5.";
        }

        error_log("🔁 Gemini Prompt: " . $prompt);

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=" . $apiKey;

        $data = [
            "contents" => [[
                "role" => "user",
                "parts" => [[ "text" => $prompt ]]
            ]]
        ];

        $jsonData = json_encode($data);
        error_log("📡 Sending Gemini Request to: " . $url);
        error_log("📝 Request Payload: " . $jsonData);

        $curl = curl_init($url);
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => $jsonData
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if ($error) {
            error_log("❌ CURL error: " . $error);
            return "Failed to connect to Gemini API.";
        }

        error_log("✅ Gemini Raw Response: " . $response);

        $parsed = json_decode($response, true);
        $review = $parsed['candidates'][0]['content']['parts'][0]['text'] ?? null;

        if ($review) {
            error_log("✅ Gemini Parsed Review: " . $review);
            return $review;
        } else {
            error_log("❌ Gemini review could not be parsed.");
            return "No AI review could be generated at this time.";
        }
    }
}
