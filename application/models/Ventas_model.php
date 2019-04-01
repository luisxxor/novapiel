<?php
class Ventas_model extends CI_Model {

  public function getClientOrders($id) {
    $this->db->select('id, fecha, cliente_id');
    $this->db->from('orden');
    $this->db->where('cliente_id',$id);

    return $this->db->get()->result();
  }

  public function getOrderSessions($id) {
    $this->db->select('ses.id, ses.servicio_id, ses.orden_id, ses.precio, ses.fecha, ses.status');
    $this->db->from('sesion ses');
    $this->db->join('servicios ser','ses.servicio_id = ser.id','left');
    $this->db->where('orden_id',$id);

    return $this->db->get()->result();
  }
  
  public function createOrders($data) {
    $this->db->insert_batch('orden',$data);
    return $this->db->error();
  }
  
  public function updateOrders($data) {
    $this->db->update_batch('orden',$data,'id');
    return $this->db->error();
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

  public function deleteOrder($id) {
    $this->db->where('id', $id);
    $this->db->delete('orden');
    return $this->db->error();
  }

  public function getAll() {
    $this->db->select('*');
    $this->db->from('clientes');
    $query = $this->db->get();
    $result = $query->result();
    return $result;
  }
}