<?php
require_once '../controlador/UsuariosController.php';
$controller = new UsuariosController();
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    session_destroy();
    header("Location: ../index.php");
    exit();
}

$id_usu = $_SESSION['usuario']['id_usuario'];
$usuario = $controller->ObtenerUsuarioPorId($id_usu);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $descripcion = trim($_POST['descripcion']);

    if (empty($descripcion)) {
        $error_message = "La descripción no puede estar vacía.";
    } else {
        $resultado = $controller->agregarTarea($usuario["id_usuario"], $descripcion);

        if (!$resultado) {
            $error_message = "Error al agregar la tarea.";
        } else {
            $_SESSION['success_message'] = "Tarea agregada con éxito.";
            header("Location: agregar_tarea.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <h1>Agregar Tarea</h1>
        <!-- Formulario de edición -->
        <form method="POST" action="" class="mt-4">
            <div class="form-group">
                <label for="descripcion">Descripcion:</label>
                <input type="text" class="form-control" id="descripcion" name="descripcion" required>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Agregar Tarea</button>
        </form>

        <a href="usuario_index.php" class="btn btn-secondary">Volver</a>
    </div>
</body>

</html>