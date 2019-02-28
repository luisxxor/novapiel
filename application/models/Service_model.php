<?php
class Service_model extends CI_Model {

  public function form_insert($data){
    $this->db->insert('servicios', array(
      'title' => $data['title'],
      'price' => $data['price'],
      'description' => $data['description']
    ));
    return $this->db->error();
  }

  public function form_update($data) {
    $this->db->update('servicios',$data,array('id' => $data['id']));
    return $this->db->error();
  }

  public function delete($id) {
    $this->db->where('id', $id);
    $this->db->delete('servicios');
    return $this->db->error();
  }

  public function getAll() {
    $this->db->select('id,title,price,description');
    $this->db->from('servicios');
    $query = $this->db->get();
    $result = $query->result();
    return $result;
  }
}