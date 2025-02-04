<?php
// Importamos los archivos necesarios para la conexión y el modelo de usuario.
require_once '../config/class_conexion.php';
require_once '../modelo/class_usuario.php';

class UsuariosController {
    private $conexion;

    // Constructor de la clase, aquí inicializamos la conexión a la base de datos.
    public function __construct() {
        $this->conexion = new Conexion();
    }

    /**
     * Agrega un nuevo usuario y sus paquetes adicionales.
     */
    public function agregarUsuario($nombre, $apellidos, $correo, $edad, $nombre_plan, $duracion, $paquetes) {
        $usuario = new Usuario($this->conexion);
        $id_usuario = $usuario->agregarUsuario($nombre, $apellidos, $correo, $edad, $nombre_plan, $duracion);

        // Si el usuario se creó correctamente y tiene paquetes seleccionados, los agregamos.
        if ($id_usuario && !empty($paquetes)) {
            foreach ($paquetes as $id_paquete) {
                $usuario->agregarPaqueteUsuario($id_usuario, $id_paquete);
            }
        }

        return $id_usuario; // Devolvemos el ID del usuario creado.
    }

    /**
     * Obtiene todos los paquetes disponibles.
     */
    public function obtenerPaquetes() {
        $usuario = new Usuario($this->conexion);
        return $usuario->obtenerPaquetes();
    }

    /**
     * Obtiene todos los usuarios con sus paquetes asociados.
     */
    public function obtenerUsuarios() {
        $usuario = new Usuario($this->conexion);
        return $usuario->obtenerUsuarios();
    }

    /**
     * Obtiene los datos de un usuario específico según su ID.
     */
    public function obtenerUsuarioPorId($id_usuario) {
        $usuario = new Usuario($this->conexion);
        return $usuario->obtenerUsuarioPorId($id_usuario);
    }

    /**
     * Obtiene los paquetes contratados por un usuario específico.
     */
    public function obtenerPaquetesPorUsuario($id_usuario) {
        $conexion = $this->conexion->conexion; // Accedemos a la conexión MySQLi

        // Preparamos la consulta para obtener los paquetes del usuario
        $query = "SELECT p.nombre_paquete, p.precio 
                  FROM usuarios_paquetes up
                  JOIN paquetes p ON up.id_paquete = p.id_paquete
                  WHERE up.id_usuario = ?";

        $stmt = $conexion->prepare($query);

        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $conexion->error);
        }

        // Asociamos el ID del usuario a la consulta y la ejecutamos.
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        // Guardamos los paquetes en un array.
        $paquetes = [];
        while ($fila = $resultado->fetch_assoc()) {
            $paquetes[] = $fila;
        }

        $stmt->close();
        return $paquetes; // Retornamos los paquetes encontrados.
    }

    /**
     * Actualiza el plan base, la duración y los paquetes de un usuario.
     */
    public function actualizarUsuario($id_usuario, $nombre_plan, $duracion, $paquetes) {
        $usuario = new Usuario($this->conexion);

        // Primero, actualizamos los datos principales del usuario.
        $resultado = $usuario->actualizarUsuario($id_usuario, $nombre_plan, $duracion);

        if ($resultado) {
            // Si la actualización fue exitosa, eliminamos los paquetes anteriores del usuario.
            $usuario->eliminarPaquetesUsuario($id_usuario);

            // Luego, agregamos los nuevos paquetes seleccionados.
            if (!empty($paquetes)) {
                foreach ($paquetes as $id_paquete) {
                    $usuario->agregarPaqueteUsuario($id_usuario, $id_paquete);
                }
            }
        }

        return $resultado; // Devolvemos el resultado de la operación.
    }

    /**
     * Elimina un usuario y sus paquetes asociados.
     */
    public function eliminarUsuario($id_usuario) {
        $usuario = new Usuario($this->conexion);
        return $usuario->eliminarUsuario($id_usuario);
    }
}
?>




