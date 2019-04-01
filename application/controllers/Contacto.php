<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Contacto extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->database();
    $this->load->model('Contacto_model', 'contacto');
    $this->load->model('Config_model', 'configuration');
    $this->load->library('email');
    $this->load->library('encrypt');
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

    $this->sendEmail($data);

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

  private function sendEmail($data) {

    $variables = ['$nombre','$email','$telefono','$mensaje'];
    
    $config_data = $this->configuration->get();

    $message = $config_data->message;

    $message = str_replace($variables,$data,$message);

    $title = $config_data->title;

    $title = str_replace($variables,$data,$title);
    
    if($config_data->receipts != null)
    {
      $config_data->receipts = json_decode($config_data->receipts);
    }
    else
    {
      $config_data->receipts = [];
    }

    $config = array(
      'protocol'  => 'smtp',
      'smtp_host' => $config_data->smtp,
      'smtp_port' => $config_data->port,
      'smtp_user' => $config_data->email,
      'smtp_pass' => $config_data->password,
      'mailtype'  => 'html',
      'charset'   => 'utf-8'
    );
    $this->email->initialize($config);
    $this->email->set_mailtype("html");
    $this->email->set_newline("\r\n");

    $this->email->to(implode(', ',$config_data->receipts));
    $this->email->from($config_data->email);
    $this->email->subject($title);
    $this->email->message($message);

    $this->email->send();
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
