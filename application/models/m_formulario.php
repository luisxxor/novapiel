<?php

class M_formulario extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
    function get_todos(){
    	
    	this->load->database();
    	
    	$query = $this->db->get('formulario');
    	return->$query->result();
		
	}
    
}
?>


/*fin archivo */