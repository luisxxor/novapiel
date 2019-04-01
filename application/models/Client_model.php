<?php
class Client_model extends CI_Model {

  public function form_insert($data){
    unset($data['id']);
    $this->db->insert('clientes', $data);
    return $this->db->error();
  }

  public function form_update($data) {
    $this->db->update('clientes',$data,array('id' => $data['id']));
    return $this->db->error();
  }

  public function delete($id) {
    $this->db->where('id', $id);
    $this->db->delete('clientes');
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