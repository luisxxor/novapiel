<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Servicios extends CI_Controller {

  public function botox()
  {
		$data = array('content' => 'servicio/botox');
		$this->load->view('template',$data);
  }

  public function hilos_revitalizantes()
	{
		$data = array('content' => 'servicio/hilos_revitalizantes');
		$this->load->view('template',$data);
	}

	public function mesoterapia_facial()
	{
		$data = array('content' => 'servicio/mesoterapia_facial');
		$this->load->view('template',$data);
	}

	public function prp()
	{
		$data = array('content' => 'servicio/prp');
		$this->load->view('template',$data);
	}

	public function peeling_facial()
	{
		$data = array('content' => 'servicio/peeling_facial');
		$this->load->view('template',$data);
	}

}
