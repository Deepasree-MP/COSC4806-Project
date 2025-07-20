<?php

class App {

    protected $controller = 'login';
    protected $method = 'index';
    protected $special_url = ['apply'];
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        // If no controller in URL, default based on login status
        if (!isset($url[1]) || empty($url[1])) {
            $this->controller = isset($_SESSION['auth']) && $_SESSION['auth'] == 1 ? 'home' : 'login';
        }

        // If controller exists in URL, load it
        if (isset($url[1]) && file_exists('app/controllers/' . $url[1] . '.php')) {
            $this->controller = $url[1];
            $_SESSION['controller'] = $this->controller;

            // If it's a special URL, always default to 'index' method
            if (in_array($this->controller, $this->special_url)) {
                $this->method = 'index';
            }

            unset($url[1]);
        } elseif (!file_exists('app/controllers/' . $this->controller . '.php')) {
            // Redirect to home if controller file doesn't exist
            header('Location: /home');
            die;
        }

        // Load the controller file
        require_once 'app/controllers/' . $this->controller . '.php';

        // Instantiate the controller class
        $this->controller = new $this->controller;

        // Check for method in URL
        if (isset($url[2]) && method_exists($this->controller, $url[2])) {
            $this->method = $url[2];
            $_SESSION['method'] = $this->method;
            unset($url[2]);
        }

        // Set params
        $this->params = $url ? array_values($url) : [];

        // Run the controller + method + params
        call_user_func_array([$this->controller, $this->method], $this->params);		
    }

    public function parseUrl() {
        $u = "{$_SERVER['REQUEST_URI']}";
        $url = explode('/', filter_var(rtrim($u, '/'), FILTER_SANITIZE_URL));
        unset($url[0]); // remove first empty part
        return $url;
    }
}
