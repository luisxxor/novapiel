<?php
class Sell_model extends CI_Model {

  public function getSessions($id) {
    $result = $this->db->query("SELECT s.id, s.fecha, s.hora, s.realizado, s.pagado, s.fecha_pago, round(a.precio/(SELECT COUNT(id) FROM `sesion` WHERE orden_id = $id), 2) as precio FROM sesion s LEFT JOIN agendamientos a ON a.id = s.orden_id WHERE orden_id = $id AND s.pagado = TRUE");


    return $result->result_array();
  }
  
  public function createSell($data) {

    $this->db->insert('orden',[
      'cliente_id' => $data['cliente_id'],
      'fecha' => $data['fecha'],
      'servicio_id' => $data['servicio_id'],
      'precio' => $data['precio'],
      'descuento' => $data['descuento']
    ]);

    return $this->db->insert_id();
  }

  public function updateSell($data) {
    $this->db->set('cliente_id',$data['cliente_id']);
    $this->db->set('fecha',$data['fecha']);
    $this->db->set('servicio_id',$data['servicio_id']);
    $this->db->set('precio',$data['precio']);
    $this->db->set('descuento',$data['descuento']);
    $this->db->where('id', $data['id']);
    $this->db->update('orden');
  }

  public function createSessions($data) {
    $this->db->insert_batch('sesion',$data);
    return $this->db->error();
  }
  
  public function updateSessions($data) {
    $this->db->update_batch('sesion',$data,'id');
    return $this->db->error();
  }

  public function form_update($data) {
    $this->db->update('clientes',$data,array('id' => $data['id']));
    return $this->db->error();
  }

  public function deleteSession($id) {
    $this->db->where('id', $id);
    $this->db->delete('sesion');
    return $this->db->error();
  }

  public function getSells() {
    $result = $this->db->query("SELECT v.id id, v.fecha fecha, c.nombre nombre, c.id cliente_id, s.title titulo, s.id servicio_id , v.precio, v.descuento, (SELECT count(id) > 0 as exist FROM sesion WHERE pagado = FALSE AND orden_id = v.id) as agendamientos FROM ventas v LEFT JOIN servicios s ON v.servicio_id = s.id LEFT JOIN clientes c ON v.cliente_id = c.id");
    return $result->result_array();
  }

  public function getAllPaidSessions() {
    $result = $this->db->query("SELECT s.id, c.nombre cliente, s.fecha, s.hora, servicio.title servicio, s.realizado, s.fecha_pago, round(o.precio/(SELECT COUNT(id) FROM sesion WHERE orden_id = o.id), 2) as precio FROM sesion s LEFT JOIN orden o ON s.orden_id = o.id LEFT JOIN clientes c ON o.cliente_id = c.id LEFT JOIN servicios servicio ON o.servicio_id = servicio.id WHERE s.pagado = TRUE");
    return $result->result_array();
  }

  public function getUnpaidSessions($id) {
    $this->db->select('COUNT(id) as unpaid_sessions');
    $this->db->from('sesion');
    $this->db->where('orden_id',$id);
    $this->db->where('pagado',FALSE);
    return $this->db->get()->row();
  }

  public function getAppointedDates($date, $order_id) {
    $this->db->select('hora');
    $this->db->from('sesion');
    $this->db->where('fecha',$date);
    $this->db->where('orden_id !=',$order_id);
    return $this->db->get()->result_array();
  }

  public function deleteSell($id) {
    $this->db->where('id', $id);
    $this->db->delete('orden');
    return $this->db->error();
  }

  public function deleteOrdersThatDontHaveSessions() {
    $this->db->query("DELETE o FROM orden o LEFT JOIN sesion s ON o.id = s.orden_id WHERE s.id IS NULL");
    return $this->db->error();
  }
}