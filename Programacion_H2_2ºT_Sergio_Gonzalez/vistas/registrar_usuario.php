<?php

// Se importa el controlador de usuarios para manejar las operaciones relacionadas con los usuarios
require_once '../controlador/UsuariosController.php';
$controller = new UsuariosController();
$error_message = ''; // Variable para almacenar mensajes de error


if ($_SERVER["REQUEST_METHOD"] == "POST") { // Verifica si el formulario fue enviado
    // Se obtienen los datos del formulario
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];

    $usuario = $controller->agregarUsuario($nombre, $apellidos, $correo, $contraseña);
    if (!$usuario) {
        $error_message = 'El usuario no se ha agregado: ';
    } else {
        header("Location: ../index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Agregar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h1 class="text-center mb-4">Registrar Usuario</h1>

                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger text-center" role="alert">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success text-center" role="alert">
                        <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="" class="p-4 border rounded shadow-sm bg-white">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>

                    <div class="mb-3">
                        <label for="apellidos" class="form-label">Apellido:</label>
                        <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                    </div>

                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo:</label>
                        <input type="email" class="form-control" id="correo" name="correo" required>
                    </div>

                    <div class="mb-3">
                        <label for="contraseña" class="form-label">Contraseña:</label>
                        <input type="password" class="form-control" id="contraseña" name="contraseña" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Registrate</button>

                    <a href="../index.php" class="btn btn-secondary w-100 mt-2">Volver a la página principal</a>
                </form>
            </div>
        </div>
    </div>
</body>

</html>