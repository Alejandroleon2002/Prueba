<?php

// Tarea DWES02 Unidad de Trabajo 2.- Características del lenguaje PHP
// Autor: Alejandro León Manzanedo

session_start();  // Inicia o reanuda la sesión

// Si no existe la agenda en la sesión, inicializarla
if (!isset($_SESSION['agenda'])) {
    $_SESSION['agenda'] = [];
}

// Inicializar el mensaje
$mensaje = "";

// Comprobar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);
    $telefono = trim($_POST['telefono']);

    // Validar que el nombre no esté vacío
    if (empty($nombre)) {
        $mensaje = "El nombre no puede estar vacío.";
    } else {
        // Validar que el teléfono tenga un formato correcto (solo dígitos)
        if (!empty($telefono) && !preg_match('/^\d+$/', $telefono)) {
            $mensaje = "El número de teléfono debe contener solo dígitos.";
        } else {
            // Comprobar si el contacto ya existe
            if (array_key_exists($nombre, $_SESSION['agenda'])) {
                if (!empty($telefono)) {
                    // Si el número de teléfono no está vacío, se actualiza
                    $_SESSION['agenda'][$nombre] = $telefono;
                    $mensaje = "Número de teléfono actualizado para $nombre.";
                } else {
                    // Si el número de teléfono está vacío, se elimina el contacto
                    unset($_SESSION['agenda'][$nombre]);
                    $mensaje = "Contacto $nombre eliminado.";
                }
            } else {
                // Si el contacto no existe y el número no está vacío, se añade
                if (!empty($telefono)) {
                    $_SESSION['agenda'][$nombre] = $telefono;
                    $mensaje = "Contacto $nombre añadido a la agenda.";
                } else {
                    $mensaje = "El número de teléfono no puede estar vacío para añadir un nuevo contacto.";
                }
            }
        }
    }
}

// Mostrar los contactos existentes en la agenda
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda de Contactos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        h2 {
            color: #555;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #28a745;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }
        li:last-child {
            border-bottom: none;
        }
        .error {
            color: red;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Agenda de Contactos</h1>

        <!-- Mensaje de advertencia si existe -->
        <?php if (!empty($mensaje)): ?>
            <p class="error"><?php echo $mensaje; ?></p>
        <?php endif; ?>

        <!-- Mostrar los contactos en la agenda -->
        <h2>Contactos</h2>
        <ul>
            <?php foreach ($_SESSION['agenda'] as $nombre => $telefono): ?>
                <li><?php echo htmlspecialchars($nombre) . ': ' . htmlspecialchars($telefono); ?></li>
            <?php endforeach; ?>
        </ul>

        <!-- Formulario para añadir/editar contactos -->
        <h2>Agregar/Modificar Contacto</h2>
        <form method="post" action="">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
            <br>
            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono">
            <br>
            <input type="submit" value="Enviar">
        </form>
    </div>
</body>
</html>
