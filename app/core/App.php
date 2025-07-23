<?php

class App {

    protected $controller = 'home'; // Set to 'home'
    protected $method = 'index';
    protected $special_url = ['apply'];
    protected $params = [];

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id']) && !isset($_SESSION['auth'])) {
            session_unset();
            session_destroy();
            session_start();
        }
        $url = $this->parseUrl();
        error_log("Parsed URL: " . print_r($url, true));

        if (isset($url[1]) && file_exists('app/controllers/' . $url[1] . '.php')) {
            $this->controller = $url[1];
            unset($url[1]);
        } elseif (!isset($url[1]) || empty($url[1])) {
            $this->controller = 'home'; 
        }

        require_once 'app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        if (isset($url[2]) && method_exists($this->controller, $url[2])) {
            $this->method = $url[2];
            unset($url[2]);
        }

        $this->params = $url ? array_values($url) : [];
        error_log("Routing to " . get_class($this->controller) . "::" . $this->method . "()");
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl() {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $url = explode('/', filter_var(rtrim($path, '/'), FILTER_SANITIZE_URL));
        unset($url[0]);
        return $url;
    }
}
