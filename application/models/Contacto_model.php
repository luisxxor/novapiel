<?php

class Contacto_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function get_todos(){

    	$this->load->database();

    	$query = $this->db->get('contacto');
    	return $query->result();
	}

	function create($data) {
      $this->db->insert('contacto', $data);
      return $this->db->affected_rows();
    }

}
?>