<?php
session_start();
include 'bd.php'; // Incluye el archivo que contiene la función modificar()

// 1. Verificar la sesión y el rol
if (!isset($_SESSION['Id_rol']) || $_SESSION['Id_rol'] != 1) {
    // Solo administradores pueden usar este script
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: administrador.php");
    exit;
}

// 2. Obtener y validar los datos
$id_ticket = filter_input(INPUT_POST, 'id_ticket', FILTER_VALIDATE_INT);
$id_prioridad = filter_input(INPUT_POST, 'id_prioridad', FILTER_VALIDATE_INT);
// El ID del técnico puede ser nulo si se selecciona "-- Sin Asignar --"
$id_tecnico_input = filter_input(INPUT_POST, 'id_tecnico', FILTER_VALIDATE_INT);
$id_tecnico = ($id_tecnico_input === false || is_null($id_tecnico_input)) ? null : $id_tecnico_input;


if (!$id_ticket || !$id_prioridad) {
    header("Location: administrador.php?update=error&msg=invalid_data");
    exit;
}

// 3. Preparar la consulta SQL de modificación para AMBOS campos
$query_update = "UPDATE Ticket SET Id_Prioridad = $1, Id_Tecnico = $2 WHERE Id_Ticket = $3";
// Se debe enviar NULL si no hay técnico asignado
$datos_a_modificar = array($id_prioridad, $id_tecnico, $id_ticket);

try {
    // 4. Ejecutar la modificación
    modificar($query_update, $datos_a_modificar);

    // 5. Redirigir de vuelta al dashboard con mensaje de éxito
    header("Location: administrador.php?update=success");
    exit;

} catch (Exception $e) {
    // Manejo de errores
    header("Location: administrador.php?update=error&msg=db_fail");
    exit;
}
?>