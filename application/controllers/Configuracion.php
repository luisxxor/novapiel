<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Configuracion extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->database();
    $this->load->model('Config_model', 'configuration');
  }

  public function index()
	{
    if ($this->session->userdata('is_authenticated') == FALSE) {
      redirect('admin');
      return null;
    }
    
    $data = array('content' => 'admin/config/index','title' => 'Novapiel - Configuración');
    $this->load->view('admin/template',$data);
	}

  public function update() {
    if ($this->session->userdata('is_authenticated') == FALSE) {
      echo json_encode(['status' => '403','message' => 'Permission Denied']);
      return null;
    }

    $data = json_decode($this->input->post('config_form'),true);

    $data['receipts'] = json_encode($data['receipts']);

    $result = $this->configuration->form_update($data);

    if($result['code'] == 0)
    {
      echo json_encode(['status' => '200', 'message' => 'Configuración actualizada exitosamente']);
    }
    else
    {
      echo json_encode(['status' => '500', 'message' => 'Configuración no actualizada, ha ocurrido un error', 'response' => $result]);
    }
  }

  public function get() {
    if ($this->session->userdata('is_authenticated') == FALSE) {
      echo json_encode(['status' => '403','message' => 'Permission Denied']);
      return null;
    }

    $config_data = $this->configuration->get();

    if($config_data->receipts != null)
    {
      $config_data->receipts = json_decode($config_data->receipts);
    }
    else
    {
      $config_data->receipts = [];
    }

    echo json_encode(['configuration' => $config_data]);
  }

}
