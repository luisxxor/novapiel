<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Servicios extends CI_Controller {

  public function botox() {
    $data = array('content' => 'servicio/botox', 'title' => 'Novapiel - Botox');
    $this->load->view('template',$data);
  }

  public function hilos_revitalizantes() {
    $data = array('content' => 'servicio/hilos_revitalizantes', 'title' => 'Novapiel - Hilos Revitalizantes');
    $this->load->view('template',$data);
  }

  public function mesoterapia_facial() {
    $data = array('content' => 'servicio/mesoterapia_facial', 'title' => 'Novapiel - Mesoterapia Facial');
    $this->load->view('template',$data);
  }

  public function prp() {
    $data = array('content' => 'servicio/prp', 'title' => 'Novapiel - Plasma Rico en Plaquetas');
    $this->load->view('template',$data);
  }

  public function peeling_facial() {
    $data = array('content' => 'servicio/peeling_facial', 'title' => 'Novapiel - Peeling Químico');
    $this->load->view('template',$data);
  }

  public function peeling_ultrasonico() {
    $data = array('content' => 'servicio/peeling_ultrasonico', 'title' => 'Novapiel - Peeling Ultrasonico');
    $this->load->view('template',$data);
  }

}
