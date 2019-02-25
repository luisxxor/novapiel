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
    	$this->load->view('admin/login');
    }
    else
    {
      $data = array('content' => 'admin/home');
		  $this->load->view('admin/template',$data);
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
      redirect('admin/?msg=1');
    }
  }

	public function logout() {
		$this->session->unset_userdata('id');
		$this->session->unset_userdata('username');
		$this->session->unset_userdata('is_authenticated');
		$this->session->sess_destroy();
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		redirect('admin');
	}
}
