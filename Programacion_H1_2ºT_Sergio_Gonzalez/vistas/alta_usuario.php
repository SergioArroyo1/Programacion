<?php

// Se importa el controlador de usuarios para manejar las operaciones relacionadas con los usuarios
require_once '../controlador/UsuariosController.php';
$controller = new UsuariosController();
$error_message = ''; // Variable para almacenar mensajes de error
$edad = isset($_POST['edad']) ? $_POST['edad'] : null; // Se asegura de que la edad tenga un valor, aunque sea null

// Se obtienen los paquetes disponibles desde la base de datos
$paquetes = $controller->obtenerPaquetes();

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Verifica si el formulario fue enviado
    // Se obtienen los datos del formulario
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $correo = $_POST['correo'];
    $edad = $_POST['edad'];
    $nombre_plan = $_POST['nombre_plan'];
    $duracion = $_POST['duracion'];
    $paquetes_seleccionados = isset($_POST['paquetes']) ? $_POST['paquetes'] : []; // Se obtiene la lista de paquetes seleccionados

    // Restricción: Si la edad es menor de 18, solo pueden elegir el paquete "Infantil"
    if ($edad < 18) {
        foreach ($paquetes_seleccionados as $id_paquete) {
            // Se busca la información del paquete seleccionado
            $paquete_info = array_filter($paquetes, fn($p) => $p['id_paquete'] == $id_paquete);
            $paquete_info = reset($paquete_info); // Se obtiene el primer elemento del array

            if ($paquete_info['nombre_paquete'] !== 'Infantil') { // Si no es el paquete "Infantil", se muestra un error
                $error_message = "Los menores de 18 años solo pueden contratar el Pack Infantil.";
                break;
            }
        }
    }

    // Restricción: El Plan Básico solo permite un paquete adicional
    if ($nombre_plan == 'Basico' && count($paquetes_seleccionados) > 1) {
        $error_message = "Los usuarios con Plan Básico solo pueden seleccionar un paquete adicional.";
    }

    // Restricción: El Pack Deporte solo se puede contratar si la duración es "Anual"
    if (in_array(1, $paquetes_seleccionados) && $duracion !== 'Anual') {
        $error_message = "El Pack Deporte solo puede ser contratado si la duración de la suscripción es Anual.";
    }

    // Si no hay errores, se procede a registrar el usuario en la base de datos
    if (!$error_message) {
        $usuario = $controller->agregarUsuario($nombre, $apellidos, $correo, $edad, $nombre_plan, $duracion, $paquetes_seleccionados);

        if (!$usuario) { // Si hay un error al guardar, se muestra un mensaje
            $error_message = "Error al agregar usuario. Por favor, verifica los datos.";
        } else { // Si todo va bien, redirige a la lista de usuarios
            header("Location: lista_usuarios.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Usuario</title>
    <script src="https://cdn.tailwindcss.com"></script> <!-- Se usa TailwindCSS para estilos modernos -->

    <script>
        function validarPaquetes() {
            let edad = document.getElementById('edad').value;
            let nombrePlan = document.getElementById('nombre_plan').value;
            let duracion = document.getElementById('duracion').value;

            // Se recorren todos los checkboxes de paquetes
            document.querySelectorAll('.form-check-input').forEach(checkbox => {
                let paqueteNombre = checkbox.getAttribute('data-nombre');
                checkbox.disabled = false; // Primero se habilitan todos

                // Restricción: Si el usuario es menor de 18, solo puede elegir "Infantil"
                if (edad < 18 && paqueteNombre !== "Infantil") {
                    checkbox.disabled = true;
                    checkbox.checked = false; // Se desmarca si no es infantil
                }

                // Restricción: Si el paquete es "Deporte", solo se puede contratar con duración "Anual"
                if (paqueteNombre === "Deporte" && duracion !== "Anual") {
                    checkbox.disabled = true;
                    checkbox.checked = false;
                }
            });
        }
    </script>
</head>

<body class="bg-gray-100 text-gray-900">
    <div class="max-w-3xl mx-auto mt-10 p-6 bg-white rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold mb-4">Agregar Usuario</h1>

        <?php if (!empty($error_message)): ?>
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-4">
                <label for="nombre" class="block font-semibold">Nombre:</label>
                <input type="text" class="w-full p-2 border rounded" id="nombre" name="nombre" required>
            </div>

            <div class="mb-4">
                <label for="apellidos" class="block font-semibold">Apellidos:</label>
                <input type="text" class="w-full p-2 border rounded" id="apellidos" name="apellidos" required>
            </div>

            <div class="mb-4">
                <label for="correo" class="block font-semibold">Correo Electrónico:</label>
                <input type="email" class="w-full p-2 border rounded" id="correo" name="correo" required>
            </div>

            <div class="mb-4">
                <label for="edad" class="block font-semibold">Edad:</label>
                <input type="number" class="w-full p-2 border rounded" id="edad" name="edad" required oninput="validarPaquetes()">
            </div>

            <div class="mb-4">
                <label for="nombre_plan" class="block font-semibold">Tipo de Plan:</label>
                <select class="w-full p-2 border rounded" id="nombre_plan" name="nombre_plan" required onchange="validarPaquetes()">
                    <option value="Basico">Básico</option>
                    <option value="Estandar">Estándar</option>
                    <option value="Premium">Premium</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="duracion" class="block font-semibold">Duración de la Suscripción:</label>
                <select class="w-full p-2 border rounded" id="duracion" name="duracion" required onchange="validarPaquetes()">
                    <option value="Mensual">Mensual</option>
                    <option value="Anual">Anual</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block font-semibold">Paquetes Adicionales:</label>
                <?php foreach ($paquetes as $paquete): ?>
                    <div class="flex items-center mt-2">
                        <input class="form-check-input mr-2" type="checkbox" name="paquetes[]" value="<?php echo $paquete['id_paquete']; ?>" id="paquete<?php echo $paquete['id_paquete']; ?>" data-nombre="<?php echo $paquete['nombre_paquete']; ?>">
                        <label for="paquete<?php echo $paquete['id_paquete']; ?>" class="text-gray-700">
                            <?php echo $paquete['nombre_paquete']; ?> - €<?php echo $paquete['precio']; ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded-lg hover:bg-blue-700">Agregar Usuario</button>
        </form>
    </div>
</body>
</html>
