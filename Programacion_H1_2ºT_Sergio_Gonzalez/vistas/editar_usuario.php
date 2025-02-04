<?php
require_once '../controlador/UsuariosController.php'; // Incluye el controlador de usuarios

$controller = new UsuariosController(); // Instancia del controlador
$error_message = '';
$success_message = '';

// Verificar si se recibió un ID válido por GET
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de usuario no proporcionado."); // Si no hay ID, se detiene la ejecución
}

$id_usuario = $_GET['id'];

// Obtener los datos del usuario desde la base de datos
$usuario = $controller->obtenerUsuarioPorId($id_usuario);
if (!$usuario) {
    die("Usuario no encontrado."); // Si el usuario no existe, se detiene la ejecución
}

// Obtener la lista de paquetes disponibles
$paquetes_disponibles = $controller->obtenerPaquetes();

// Convierte la cadena de paquetes del usuario en un array para poder manipularla
$paquetes_usuario = $usuario['paquetes'] ? explode(',', $usuario['paquetes']) : [];

// Si el formulario fue enviado, procesar la actualización
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_plan = $_POST['nombre_plan']; // Plan elegido por el usuario
    $duracion = $_POST['duracion']; // Duración de la suscripción
    $paquetes = isset($_POST['paquetes']) ? $_POST['paquetes'] : []; // Paquetes seleccionados

    // Validaciones de restricciones
    if ($usuario['edad'] < 18) { // Si el usuario es menor de edad
        foreach ($paquetes as $id_paquete) {
            // Buscar la información del paquete seleccionado
            $paquete_info = array_filter($paquetes_disponibles, function ($p) use ($id_paquete) {
                return $p['id_paquete'] == $id_paquete;
            });
            $paquete_info = reset($paquete_info);
            
            // Si el paquete no es "Infantil", mostrar error
            if ($paquete_info['nombre_paquete'] !== 'Infantil') {
                $error_message = "Los menores de 18 años solo pueden contratar el Pack Infantil.";
                break;
            }
        }
    } elseif ($nombre_plan == 'Basico' && count($paquetes) > 1) { 
        // Si el usuario tiene plan Básico, solo puede elegir un paquete adicional
        $error_message = "Los usuarios con Plan Básico solo pueden seleccionar un paquete adicional.";
    } elseif (in_array(1, $paquetes) && $duracion !== 'Anual') { 
        // Si el usuario selecciona el Pack Deporte, la suscripción debe ser Anual
        $error_message = "El Pack Deporte solo puede ser contratado si la duración de la suscripción es Anual.";
    }

    // Si no hay errores, proceder con la actualización del usuario
    if (!$error_message) {
        $resultado = $controller->actualizarUsuario($id_usuario, $nombre_plan, $duracion, $paquetes);

        if ($resultado) {
            $success_message = "Usuario actualizado con éxito.";
            header("Location: lista_usuarios.php"); // Redirigir a la lista de usuarios
            exit();
        } else {
            $error_message = "Error al actualizar el usuario."; // Si hubo error en la actualización
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Editar Usuario</h1>

        <!-- Mostrar mensaje de error si existe -->
        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <!-- Mostrar mensaje de éxito si la actualización fue correcta -->
        <?php if ($success_message): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Nombre:</label>
                <input type="text" class="form-control" value="<?php echo $usuario['nombre']; ?>" disabled> 
                <!-- Campo de solo lectura -->
            </div>
            
            <div class="mb-3">
                <label class="form-label">Apellidos:</label>
                <input type="text" class="form-control" value="<?php echo $usuario['apellidos']; ?>" disabled>
            </div>

            <div class="mb-3">
                <label class="form-label">Correo:</label>
                <input type="email" class="form-control" value="<?php echo $usuario['correo']; ?>" disabled>
            </div>

            <div class="mb-3">
                <label class="form-label">Plan Base:</label>
                <select class="form-control" name="nombre_plan" required>
                    <option value="Basico" <?php echo ($usuario['nombre_plan'] == 'Basico') ? 'selected' : ''; ?>>Básico</option>
                    <option value="Estandar" <?php echo ($usuario['nombre_plan'] == 'Estandar') ? 'selected' : ''; ?>>Estándar</option>
                    <option value="Premium" <?php echo ($usuario['nombre_plan'] == 'Premium') ? 'selected' : ''; ?>>Premium</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Duración:</label>
                <select class="form-control" name="duracion" required>
                    <option value="Mensual" <?php echo ($usuario['duracion'] == 'Mensual') ? 'selected' : ''; ?>>Mensual</option>
                    <option value="Anual" <?php echo ($usuario['duracion'] == 'Anual') ? 'selected' : ''; ?>>Anual</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Paquetes Adicionales:</label>
                <?php foreach ($paquetes_disponibles as $paquete): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="paquetes[]" value="<?php echo $paquete['id_paquete']; ?>"
                            <?php echo in_array($paquete['id_paquete'], $paquetes_usuario) ? 'checked' : ''; ?>
                            <?php 
                                // Deshabilitar paquetes no permitidos para menores de 18 años
                                if ($usuario['edad'] < 18 && $paquete['nombre_paquete'] !== 'Infantil') {
                                    echo ' disabled';
                                }
                            ?>>
                        <label class="form-check-label">
                            <?php echo $paquete['nombre_paquete'] . " - $" . $paquete['precio']; ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>

            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="lista_usuarios.php" class="btn btn-secondary">Volver</a>
        </form>
    </div>
</body>
</html>





