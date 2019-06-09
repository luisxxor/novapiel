<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ventas extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('Sell_model', 'sell');
  }

  public function index() {
    if ($this->session->userdata('is_authenticated') == FALSE) {
      redirect('admin');
    }
    
    $data = array('content' => 'admin/venta/index','title' => 'Novapiel - Ventas');
    $this->load->view('admin/template',$data);
  }
    
  public function getSessions() {
    if ($this->session->userdata('is_authenticated') == FALSE) {
      echo json_encode(['status' => '403','message' => 'Permission Denied']);
      return null;
    }

    $id = $this->input->get('id');

    $result = $this->sell->getSessions($id);

    header('Content-Type: application/json');
    echo json_encode(['sessions' => $result]);
  }

  public function updateOrCreateOrderSessions() {
    if ($this->session->userdata('is_authenticated') == FALSE) {
      echo json_encode(['status' => '403','message' => 'Permission Denied']);
      return null;
    }
    
    $data = json_decode($this->input->post('sell_form'),true);

    if($data['sell']['id'] == null) {
      $data['sell']['id'] = $this->sell->createSell($data['sell']);
    } else {
      $this->sell->updateSell($data['sell']);
    }

    $createSessions = [];
    $updateSessions = [];

    foreach($data['sessions'] as $val) {
      $val['orden_id'] = $data['sell']['id'];
      if($val['id'] == NULL) {
        $createSessions[] = $val;
      } else {
        $updateSessions[] = $val;
      }
    }

    $resultCreate = count($createSessions) > 0 ? $this->sell->createSessions($createSessions) : array('code' => 0);

    $resultUpdate = count($updateSessions) > 0 ? $this->sell->updateSessions($updateSessions) : array('code' => 0);

    if($resultCreate['code'] == 0 AND $resultUpdate['code'] == 0) {
      echo json_encode(['status' => '200', 'message' => 'Sesiones actualizadas correctamente','responseCreate' => $resultCreate, 'responseUpdate' => $resultUpdate]);
    } else {
      echo json_encode(['status' => '500', 'message' => 'Sesiones no actualizadas, ha ocurrido un error', 'responseCreate' => $resultCreate, 'responseUpdate' => $resultUpdate]);
    }
  }

  public function deleteSession() {
    if ($this->session->userdata('is_authenticated') == FALSE) {
      echo json_encode(['status' => '403','message' => 'Permission Denied']);
      return null;
    }

    $id = $this->input->post('id');

    $result = $this->sell->deleteSession($id);

    if($result['code'] == 0) {
      echo json_encode(['status' => '200', 'message' => 'Sesion eliminada correctamente','response' => $result]);
    } else {
      echo json_encode(['status' => '500', 'message' => 'Sesion no eliminada, ha ocurrido un error', 'response' => $result]);
    }
  }

  public function deleteSell() {
    if ($this->session->userdata('is_authenticated') == FALSE) {
      echo json_encode(['status' => '403','message' => 'Permission Denied']);
      return null;
    }

    $id = $this->input->post('id');

    $result = $this->sell->deleteSell($id);

    if($result['code'] == 0) {
      echo json_encode(['status' => '200', 'message' => 'Orden de venta eliminada correctamente','response' => $result]);
    } else {
      echo json_encode(['status' => '500', 'message' => 'Orden de venta no eliminada, ha ocurrido un error', 'response' => $result]);
    }
  }

  public function getSells() {
    if ($this->session->userdata('is_authenticated') == FALSE) {
      echo json_encode(['status' => '403','message' => 'Permission Denied']);
      return null;
    }

    $data = $this->sell->getSells();


    foreach ($data as &$sell) {
      $sell['agendamientos'] = boolval($sell['agendamientos']);
    }

    echo json_encode(['sells' => $data]);
  }

  public function getAllPaidSessions() {
    if ($this->session->userdata('is_authenticated') == FALSE) {
      echo json_encode(['status' => '403','message' => 'Permission Denied']);
      return null;
    }

    $result = $this->sell->getAllPaidSessions();
    
    header('Content-Type: application/json');
    echo json_encode($result);
  }

  public function getUnpaidSessions() {
    if ($this->session->userdata('is_authenticated') == FALSE) {
      echo json_encode(['status' => '403','message' => 'Permission Denied']);
      return null;
    }

    $id = $this->input->get('id');

    $result = $this->sell->getUnpaidSessions($id);
    
    header('Content-Type: application/json');
    $result->unpaid_sessions = intval($result->unpaid_sessions);
    echo json_encode($result);

  }

  public function getAvailable() {
    if ($this->session->userdata('is_authenticated') == FALSE) {
      echo json_encode(['status' => '403','message' => 'Permission Denied']);
      return null;
    }

    $date = $this->input->get('date');
    $order_id = $this->input->get('order_id');
    $result = $this->sell->getAppointedDates($date,$order_id);

    $formattedResult = [];
    
    foreach($result as $r) {
      $formattedResult[] = $r['hora'];
    }

    $hours = [];
    for($i = 0; $i < 24; $i++) {
      if(in_array($i, $formattedResult)) {
        continue;
      }

      $text = $i < 10 ? '0'.$i.':00' : $i.':00';
      $hours[] = ['value' => $i, 'text' => $text];
    };

    header('Content-Type: application/json');
    echo json_encode($hours);
  }  
}