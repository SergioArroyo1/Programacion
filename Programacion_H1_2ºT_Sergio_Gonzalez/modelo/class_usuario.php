<?php
// Importamos la conexión a la base de datos
require_once '../config/class_conexion.php';

class Usuario {
    private $conexion;

    // Constructor que recibe la conexión y la asigna a la variable interna
    public function __construct($conexion) {
        $this->conexion = $conexion->conexion;
    }

    /**
     * Agrega un nuevo usuario a la base de datos y devuelve su ID.
     */
    public function agregarUsuario($nombre, $apellidos, $correo, $edad, $nombre_plan, $duracion) {
        // Preparamos la consulta SQL para insertar un usuario
        $query = "INSERT INTO usuarios (nombre, apellidos, correo, edad, nombre_plan, duracion) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("sssiss", $nombre, $apellidos, $correo, $edad, $nombre_plan, $duracion);
        
        // Si la inserción fue exitosa, devolvemos el ID del usuario creado
        if ($stmt->execute()) {
            return $this->conexion->insert_id;
        } else {
            return false; // En caso de error, devolvemos false
        }
    }

    /**
     * Obtiene la lista de todos los usuarios con sus paquetes asignados.
     */
    public function obtenerUsuarios() {
        // Consulta que obtiene los datos de los usuarios junto con los paquetes contratados
        $query = "SELECT u.id_usuario, u.nombre, u.apellidos, u.correo, u.edad, u.nombre_plan, u.duracion,
                         GROUP_CONCAT(p.nombre_paquete SEPARATOR ', ') AS paquetes
                  FROM usuarios u
                  LEFT JOIN usuarios_paquetes up ON u.id_usuario = up.id_usuario
                  LEFT JOIN paquetes p ON up.id_paquete = p.id_paquete
                  GROUP BY u.id_usuario";
        $result = $this->conexion->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtiene los datos de un usuario específico por su ID.
     */
    public function obtenerUsuarioPorId($id_usuario) {
        // Consulta para obtener la información del usuario y sus paquetes en una sola consulta
        $query = "SELECT u.*, 
                         GROUP_CONCAT(p.id_paquete) AS paquetes 
                  FROM usuarios u
                  LEFT JOIN usuarios_paquetes up ON u.id_usuario = up.id_usuario
                  LEFT JOIN paquetes p ON up.id_paquete = p.id_paquete
                  WHERE u.id_usuario = ?
                  GROUP BY u.id_usuario";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Actualiza el plan base y la duración de un usuario.
     */
    public function actualizarUsuario($id_usuario, $nombre_plan, $duracion) {
        // Preparamos la consulta para actualizar el plan y la duración de un usuario
        $query = "UPDATE usuarios SET nombre_plan = ?, duracion = ? WHERE id_usuario = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("ssi", $nombre_plan, $duracion, $id_usuario);
        return $stmt->execute(); // Retornamos true si la actualización fue exitosa
    }

    /**
     * Elimina todos los paquetes asociados a un usuario.
     */
    public function eliminarPaquetesUsuario($id_usuario) {
        // Eliminamos los paquetes contratados por el usuario
        $query = "DELETE FROM usuarios_paquetes WHERE id_usuario = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $id_usuario);
        return $stmt->execute();
    }

    /**
     * Agrega un paquete a un usuario.
     */
    public function agregarPaqueteUsuario($id_usuario, $id_paquete) {
        // Inserta un nuevo paquete para un usuario específico
        $query = "INSERT INTO usuarios_paquetes (id_usuario, id_paquete) VALUES (?, ?)";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("ii", $id_usuario, $id_paquete);
        return $stmt->execute();
    }

    /**
     * Obtiene la lista de paquetes disponibles.
     */
    public function obtenerPaquetes() {
        // Consulta para obtener todos los paquetes disponibles en la base de datos
        $query = "SELECT * FROM paquetes";
        $result = $this->conexion->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Elimina un usuario y sus paquetes asociados.
     */
    public function eliminarUsuario($id_usuario) {
        // Primero eliminamos los paquetes del usuario antes de eliminarlo de la base de datos
        $this->eliminarPaquetesUsuario($id_usuario);
        
        // Luego eliminamos al usuario de la tabla principal
        $query = "DELETE FROM usuarios WHERE id_usuario = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $id_usuario);
        return $stmt->execute();
    }
}
?>





