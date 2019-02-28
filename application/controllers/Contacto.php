<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Contacto extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->database();
    $this->load->model('Contacto_model', 'contacto');
  }

  public function index()
	{
    $this->load->view('contacto/formulario');
	}

  public function admin()
  {
    $this->load->view('admin/contacto/index');
  }

  public function procesarFormularioContacto()
  {
    $data = array();

    $data['nombre'] = $this->input->post('name');
    $data['email'] = $this->input->post('email');
    $data['telefono'] = $this->input->post('phone');
    $data['mensaje'] = $this->input->post('message');

    $result = $this->contacto->create($data);
    if($result)
    {
      redirect('contacto/exito');
    }
  else
    {
      redirect('contacto/error');
    }
  }

  public function exito()
  {
    $this->load->view('contacto/exito');
  }

  public function error()
  {
    $this->load->view('contacto/error');
  }
}
