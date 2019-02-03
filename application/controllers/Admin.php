<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('User_model', 'user');
  }

  public function index()
  {
    if (!$this->session->has_userdata('is_authenticated')) {

      var_dump($this->session->userdata());
      $this->load->view('admin/login');
    }
    else
    {
      $this->load->view('admin/home');
    }
  }

  public function process_login()
  {
    $sessArray = array();

    $username = $this->input->post('username');
    $password = $this->input->post('password');

    $this->user->setUsername($username);
    $this->user->setPassword($password);

    $result = $this->user->login();

    if($result) {
      foreach($result as $row) {
        $sessArray = array(
          'id' => $row->id,
          'username' => $row->username,
          'is_authenticated' => TRUE,
        );
        $this->session->set_userdata($sessArray);
        var_dump($this->session->userdata());
      }
      redirect('admin');
    } else {
      redirect('admin/login?msg=1');
    }
  }
}
