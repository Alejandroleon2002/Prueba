<?php
// DWES02 - Aplicación de agenda en PHP - Autor: JUAN
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

// Mostrar los contactos existentes en la agenda
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda de Contactos</title>
</head>
<body>
    <h1>Agenda de Contactos</h1>

    <!-- Mensaje de advertencia si existe -->
    <?php if (!empty($mensaje)): ?>
        <p style="color: red;"><?php echo $mensaje; ?></p>
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
</body>
</html>
