<?php

class App {

    protected $controller = 'login';
    protected $method = 'index';
    protected $special_url = ['apply'];
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();
        error_log("Parsed URL: " . print_r($url, true));

        if (!isset($url[1]) || empty($url[1])) {
            $this->controller = isset($_SESSION['auth']) && $_SESSION['auth'] == 1 ? 'home' : 'login';
            error_log("Default controller selected: {$this->controller}");
        }

        if (isset($url[1]) && file_exists('app/controllers/' . $url[1] . '.php')) {
            $this->controller = $url[1];
            error_log("Controller file found: {$this->controller}");

            if (in_array($this->controller, $this->special_url)) {
                $this->method = 'index';
            }

            unset($url[1]);
        } elseif (!file_exists('app/controllers/' . $this->controller . '.php')) {
            error_log("Controller file not found: {$this->controller}. Redirecting to /home.");
            header('Location: /home');
            die;
        }

        require_once 'app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        if (isset($url[2]) && method_exists($this->controller, $url[2])) {
            $this->method = $url[2];
            error_log("Method set: {$this->method}");
            unset($url[2]);
        } else {
            error_log("Default method used: {$this->method}");
        }

        $this->params = $url ? array_values($url) : [];
        error_log("Params: " . print_r($this->params, true));

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl() {
        $u = "{$_SERVER['REQUEST_URI']}";
        $url = explode('/', filter_var(rtrim($u, '/'), FILTER_SANITIZE_URL));
        unset($url[0]);
        return $url;
    }
}
