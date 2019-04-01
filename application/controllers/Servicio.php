<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Servicio extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('Service_model', 'service');
  }

  public function index()
  {
    if ($this->session->userdata('is_authenticated') == FALSE) {
      redirect('admin');
    }
    
    $data = array('content' => 'admin/servicio/index','title' => 'Novapiel - Servicios');
    $this->load->view('admin/template',$data);
  }

  function getServices() {
    $data['services'] = $this->service->getAll();
    header('Content-Type: application/json');
    echo json_encode(['services' => $data['services']]);

  }
  
  public function create() {
    if ($this->session->userdata('is_authenticated') == FALSE) {
      echo json_encode(['status' => '403','message' => 'Permission Denied']);
      return null;
    }

    $data = json_decode($this->input->post('service_form'),true);

    $result = $this->service->form_insert($data);

    if($result['code'] == 0)
    {
      echo json_encode(['status' => '201', 'message' => 'Servicio creado exitosamente']);
    }
    else
    {
      echo json_encode(['status' => '500', 'message' => 'Servicio no creado, ha ocurrido un error']);
    }
  }

  public function update() {
    if ($this->session->userdata('is_authenticated') == FALSE) {
      echo json_encode(['status' => '403','message' => 'Permission Denied']);
      return null;
    }

    $data = json_decode($this->input->post('service_form'),true);

    $result = $this->service->form_update($data);

    if($result['code'] == 0)
    {
      echo json_encode(['status' => '200', 'message' => 'Servicio actualizado exitosamente']);
    }
    else
    {
      echo json_encode(['status' => '500', 'message' => 'Servicio no actualizado, ha ocurrido un error', 'response' => $result]);
    }
  }

  public function delete() {
    if ($this->session->userdata('is_authenticated') == FALSE) {
      echo json_encode(['status' => '403','message' => 'Permission Denied']);
      return null;
    }

    $id = $this->input->post('id');

    $result = $this->service->delete($id);

    if($result['code'] == 0)
    {
      echo json_encode(['status' => '200', 'message' => 'Servicio eliminado correctamente']);
    }
    else
    {
      echo json_encode(['status' => '500', 'message' => 'Servicio no eliminado, ha ocurrido un error', 'response' => $result]);
    }
  }    
}