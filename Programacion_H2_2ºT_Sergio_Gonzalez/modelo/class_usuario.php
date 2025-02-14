<?php
// Importamos la conexión a la base de datos
require_once '../config/class_conexion.php';

// Definimos la clase Usuario
class Usuario
{
    private $conexion; // Propiedad para manejar la conexión

    // Constructor: Se ejecuta automáticamente cuando se crea un objeto de esta clase
    public function __construct()
    {
        $this->conexion = new Conexion(); // Se establece la conexión con la base de datos
    }

    // Método para agregar un usuario a la base de datos
    public function agregarUsuario($nombre, $apellidos, $correo, $contraseña)
    {
        $contraseñaSegura = password_hash($contraseña, PASSWORD_DEFAULT); // Se cifra la contraseña
        $query = "INSERT INTO usuarios (nombre, apellidos, correo, contraseña) VALUES (?, ?, ?, ?)";
        $stmt = $this->conexion->conexion->prepare($query); // Se prepara la consulta
        $stmt->bind_param("ssss", $nombre, $apellidos, $correo, $contraseñaSegura); // Se enlazan los parámetros

        if ($stmt->execute()) { // Se ejecuta la consulta
            $stmt->close(); // Se cierra la consulta
            return true;
        } else {
            error_log("No se ha podido agregar el usuario: " . $stmt->error); // Se registra el error
            $stmt->close();
            return false;
        }
    }

    // Método para iniciar sesión
    public function iniciaSesion($correo, $contraseña)
    {
        $query = "SELECT * FROM usuarios WHERE correo = ?";
        $stmt = $this->conexion->conexion->prepare($query);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_assoc();

        // Verifica si el usuario existe y la contraseña es correcta
        if ($usuario && password_verify($contraseña, $usuario['contraseña'])) {
            session_start(); // Inicia la sesión
            $_SESSION['id_usuario'] = $usuario['usuario']; // Guarda el ID del usuario en la sesión
            $stmt->close();
            return $usuario;
        } else {
            return false;
        }
    }
    
    // Método para obtener un usuario por su ID
    public function ObtenerUsuarioPorId($id_usuario)
    {
        $query = "SELECT * FROM usuarios WHERE id_usuario = ?";
        $stmt = $this->conexion->conexion->prepare($query);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();
        return $resultado->fetch_assoc();
    }

    // Método para eliminar un usuario por su ID
    public function eliminarUsuario($id_usuario) {
        $query = "DELETE FROM usuarios WHERE id_usuario = ?";
        $stmt = $this->conexion->conexion->prepare($query);
        $stmt->bind_param("i", $id_usuario);
        if ($stmt->execute()){
            echo "Usuario eliminado con éxito";
        } else {
            echo "Error al eliminar el usuario" . $stmt->error;
        }
        $stmt->close();
    }

    // Método para agregar una tarea a un usuario
    public function agregarTarea($id_usuario, $descripcion) {
        $query = "INSERT INTO tarea (id_usuario, descripcion) VALUES (?, ?)";
        $stmt = $this->conexion->conexion->prepare($query);
        $stmt->bind_param("is", $id_usuario, $descripcion);
        if ($stmt->execute()){
            echo "Tarea agregada con éxito";
            $stmt->close();
            return true;
        } else {
            echo "Error al agregar la tarea" . $stmt->error;
            $stmt->close();
            return false;
        }
    }

    // Método para obtener todas las tareas de un usuario
    public function obtenerTareasPorId($id_usuario) {
        $query = "SELECT id_tarea, descripcion, estado FROM tarea WHERE id_usuario = ?";
        $stmt = $this->conexion->conexion->prepare($query);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $resultado_2 = $resultado->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $resultado_2;
    }

    // Método para eliminar una tarea por su ID
    public function eliminarTarea($id_tarea) {
        $query = "DELETE FROM tarea WHERE id_tarea = ?";
        $stmt = $this->conexion->conexion->prepare($query);
        $stmt->bind_param("i", $id_tarea);
        if ($stmt->execute()){
            echo "Tarea eliminada";
        } else {
            echo "Error al eliminar la tarea" . $stmt->error;
        }
        $stmt->close();
        return true;
    }

    // Método para actualizar el estado de una tarea
    public function actualizarTarea($id_tarea, $estado) {
        $query = "UPDATE tarea SET estado = ? WHERE id_tarea = ?";
        $stmt = $this->conexion->conexion->prepare($query);
        $stmt->bind_param("si", $estado, $id_tarea);
        $stmt->execute();
        return true;
    }
}
