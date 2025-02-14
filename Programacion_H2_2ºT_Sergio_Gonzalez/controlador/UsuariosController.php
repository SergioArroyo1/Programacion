<?php 
require_once '../modelo/class_usuario.php';

class UsuariosController
{
    private $usuario;

    public function __construct()
    {
        $this->usuario = new Usuario();
    }

    public function agregarUsuario ($nombre, $apellidos , $correo, $contraseña)
    {
        return $this->usuario->agregarUsuario($nombre, $apellidos, $correo, $contraseña);
    }

    public function iniciaSesion($correo, $contraseña)
    {
        return $this->usuario->iniciaSesion($correo, $contraseña);
    }

    public function ObtenerUsuarioPorId ($id_usuario)
    {
        return $this->usuario->obtenerUsuarioPorId($id_usuario);
    }

    public function eliminarUsuario($id_usuario)
    {
        return $this->usuario->eliminarUsuario($id_usuario);
    }

    public function agregarTarea($id_usuario, $descripcion)
    {
        return $this->usuario->agregarTarea($id_usuario, $descripcion);
    }

    public function obtenerTareasPorId($id_usuario)
    {
        return $this->usuario->obtenerTareasPorId($id_usuario);
    }

    public function eliminarTarea($id_tarea) 
    {
        return $this->usuario->eliminarTarea($id_tarea);

    }

    public function actualizarTarea($id_tarea, $estado) 
    {
        return $this->usuario->actualizarTarea($id_tarea, $estado);
    }

}