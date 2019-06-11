<?php

  class Config_model extends CI_Model {

    function __construct()
    {
      parent::__construct();
    }

    public function form_update($data) {
      $this->db->update('configuracion',$data,array('id' => 1));
      return $this->db->error();
    }

    public function get() {
      $this->db->select('email,password,smtp,port,receipts,title,message');
      $this->db->from('configuracion');
      $query = $this->db->get();
      $result = $query->result();
      return $result[0];
    }
  }
?>