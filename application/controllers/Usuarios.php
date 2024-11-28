<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Usuarios_model');
        $this->load->helper(['url', 'form']);
        $this->load->library('form_validation');
    }

    // Obtener todos los usuarios
    public function index() {
        $users = $this->Usuarios_model->obtener_todos_usuarios();
        echo json_encode($users);
    }

    // Obtener un usuario por ID
    public function show($id) {
        $user = $this->Usuarios_model->obtener_usuario_por_id($id);
        if ($user) {
            echo json_encode($user);
        } else {
            echo json_encode(['error' => 'Usuario no encontrado']);
        }
    }

    // Crear un usuario
    public function store() {
        $this->form_validation->set_rules('nombre', 'Nombre', 'required|max_length[255]');
        $this->form_validation->set_rules('email', 'Correo', 'required|valid_email|is_unique[usuarios.email]');
        $this->form_validation->set_rules('password', 'Contraseña', 'required|min_length[6]');

        if ($this->form_validation->run() === FALSE) {
            echo json_encode(['error' => validation_errors()]);
        } else {
            $data = [
                'nombre' => $this->input->post('nombre'),
                'email' => $this->input->post('email'),
                'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT)
            ];

            if ($this->Usuarios_model->create_user($data)) {
                echo json_encode(['message' => 'Usuario creado exitosamente']);
            } else {
                echo json_encode(['error' => 'Error al crear usuario']);
            }
        }
    }

    // Actualizar un usuario
    public function update($id) {
        $user = $this->Usuarios_model->obtener_usuario_por_id($id);
        if (!$user) {
            echo json_encode(['error' => 'Usuario no encontrado']);
            return;
        }

        $this->form_validation->set_rules('nombre', 'Nombre', 'max_length[255]');
        $this->form_validation->set_rules('email', 'Correo', 'valid_email|is_unique[usuarios.email]');
        $this->form_validation->set_rules('password', 'Contraseña', 'min_length[6]');

        if ($this->form_validation->run() === FALSE) {
            echo json_encode(['error' => validation_errors()]);
        } else {
            $data = [];
            if ($this->input->post('nombre')) $data['nombre'] = $this->input->post('nombre');
            if ($this->input->post('email')) $data['email'] = $this->input->post('email');
            if ($this->input->post('password')) $data['password'] = password_hash($this->input->post('password'), PASSWORD_BCRYPT);

            if ($this->Usuarios_model->update_user($id, $data)) {
                echo json_encode(['message' => 'Usuario actualizado exitosamente']);
            } else {
                echo json_encode(['error' => 'Error al actualizar usuario']);
            }
        }
    }

    // Eliminar un usuario
    public function delete($id) {
        if ($this->Usuarios_model->delete_user($id)) {
            echo json_encode(['message' => 'Usuario eliminado exitosamente']);
        } else {
            echo json_encode(['error' => 'Error al eliminar usuario']);
        }
    }
}
