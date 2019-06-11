<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('User_model', 'user');
  }

  public function index() {
    if (!$this->session->has_userdata('is_authenticated')) {
    	$this->load->view('admin/login');
    }
    else
    {
      $data = array('content' => 'admin/home', 'title' => 'Novapiel - Admin Panel');
		  $this->load->view('admin/template',$data);
    }
  }

  public function process_login() {

    $recaptcha = $this->checkRecaptcha($this->input->post('g-recaptcha-response'));

    $recaptcha = json_decode($recaptcha,true);

    if($recaptcha['success'])
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
    else
    {
      redirect('admin/?msg=2');
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

  private function getRealIpAddr() {
    if (!empty($_SERVER['HTTP_CLIENT_IP']))
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }

    return $ip;
  }

  private function checkRecaptcha($response) {
    $curl = curl_init();

    $data = array('secret' => '6LdXvJsUAAAAAKEIQrjmJbIuQVz8JO2ZVAcmEDL6', 'response' => $response, 'remoteip' => $this->getRealIpAddr());

    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);


    curl_close($curl);

    return $result;
  }

}
