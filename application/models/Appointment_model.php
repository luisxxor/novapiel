<?php
class Appointment_model extends CI_Model {

  public function getAppointments() {
    $result = $this->db->query("SELECT a.id id, a.fecha fecha, c.nombre nombre, c.id cliente_id, s.title titulo, s.id servicio_id , a.precio, a.descuento, (SELECT count(id) > 1 as exist FROM sesion WHERE pagado = TRUE AND orden_id = a.id) as ventas FROM agendamientos a LEFT JOIN servicios s ON a.servicio_id = s.id LEFT JOIN clientes c ON a.cliente_id = c.id");
    return $result->result_array();
  }

  public function getSessions($id) {
    $result = $this->db->query("SELECT s.id, s.fecha, s.hora, s.realizado, round(a.precio/(SELECT COUNT(id) FROM `sesion` WHERE orden_id = 3), 2) as precio FROM sesion s LEFT JOIN agendamientos a ON a.id = s.orden_id WHERE orden_id = $id AND s.pagado = FALSE");


    return $result->result_array();
  }

  public function createSessions($data) {
    $this->db->insert_batch('sesion',$data);
    return $this->db->error();
  }
  
  public function updateSessions($data) {
    $this->db->update_batch('sesion',$data,'id');
    return $this->db->error();
  }

  public function createAppointment($data) {

    $this->db->insert('orden',[
      'cliente_id' => $data['cliente_id'],
      'fecha' => $data['fecha'],
      'servicio_id' => $data['servicio_id'],
      'precio' => $data['precio'],
      'descuento' => $data['descuento']
    ]);

    return $this->db->insert_id();
  }

  public function updateAppointment($data) {
    $this->db->set('cliente_id',$data['cliente_id']);
    $this->db->set('fecha',$data['fecha']);
    $this->db->set('servicio_id',$data['servicio_id']);
    $this->db->set('precio',$data['precio']);
    $this->db->set('descuento',$data['descuento']);
    $this->db->where('id', $data['id']);
    $this->db->update('orden');
  }

  public function getPaidSessions($id) {
    $this->db->select('COUNT(id) as paid_sessions');
    $this->db->from('sesion');
    $this->db->where('orden_id',$id);
    $this->db->where('pagado',TRUE);
    return $this->db->get()->row();
  }

  public function getAppointedDates($date, $order_id) {
    $this->db->select('hora');
    $this->db->from('sesion');
    $this->db->where('fecha',$date);
    $this->db->where('orden_id !=',$order_id);
    return $this->db->get()->result_array();
  }
}