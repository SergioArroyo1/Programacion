<?php
require_once '../config/class_conexion.php';

class Usuario

{
    private $conexion;

    public function __construct()
    {
        $this->conexion = new Conexion();
    }

    public function agregarUsuario($nombre, $apellidos, $correo, $contraseña)
    {
        $contraseñaSegura = password_hash($contraseña, PASSWORD_DEFAULT);
        $query = "INSERT INTO usuarios (nombre, apellidos, correo, contraseña) VALUES (?, ?, ?, ?)";
        $stmt = $this->conexion->conexion->prepare($query);
        $stmt->bind_param("ssss", $nombre, $apellidos, $correo, $contraseñaSegura);

        if ($stmt->execute()) {
            $stmt->close();
            return true;;
        } else {
            error_log("No se ha podido agregar el usuario: " . $stmt->error);
            $stmt->close();
            return false;

        }
    }
    public function iniciaSesion($correo, $contraseña)
    {
        $query = "SELECT * FROM usuarios WHERE correo = ?";
        $stmt = $this->conexion->conexion->prepare($query);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_assoc();

        if ($usuario && password_verify($contraseña, $usuario['contraseña'])) {

            session_start();
            $_SESSION['id_usuario'] = $usuario['usuario'];
            $stmt->close();
            return $usuario;
        } else {
            return false;
        }
    }
    
    public function ObtenerUsuarioPorId($id_usuario)
    {
        $query = "SELECT * FROM usuarios WHERE id_usuario = ?";
        $stmt = $this->conexion->conexion->prepare($query);
        $stmt->bind_param("i", $id_usuario);
        $stmt-> execute();
        $resultado = $stmt->get_result();
        $stmt-> close();
        return $resultado->fetch_assoc();
    }

    public function eliminarUsuario($id_usuario) {
        $query = "DELETE FROM usuarios WHERE id_usuario = ?";
        $stmt = $this->conexion->conexion->prepare($query);
        $stmt->bind_param("i", $id_usuario);
        if ($stmt->execute()){
            echo "Usuario eliminado con éxito";
        } else {
            echo "Error al eliminar el usuario" . $stmt->error;
        }
        $stmt ->close();
    }

    public function agregarTarea($id_usuario, $descripcion) {
        $query = "INSERT INTO tarea (id_usuario, descripcion) VALUES (?, ?)";
        $stmt = $this->conexion->conexion->prepare($query);
        $stmt->bind_param("is", $id_usuario, $descripcion);
        if ($stmt->execute()){
            echo "Tarea agregada con éxito";
            $stmt ->close();
            return true;
        } else {
            echo "Error al agregar la tarea" . $stmt->error;
            $stmt ->close();
            return false;
        }

    }

    public function obtenerTareasPorId($id_usuario) {
        $query = "SELECT id_tarea, descripcion, estado FROM tarea WHERE id_usuario = ?";
        $stmt = $this->conexion->conexion->prepare($query);
        $stmt->bind_param("i", $id_usuario);
        $stmt-> execute();
        $resultado = $stmt->get_result();
        $resultado_2 = $resultado-> fetch_all(MYSQLI_ASSOC);
        $stmt-> close();
        return $resultado_2;

    }
    public function eliminarTarea($id_tarea) {
        $query = "DELETE FROM tarea WHERE id_tarea = ?";
        $stmt = $this->conexion->conexion->prepare($query);
        $stmt->bind_param("i", $id_tarea);
        if ($stmt->execute()){
            echo "Tarea eliminada: ";
        } else {
            echo "Error al eliminar la tarea" . $stmt->error;
        }
        $stmt ->close();
        return true;
    }

    public function actualizarTarea($id_tarea, $estado) {
        $query = "UPDATE tarea SET estado = ? WHERE id_tarea = ?";
        $stmt = $this->conexion->conexion->prepare($query);
        $stmt->bind_param("si", $estado, $id_tarea);
        $stmt->execute();
        return true;

    }












    }