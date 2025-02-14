<?php
// Incluimos el controlador para manejar las tareas y usuarios
require_once '../controlador/UsuariosController.php';

// Creamos una instancia del controlador
$controller = new UsuariosController();

// Iniciamos la sesión para saber si el usuario está logueado
session_start();

// Verificamos si el usuario está logueado, si no, redirigimos a la página de inicio
if (!isset($_SESSION['usuario'])) {
    session_destroy(); // Terminamos la sesión si no está logueado
    header("Location: ../index.php"); // Redirigimos al login
    exit(); // Terminamos el script aquí
}

// Si la acción es 'completar' y hay una tarea con ese ID, marcamos la tarea como completada
if (isset($_GET['action']) && $_GET['action'] == 'completar' && isset($_GET['id_tarea'])) {
    $id_tarea = intval($_GET['id_tarea']); // Obtenemos el ID de la tarea

    // Llamamos al controlador para actualizar el estado de la tarea
    if ($controller->actualizarTarea($id_tarea, 'Completado')) {
        header("Location: ver_tarea.php"); // Si todo sale bien, redirigimos para ver la lista actualizada
        exit();
    } else {
        $error_message = "Error al marcar la tarea como completada."; // Si hay error, mostramos mensaje
    }
}

// Procesamos la acción de eliminar la tarea
if (isset($_GET['action']) && $_GET['action'] == 'eliminar' && isset($_GET['id_tarea'])) {
    $id_tarea = intval($_GET['id_tarea']); // Obtenemos el ID de la tarea

    // Llamamos al controlador para eliminar la tarea
    if ($controller->eliminarTarea($id_tarea)) {
        header("Location: ver_tarea.php"); // Si todo sale bien, redirigimos
        exit();
    } else {
        $error_message = "Error al eliminar la tarea."; // Si hay error, mostramos mensaje
    }
}

// Obtenemos el ID del usuario desde la sesión
$id_user = $_SESSION['usuario']['id_usuario'];

// Obtenemos la información del usuario
$usuario = $controller->obtenerUsuarioporID($id_user);

// Si no se encuentra el usuario, mostramos un mensaje y terminamos el script
if (!$usuario) {
    echo "Error: Usuario no encontrado.";
    exit();
}

// Obtenemos las tareas del usuario
$tabla = $controller->obtenerTareasPorId($usuario["id_usuario"]);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista Tareas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-4">
        <!-- Título principal -->
        <h1 class="mb-3">Tus Tareas:</h1>

        <!-- Tabla donde mostramos las tareas -->
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Recorremos cada tarea y la mostramos en una fila -->
                <?php foreach ($tabla as $tarea): ?>
                    <tr>
                        <td><?= htmlspecialchars($tarea['descripcion']) ?></td> <!-- Descripción de la tarea -->
                        <td><?= htmlspecialchars($tarea['estado']) ?></td> <!-- Estado de la tarea -->
                        <td>
                            <!-- Si la tarea no está completada, mostramos el botón de completar -->
                            <?php if ($tarea['estado'] !== 'Completado') : ?>
                                <a href="?action=completar&id_tarea=<?= htmlspecialchars($tarea['id_tarea']) ?>" class="btn btn-info btn-sm me-2">Completada</a>
                            <?php endif; ?>

                            <!-- Botón para eliminar la tarea -->
                            <a href="?action=eliminar&id_tarea=<?= htmlspecialchars($tarea['id_tarea']) ?>" class="btn btn-danger btn-sm">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Botón para volver a la página anterior -->
        <a href="usuario_index.php" class="btn btn-secondary">Volver</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>