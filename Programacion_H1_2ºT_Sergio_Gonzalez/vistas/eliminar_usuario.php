<?php
require_once '../controlador/UsuariosController.php';
$controller = new UsuariosController();

if (isset($_GET['id'])) {
    $id_usuario = $_GET['id'];
    
    if ($controller->eliminarUsuario($id_usuario)) {
        // Redirigir a la lista de usuarios tras la eliminación
        header("Location: lista_usuarios.php");
        exit();
    } else {
        echo "<p>Error al eliminar el usuario.</p>";
    }
} else {
    echo "<p>ID de usuario no proporcionado.</p>";
}
?>

