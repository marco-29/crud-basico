<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios_model extends CI_Model {

    // Obtener todos los usuarios
    public function obtener_todos_usuarios() {
        return $this->db->get('usuarios')->result_array();
    }

    // Obtener un usuario por ID
    public function obtener_usuario_por_id($id) {
        return $this->db->get_where('usuarios', ['id' => $id])->row_array();
    }

    // Crear un usuario
    public function create_user($data) {
        return $this->db->insert('usuarios', $data);
    }

    // Actualizar un usuario
    public function update_user($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('usuarios', $data);
    }

    // Eliminar un usuario
    public function delete_user($id) {
        return $this->db->delete('usuarios', ['id' => $id]);
    }
}
