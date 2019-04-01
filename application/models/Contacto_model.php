<?php

  class Contacto_model extends CI_Model {

    function __construct()
    {
      parent::__construct();
    }

    public function getAll() {
      $this->db->select('*');
      $this->db->from('contacto');
      $query = $this->db->get();
      $result = $query->result();
      return $result;
    }

     function create($data) {
      $this->db->insert('contacto', $data);
      return $this->db->affected_rows();
    }

  }
?>