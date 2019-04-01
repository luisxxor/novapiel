<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ubicacion extends CI_Controller {

	public function index()
	{
		$data = array('content' => 'ubicacion/index', 'title' => 'Novapiel - Ubicación');
		$this->load->view('template',$data);
	}

}
