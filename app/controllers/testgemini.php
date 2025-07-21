<?php

class TestGemini extends Controller
{
    public function index()
    {
        $result = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['prompt'])) {
            require_once 'app/models/Api.php';
            $prompt = trim($_POST['prompt']);
            $result = Api::getGeminiReview($prompt);
        }

        $this->view('testgemini/index', ['result' => $result]);
    }
}
