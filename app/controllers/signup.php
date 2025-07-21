<?php

class Signup extends Controller
{
    public function index()
    {
        $this->view('signup/index');
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm'] ?? '';
            $error = '';

            if ($username === '' || $password === '' || $confirm === '') {
                $error = 'All fields are required.';
            } elseif ($password !== $confirm) {
                $error = 'Passwords do not match.';
            } else {
                $userModel = $this->model('User');
                if ($userModel->findByUsername($username)) {
                    $error = 'Username already exists.';
                } else {
                    $hashed = hash('sha256', $password);
                    $userModel->createUser($username, $hashed);
                    header('Location: /login');
                    exit;
                }
            }
            $this->view('signup/index', ['error' => $error, 'username' => $username]);
        } else {
            $this->view('signup/index');
        }
    }
}
