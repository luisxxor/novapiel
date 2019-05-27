<?php

  class Contacto_model extends CI_Model {

    function __construct()
    {
      parent::__construct();
    }

    public function getAll() {
      $this->db->select('*');
      $this->db->from('contacto');
      $this->db->order_by('id', 'desc');
      $query = $this->db->get();
      $result = $query->result();
      return $result;
    }

     function create($data) {
      $this->db->insert('contacto', $data);
      return $this->db->affected_rows();
    }

    public function markAsRead($id) {
      $this->db->set('leido',1,FALSE);
      $this->db->where('id',$id);
      $this->db->update('contacto');
      return $this->db->error();
    }

    public function markAsUnread($id) {
      $this->db->set('leido',0,FALSE);
      $this->db->where('id',$id);
      $this->db->update('contacto');
      return $this->db->error();
    }

  }
?>