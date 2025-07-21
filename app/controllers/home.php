<?php

class Home extends Controller {

    public function index() {
      session_start();
      $user = $this->model('User');
      $data = $user->getAllUsers();

      
      $this->view('home/index', ['users' => $data]);
      die;
    }

}
