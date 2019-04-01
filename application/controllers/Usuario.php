<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('User_model', 'user');
  }

  public function index()
  {
    if ($this->session->userdata('is_authenticated') == FALSE) {
      redirect('admin');
    }
    $data = array('content' => 'admin/usuario/index','title' => 'Novapiel - Usuarios');
    $this->load->view('admin/template',$data);
  }

  function getUsers() {
    $data['users'] = $this->user->getAll();
    header('Content-Type: application/json');
    echo json_encode(['users' => $data['users']]);

  }
  
  public function create() {
    if ($this->session->userdata('is_authenticated') == FALSE) {
      echo json_encode(['status' => '403','message' => 'Permission Denied']);
      return null;
    }

    $data = json_decode($this->input->post('user_form'),true);

    $is_available = $this->user->usernameIsAvailable(null,$data['username']);
    
    if($is_available)
    {
      $result = $this->user->form_insert($data);

      if($result['code'] == 0)
      {
        echo json_encode(['status' => '201', 'message' => 'Usuario creado exitosamente']);
      }
      else
      {
        echo json_encode(['status' => '500', 'message' => 'Usuario no creado, ha ocurrido un error']);
      }
    }
    else
    {
      echo json_encode(['status' => '422', 'message' => 'El nombre de usuario seleccionado ya existe en la base de datos']);
    }
  }

  public function update() {
    if ($this->session->userdata('is_authenticated') == FALSE) {
      echo json_encode(['status' => '403','message' => 'Permission Denied']);
      return null;
    }

    $data = json_decode($this->input->post('user_form'),true);

    $update_data['id'] = $data['id'];
    $update_data['username'] = $data['username'];

    if(trim($data['password']) != '')
    {
      $update_data['password'] = $data['password'];
    }

    $result = $this->user->form_update($update_data);

    if($result['code'] == 0)
    {
      echo json_encode(['status' => '200', 'message' => 'Usuario actualizado exitosamente']);
    }
    else
    {
      echo json_encode(['status' => '500', 'message' => 'Usuario no actualizado, ha ocurrido un error', 'response' => $result]);
    }
  }

  public function delete() {
    if ($this->session->userdata('is_authenticated') == FALSE) {
      echo json_encode(['status' => '403','message' => 'Permission Denied']);
      return null;
    }

    $id = $this->input->post('id');

    $result = $this->user->delete($id);

    if($result['code'] == 0)
    {
      echo json_encode(['status' => '200', 'message' => 'Usuario eliminado correctamente']);
    }
    else
    {
      echo json_encode(['status' => '500', 'message' => 'Usuario no eliminado, ha ocurrido un error', 'response' => $result]);
    }
  }

  public function usernameIsAvailable() {
    if ($this->session->userdata('is_authenticated') == FALSE) {
      echo json_encode(['status' => '403','message' => 'Permission Denied']);
      return null;
    }

    $data = json_decode($this->input->post('usernameTest'),true);

    $result = $this->user->usernameIsAvailable($data['id'],$data['username']);

    echo json_encode(['response' => $result]);
    }
    
}