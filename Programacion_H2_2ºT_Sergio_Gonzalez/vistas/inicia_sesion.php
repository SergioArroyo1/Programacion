<?php

require_once '../controlador/UsuariosController.php';
$controller = new UsuariosController();
$error_message = null;
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") { // Verifica si el formulario fue enviado
    // Se obtienen los datos del formulario
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];

    $usuario = $controller->iniciaSesion($correo, $contraseña);
    if (!$usuario) {
        $error_message = "Correo o contraseña incorrectos";
    } else {
        $_SESSION['usuario'] = $usuario;
        $_SESSION['success_message'] = "Usuario iniciado con éxito";
        header("Location: ../vistas/usuario_index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h1 class="text-center mb-4">Inicia Sesion</S></h1>

                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger text-center" role="alert">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <!-- Mensaje de éxito -->
                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success text-center" role="alert">
                        <?php echo $_SESSION['success_message'];
                        unset($_SESSION['success_message']); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="" class="p-4 border rounded shadow-sm bg-white">

                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo:</label>
                        <input type="email" class="form-control" id="correo" name="correo" required>
                    </div>

                    <div class="mb-3">
                        <label for="contraseña" class="form-label">Contraseña:</label>
                        <input type="password" class="form-control" id="contraseña" name="contraseña" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Iniciar Sesion</button>

                </form>
            </div>
        </div>
    </div>
</body>

</html>