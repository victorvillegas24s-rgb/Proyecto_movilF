<?php
session_start();
include 'bd.php'; // Incluye tu archivo de conexión

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. Obtener y sanear las entradas
    $correo = $_POST['correo'];
    $pass = $_POST['pass'];

    // 2. Consulta a la base de datos
    $query = "SELECT Id_usuario, Nombre, Id_rol FROM Usuarios WHERE Correo = $1 AND Pass = $2";
    $datos = array($correo, $pass);

    $usuario = seleccionar($query, $datos);

    // 3. Verificación y Redirección
    if ($usuario && count($usuario) > 0) {
        $user_data = $usuario[0];

        // Guardar datos del usuario en la sesión
        $_SESSION['Id_usuario'] = $user_data['id_usuario'];
        $_SESSION['Nombre'] = $user_data['nombre'];
        $_SESSION['Id_rol'] = $user_data['id_rol'];

        $rol_id = $user_data['id_rol'];

        // Redirección basada en el rol con los nuevos nombres de archivo
        switch ($rol_id) {
            case 1:
                // Administrador
                header("Location: administrador.php"); // <-- CORREGIDO
                break;
            case 2:
                // Técnico
                header("Location: tecnico.php"); // <-- CORREGIDO
                break;
            case 3:
                // Estándar
                header("Location: estandar.php"); // <-- CORREGIDO
                break;
            default:
                // Si el rol no está definido, enviamos al login con error
                header("Location: login.php?error=invalid_role");
                break;
        }
        exit;
    } else {
        // Credenciales incorrectas
        header("Location: login.php?error=credenciales_invalidas");
        exit;
    }

} else {
    // Si alguien intenta acceder directamente a este archivo sin POST
    header("Location: login.php");
    exit;
}
?>