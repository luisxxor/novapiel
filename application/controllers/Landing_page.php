<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Landing_page extends CI_Controller {

  public function index()
  {
		$data = array('content' => 'landing', 'title' => 'Novapiel');
    $this->load->view('template',$data);
  }

}
