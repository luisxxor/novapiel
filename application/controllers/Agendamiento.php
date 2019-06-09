<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Agendamiento extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('Appointment_model', 'appointment');
  }

  public function index() {
    if ($this->session->userdata('is_authenticated') == FALSE) {
      redirect('admin');
    }
    
    $data = array('content' => 'admin/agendamiento/index','title' => 'Novapiel - Agendamientos');
    $this->load->view('admin/template',$data);
  }

  function getAppointments() {

    if ($this->session->userdata('is_authenticated') == FALSE) {
      echo json_encode(['status' => '403','message' => 'Permission Denied']);
      return null;
    }


    $data = $this->appointment->getAppointments();

    foreach ($data as &$appointment) {
      $appointment['ventas'] = boolval($appointment['ventas']);
    }

    header('Content-Type: application/json');
    echo json_encode(['appointments' => $data ]);

  }

  function getSessions() {
    if ($this->session->userdata('is_authenticated') == FALSE) {
      echo json_encode(['status' => '403','message' => 'Permission Denied']);
      return null;
    }

    $id = $this->input->get('id');

    $result = $this->appointment->getSessions($id);
    
    header('Content-Type: application/json');
    echo json_encode(['sessions' => $result]);
  }

  public function updateOrCreateOrderSessions() {
    if ($this->session->userdata('is_authenticated') == FALSE) {
      echo json_encode(['status' => '403','message' => 'Permission Denied']);
      return null;
    }
    
    $data = json_decode($this->input->post('appointment_form'),true);

    if($data['appointment']['id'] == null) {
      $data['appointment']['id'] = $this->appointment->createAppointment($data['appointment']);
    } else {
      $this->appointment->updateAppointment($data['appointment']);
    }

    $createSessions = [];
    $updateSessions = [];

    foreach($data['sessions'] as $session) {
      $session['orden_id'] = $data['appointment']['id'];
      if($session['id'] == NULL) {
        $createSessions[] = $session;
      } else {
        $updateSessions[] = $session;
      }
    }

    $resultCreate = count($createSessions) > 0 ? $this->appointment->createSessions($createSessions) : array('code' => 0);

    $resultUpdate = count($updateSessions) > 0 ? $this->appointment->updateSessions($updateSessions) : array('code' => 0);

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

    $result = $this->appointment->deleteSession($id);

    $result_clean = $this->appointment->deleteOrdersThatDontHaveSessions();

    if($result['code'] == 0 && $result_clean['code'] == 0) {
      echo json_encode(['status' => '200', 'message' => 'Sesion eliminada correctamente','response' => $result]);
    } else {
      echo json_encode(['status' => '500', 'message' => 'Sesion no eliminada, ha ocurrido un error', 'response' => $result]);
    }
  }

  public function deleteAppointment() {
    if ($this->session->userdata('is_authenticated') == FALSE) {
      echo json_encode(['status' => '403','message' => 'Permission Denied']);
      return null;
    }

    $id = $this->input->post('id');

    $result = $this->appointment->deleteAppointment($id);

    if($result['code'] == 0) {
      echo json_encode(['status' => '200', 'message' => 'Orden de venta eliminada correctamente','response' => $result]);
    } else {
      echo json_encode(['status' => '500', 'message' => 'Orden de venta no eliminada, ha ocurrido un error', 'response' => $result]);
    }
  }

  public function getPaidSessions() {
    if ($this->session->userdata('is_authenticated') == FALSE) {
      echo json_encode(['status' => '403','message' => 'Permission Denied']);
      return null;
    }

    $id = $this->input->get('id');

    $result = $this->appointment->getPaidSessions($id);
    
    header('Content-Type: application/json');
    $result->paid_sessions = intval($result->paid_sessions);
    echo json_encode($result);

  }

  public function getAvailable() {
    if ($this->session->userdata('is_authenticated') == FALSE) {
      echo json_encode(['status' => '403','message' => 'Permission Denied']);
      return null;
    }

    $date = $this->input->get('date');
    $order_id = $this->input->get('order_id');
    $result = $this->appointment->getAppointedDates($date,$order_id);

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