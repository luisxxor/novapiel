<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Formulario extends CI_Controller {

    public function index()
	{

		$this->load->view('contactanos');
	}

    public function procesarFormularioContacto()
    {
        //$this->input()->post();
        echo "excellent";
    }
}


/*fin archivo*/