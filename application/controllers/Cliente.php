<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cliente extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('Client_model', 'client');
  }

  public function index()
  {
    if ($this->session->userdata('is_authenticated') == FALSE) {
      redirect('admin');
    }
    
    $data = array('content' => 'admin/cliente/index','title' => 'Novapiel - Clientes');
    $this->load->view('admin/template',$data);
  }

  function getClients() {
    $data['clients'] = $this->client->getAll();
    header('Content-Type: application/json');
    echo json_encode(['clients' => $data['clients']]);

  }

  function getClientsWithOrders() {
    $data['clients'] = $this->client->getWithOrders();
    header('Content-Type: application/json');
    echo json_encode(['clients' => $data['clients']]);
  }
  
  public function create() {
    if ($this->session->userdata('is_authenticated') == FALSE) {
      echo json_encode(['status' => '403','message' => 'Permission Denied']);
      return null;
    }

    $data = json_decode($this->input->post('client_form'),true);

    $result = $this->client->form_insert($data);

    if($result['code'] == 0)
    {
      echo json_encode(['status' => '201', 'message' => 'Cliente creado exitosamente']);
    }
    else
    {
      echo json_encode(['status' => '500', 'message' => 'Cliente no creado, ha ocurrido un error']);
    }
  }

  public function update() {
    if ($this->session->userdata('is_authenticated') == FALSE) {
      echo json_encode(['status' => '403','message' => 'Permission Denied']);
      return null;
    }

    $data = json_decode($this->input->post('client_form'),true);

    $result = $this->client->form_update($data);

    if($result['code'] == 0)
    {
      echo json_encode(['status' => '200', 'message' => 'Cliente actualizado exitosamente']);
    }
    else
    {
      echo json_encode(['status' => '500', 'message' => 'Cliente no actualizado, ha ocurrido un error', 'response' => $result]);
    }
  }

  public function delete() {
    if ($this->session->userdata('is_authenticated') == FALSE) {
      echo json_encode(['status' => '403','message' => 'Permission Denied']);
      return null;
    }

    $id = $this->input->post('id');

    $result = $this->client->delete($id);

    if($result['code'] == 0)
    {
      echo json_encode(['status' => '200', 'message' => 'Cliente eliminado correctamente']);
    }
    else
    {
      echo json_encode(['status' => '500', 'message' => 'Cliente no eliminado, ha ocurrido un error', 'response' => $result]);
    }
  }    
}