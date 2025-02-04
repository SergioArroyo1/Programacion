<?php
// Incluimos el controlador que maneja los usuarios
require_once '../controlador/UsuariosController.php';

// Creamos una instancia del controlador
$controller = new UsuariosController();

// Obtenemos la lista de usuarios desde la base de datos
$usuarios = $controller->obtenerUsuarios();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Usuarios</title>
    <!-- Importamos Bootstrap para darle estilo a la tabla y botones -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Lista de Usuarios</h1>

        <!-- Tabla para mostrar la información de los usuarios -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Correo</th>
                    <th>Edad</th>
                    <th>Plan</th>
                    <th>Duración</th>
                    <th>Paquetes</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?> 
                    <tr>
                        <!-- Mostramos la información del usuario en cada celda -->
                        <td><?php echo $usuario['id_usuario']; ?></td>
                        <td><?php echo $usuario['nombre']; ?></td>
                        <td><?php echo $usuario['apellidos']; ?></td>
                        <td><?php echo $usuario['correo']; ?></td>
                        <td><?php echo $usuario['edad']; ?></td>
                        <td><?php echo $usuario['nombre_plan']; ?></td>
                        <td><?php echo $usuario['duracion']; ?></td>
                        <!-- Si el usuario tiene paquetes, los mostramos, si no, mostramos "Ninguno" -->
                        <td><?php echo !empty($usuario['paquetes']) ? $usuario['paquetes'] : 'Ninguno'; ?></td>
                        <td>
                            <!-- Botón para editar la información del usuario -->
                            <a href="editar_usuario.php?id=<?php echo $usuario['id_usuario']; ?>" class="btn btn-warning btn-sm">Editar</a>
                            <!-- Botón para eliminar al usuario -->
                            <a href="eliminar_usuario.php?id=<?php echo $usuario['id_usuario']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                            <!-- Botón para ver el costo desglosado del usuario -->
                            <a href="costo_desglosado.php?id=<?php echo $usuario['id_usuario']; ?>" class="btn btn-info btn-sm">Coste Desglosado</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <!-- Botón para agregar un nuevo usuario -->
        <a href="alta_usuario.php" class="btn btn-success">Agregar Usuario</a>
    </div>
</body>
</html>


