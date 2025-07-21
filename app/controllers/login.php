<?php

class Login extends Controller {

    public function index() {
        
        $this->view('login/index');
    }

    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $userModel = $this->model('User');
            $user = $userModel->findByUsername($username);

            if ($user && hash('sha256', $password) === $user['password_hash']) {
                $_SESSION['auth'] = 1;
                $_SESSION['user'] = $user;
                $_SESSION['role'] = $user['role'];
                $_SESSION['login_time'] = date('Y-m-d H:i:s');

                
                $userModel->logLogin($user['id']);

                header('Location: /home');
                exit;
            } else {
                $_SESSION['error'] = "Invalid username or password";
                header('Location: /login');
                exit;
            }
        } else {
            header('Location: /login');
            exit;
        }
    }

    public function logout() {
        session_destroy();
        header('Location: /login');
        exit;
    }

    public function test() {
        $userModel = $this->model('User');
        $user = $userModel->findByUsername('admin');

        if ($user) {
            echo "Admin Found: " . $user['username'] . " | Role: " . $user['role'] . "<br>";
            $userModel->logLogin($user['id']);
            echo "Login Logged.";
        } else {
            echo "Admin user not found.";
        }
    }

}
