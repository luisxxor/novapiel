<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ventas extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('Ventas_model', 'ventas');
  }

  public function index() {
    if ($this->session->userdata('is_authenticated') == FALSE) {
      redirect('admin');
    }
    
    $data = array('content' => 'admin/ventas/index','title' => 'Novapiel - Ventas');
    $this->load->view('admin/template',$data);
  }

  public function getClientOrders() {
    if ($this->session->userdata('is_authenticated') == FALSE) {
      echo json_encode(['status' => '403','message' => 'Permission Denied']);
      return null;
    }

    $id = $this->input->get('id');

    $result = $this->ventas->getClientOrders($id);

    echo json_encode(['orders' => $result]);
  }
    
  public function getOrderSessions() {
    if ($this->session->userdata('is_authenticated') == FALSE) {
      echo json_encode(['status' => '403','message' => 'Permission Denied']);
      return null;
    }

    $id = $this->input->get('id');

    $result = $this->ventas->getOrderSessions($id);

    echo json_encode(['sessions' => $result]);
  }
  
  public function create() {
    if ($this->session->userdata('is_authenticated') == FALSE) {
      echo json_encode(['status' => '403','message' => 'Permission Denied']);
      return null;
    }

    $data = json_decode($this->input->post('ventas_form'),true);

    $result = $this->client->form_insert($data);

    if($result['code'] == 0) {
      echo json_encode(['status' => '201', 'message' => 'Venta creada exitosamente']);
    } else {
      echo json_encode(['status' => '500', 'message' => 'Venta no creada, ha ocurrido un error']);
    }
  }

  public function updateOrCreateOrderSessions() {
    if ($this->session->userdata('is_authenticated') == FALSE) {
      echo json_encode(['status' => '403','message' => 'Permission Denied']);
      return null;
    }
    
    $data = json_decode($this->input->post('ventas_form'),true);

    if($data['order']['id'] == null) {
      $data['order']['id'] = $this->ventas->createOrder($data['order']);
    }

    $createSessions = [];
    $updateSessions = [];

    foreach($data['sessions'] as $val) {
      $val['orden_id'] = $data['order']['id'];
      if($val['id'] == NULL) {
        $createSessions[] = $val;
      } else {
        $updateSessions[] = $val;
      }
    }

    $resultCreate = count($createSessions) > 0 ? $this->ventas->createSessions($createSessions) : array('code' => 0);

    $resultUpdate = count($updateSessions) > 0 ? $this->ventas->updateSessions($updateSessions) : array('code' => 0);

    if($resultCreate['code'] == 0 AND $resultUpdate['code'] == 0) {
      echo json_encode(['status' => '200', 'message' => 'Sesiones actualizadas correctamente','responseCreate' => $resultCreate, 'responseUpdate' => $resultUpdate]);
    } else {
      echo json_encode(['status' => '500', 'message' => 'Sesiones no actualizadas, ha ocurrido un error', 'responseCreate' => $resultCreate, 'responseUpdate' => $resultUpdate]);
    }
  }

  public function updateOrCreateClientOrders() {
    if ($this->session->userdata('is_authenticated') == FALSE) {
      echo json_encode(['status' => '403','message' => 'Permission Denied']);
      return null;
    }

    $data = json_decode($this->input->post('ventas_form'),true);
    $createOrders = [];
    $updateOrders = [];

    foreach($data['orders'] as $val) {
      if($val['id'] == NULL) {
        $createOrders[] = $val;
      } else {
        $updateOrders[] = $val;
      }
    }

    $resultCreate = count($createOrders) > 0 ? $this->ventas->createOrders($createOrders) : array('code' => 0);

    $resultUpdate = count($updateOrders) > 0 ? $this->ventas->updateOrders($updateOrders) : array('code' => 0);

    if($resultCreate['code'] == 0 AND $resultUpdate['code'] == 0) {
      echo json_encode(['status' => '200', 'message' => 'Ordenes actualizadas correctamente','responseCreate' => $resultCreate, 'responseUpdate' => $resultUpdate]);
    } else {
      echo json_encode(['status' => '500', 'message' => 'Ordenes no actualizadas, ha ocurrido un error', 'responseCreate' => $resultCreate, 'responseUpdate' => $resultUpdate]);
    }
  }

  public function deleteSession() {
    if ($this->session->userdata('is_authenticated') == FALSE) {
      echo json_encode(['status' => '403','message' => 'Permission Denied']);
      return null;
    }

    $id = $this->input->post('id');

    $result = $this->ventas->deleteSession($id);

    if($result['code'] == 0) {
      echo json_encode(['status' => '200', 'message' => 'Sesion eliminada correctamente','response' => $result]);
    } else {
      echo json_encode(['status' => '500', 'message' => 'Sesion no eliminada, ha ocurrido un error', 'response' => $result]);
    }
  }

  public function deleteOrder() {
    if ($this->session->userdata('is_authenticated') == FALSE) {
      echo json_encode(['status' => '403','message' => 'Permission Denied']);
      return null;
    }

    $id = $this->input->post('id');

    $result = $this->ventas->deleteOrder($id);

    if($result['code'] == 0) {
      echo json_encode(['status' => '200', 'message' => 'Orden de venta eliminada correctamente','response' => $result]);
    } else {
      echo json_encode(['status' => '500', 'message' => 'Orden de venta no eliminada, ha ocurrido un error', 'response' => $result]);
    }
  }

  public function getOrders() {
    if ($this->session->userdata('is_authenticated') == FALSE) {
      echo json_encode(['status' => '403','message' => 'Permission Denied']);
      return null;
    }

    $result = $this->ventas->getOrders();

    echo json_encode(['orders' => $result]);
  }
}