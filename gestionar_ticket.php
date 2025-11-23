<?php
session_start();
include 'bd.php'; // Incluye el archivo que contiene la función modificar()

// 1. Verificar la sesión y el rol
if (!isset($_SESSION['Id_rol']) || $_SESSION['Id_rol'] != 2) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: tecnico.php");
    exit;
}

// 2. Obtener y validar los datos
$id_ticket = filter_input(INPUT_POST, 'id_ticket', FILTER_VALIDATE_INT);
$id_tecnico = filter_input(INPUT_POST, 'id_tecnico', FILTER_VALIDATE_INT);
$accion = $_POST['accion'] ?? '';

if (!$id_ticket || !$id_tecnico || !in_array($accion, ['aceptar', 'finalizar'])) {
    header("Location: tecnico.php?action=error&msg=invalid_data");
    exit;
}

try {
    if ($accion === 'aceptar') {
        // ACCIÓN: ACEPTAR/TOMAR TICKET
        // Asigna el Id_Tecnico logueado al ticket.
        $query_update = "UPDATE Ticket SET Id_Tecnico = $1 WHERE Id_Ticket = $2";
        $datos_a_modificar = array($id_tecnico, $id_ticket);

    } elseif ($accion === 'finalizar') {
        // ACCIÓN: FINALIZAR/CERRAR TICKET
        // Cambia el campo Estado a TRUE y registra la Fecha_Terminado.
        $query_update = "UPDATE Ticket SET Estado = TRUE, Fecha_Terminado = CURRENT_TIMESTAMP WHERE Id_Ticket = $1 AND Id_Tecnico = $2";
        $datos_a_modificar = array($id_ticket, $id_tecnico);
    }
    
    // 3. Ejecutar la modificación
    modificar($query_update, $datos_a_modificar);

    // 4. Redirigir con éxito
    header("Location: tecnico.php?action=success");
    exit;   

} catch (Exception $e) {
    header("Location: tecnico.php?action=error&msg=db_fail");
    exit;
}
?>
    