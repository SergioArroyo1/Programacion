<?php
require_once '../controlador/UsuariosController.php'; // Incluye el controlador de usuarios

// Verifica si se recibe un ID válido en la URL
if (!isset($_GET['id'])) {
    die("Error: No se proporcionó un ID de usuario."); // Si no hay ID, se muestra un error y se detiene la ejecución
}

$id_usuario = $_GET['id'];
$controller = new UsuariosController(); // Instancia del controlador

// Obtiene la información del usuario desde la base de datos
$usuario = $controller->obtenerUsuarioPorId($id_usuario);

// Obtiene los paquetes contratados por el usuario
$paquetes = $controller->obtenerPaquetesPorUsuario($id_usuario);

// Define los precios de los planes de suscripción
$precios_planes = [
    "Plan Basico (1 dispositivo)" => 9.99,
    "Plan Estandar (2 dispositivos)" => 13.99,
    "Plan Premium (4 dispositivos)" => 17.99
];

// Define los precios de los paquetes adicionales
$precios_paquetes = [
    "Deporte" => 6.99,
    "Cine" => 7.99,
    "Infantil" => 4.99
];

// Relaciona los nombres de los planes en la BD con los nombres que se usan en el array de precios
$mapa_planes = [
    "Basico" => "Plan Basico (1 dispositivo)",
    "Estandar" => "Plan Estandar (2 dispositivos)",
    "Premium" => "Plan Premium (4 dispositivos)"
];

// Convierte el nombre del plan obtenido de la BD al formato del array de precios
$nombre_plan_convertido = $mapa_planes[$usuario['nombre_plan']] ?? "";

// Obtiene el precio del plan del usuario
$costo_plan = $precios_planes[$nombre_plan_convertido] ?? 0;

// Calcula el costo total de los paquetes adicionales que tiene contratados
$costo_paquetes = 0;
foreach ($paquetes as $paquete) {
    $nombre_paquete = $paquete['nombre_paquete'];
    $costo_paquetes += $precios_paquetes[$nombre_paquete] ?? 0;
}

// Calcula el costo total mensual sumando el plan base y los paquetes adicionales
$costo_total = $costo_plan + $costo_paquetes;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Costo Desglosado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">Costo Desglosado</h1>
        <h3>Usuario: <?php echo htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellidos']); ?></h3>
        <h4>Correo: <?php echo htmlspecialchars($usuario['correo']); ?></h4>

        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>Concepto</th>
                    <th>Costo Mensual (€)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo htmlspecialchars($nombre_plan_convertido); ?></td>
                    <td><?php echo number_format($costo_plan, 2); ?> €</td>
                </tr>

                <?php if (!empty($paquetes)): ?> 
                    <!-- Si el usuario tiene paquetes contratados, se muestran en la tabla -->
                    <?php foreach ($paquetes as $paquete): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($paquete['nombre_paquete']); ?></td>
                            <td><?php echo number_format($precios_paquetes[$paquete['nombre_paquete']] ?? 0, 2); ?> €</td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Si el usuario no tiene paquetes contratados, se muestra un mensaje -->
                    <tr>
                        <td colspan="2">No tiene paquetes adicionales contratados</td>
                    </tr>
                <?php endif; ?>

                <!-- Fila con el costo total -->
                <tr class="table-success">
                    <th>Total Mensual</th>
                    <th><?php echo number_format($costo_total, 2); ?> €</th>
                </tr>
            </tbody>
        </table>

        <a href="lista_usuarios.php" class="btn btn-primary">Volver a la Lista de Usuarios</a>
    </div>
</body>

</html>
