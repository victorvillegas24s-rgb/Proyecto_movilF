<?php
/**
 * Endpoints de tickets:
 * - GET /api/tickets/tecnico - Obtiene tickets abiertos para técnicos
 * - POST /api/tickets/gestionar - Gestiona tickets (aceptar/finalizar)
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Auth-Token');

// Manejar preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../bd.php';
require_once 'auth_helper.php';

// Determinar la acción basada en la ruta y método
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Obtener parámetro de acción desde GET/POST o URI
$accion_param = $_GET['accion'] ?? $_POST['accion'] ?? null;
$tipo_param = $_GET['tipo'] ?? null;

// Leer el body para POST si viene en JSON
$input_json = null;
if ($method === 'POST') {
    $raw_input = file_get_contents('php://input');
    if ($raw_input) {
        $input_json = json_decode($raw_input, true);
        if ($input_json && isset($input_json['accion'])) {
            $accion_param = $input_json['accion'];
        }
    }
}

// Endpoint: GET /api/tickets/tecnico
// Puede venir como: /api/tickets.php?tipo=tecnico o /api/tickets/tecnico
if ($method === 'GET' && ($tipo_param === 'tecnico' || strpos($uri, '/tecnico') !== false)) {
    obtenerTicketsTecnico();
}
// Endpoint: POST /api/tickets/gestionar
// Puede venir como: /api/tickets.php con POST body (JSON o form-data) o /api/tickets/gestionar
elseif ($method === 'POST' && ($accion_param !== null || strpos($uri, '/gestionar') !== false)) {
    gestionarTicket();
}
else {
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'message' => 'Endpoint no encontrado. Use GET con ?tipo=tecnico o POST con acción en body.'
    ]);
}

/**
 * GET /api/tickets/tecnico
 * Obtiene la lista de tickets abiertos (Estado=FALSE)
 */
function obtenerTicketsTecnico() {
    // Validar autenticación
    $token_data = requerirAutenticacion();
    
    try {
        // Query para obtener tickets abiertos con información del creador y prioridad
        $query_tickets = "SELECT 
                            T.Id_Ticket, 
                            T.Titulo, 
                            T.Id_Tecnico,
                            T.Estado,
                            T.Fecha_Inicio,
                            U.Nombre AS CreadorNombre, 
                            U.Apellido AS CreadorApellido,
                            P.Nombre_Prioridad AS Prioridad
                          FROM Ticket T
                          INNER JOIN Usuarios U ON T.Id_Usuario = U.Id_Usuario
                          INNER JOIN Prioridad P ON T.Id_Prioridad = P.Id_Prioridad
                          WHERE T.Estado = FALSE
                          ORDER BY P.Id_Prioridad DESC, T.Fecha_Inicio ASC";
        
        $tickets = seleccionar($query_tickets, array());
        
        if ($tickets === false) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error al consultar tickets'
            ]);
            return;
        }
        
        // Formatear respuesta según el contrato
        $tickets_formateados = [];
        foreach ($tickets as $ticket) {
            $nombre_completo = trim($ticket['creadornombre'] . ' ' . ($ticket['creadorapellido'] ?? ''));
            
            $tickets_formateados[] = [
                'Id_Ticket' => (int)$ticket['id_ticket'],
                'Titulo' => $ticket['titulo'],
                'Id_Tecnico' => $ticket['id_tecnico'] ? (int)$ticket['id_tecnico'] : null,
                'Fecha_Inicio' => $ticket['fecha_inicio'],
                'CreadorNombre' => $nombre_completo,
                'Prioridad' => $ticket['prioridad']
            ];
        }
        
        // Retornar array directamente según el contrato
        echo json_encode($tickets_formateados);
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error en el servidor: ' . $e->getMessage()
        ]);
    }
}

/**
 * POST /api/tickets/gestionar
 * Permite al técnico aceptar o finalizar un ticket
 */
function gestionarTicket() {
    // Validar autenticación
    $token_data = requerirAutenticacion();
    
    // Solo técnicos (rol 2) pueden gestionar tickets
    if ($token_data['role'] != 2) {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'message' => 'Acceso denegado. Solo técnicos pueden gestionar tickets.'
        ]);
        return;
    }
    
    // Obtener datos del body (JSON o form-data)
    $raw_input = file_get_contents('php://input');
    $input = null;
    
    if ($raw_input) {
        $input = json_decode($raw_input, true);
    }
    
    if (!$input || empty($input)) {
        $input = $_POST;
    }
    
    $id_ticket = isset($input['id_ticket']) ? filter_var($input['id_ticket'], FILTER_VALIDATE_INT) : null;
    $id_tecnico = isset($input['id_tecnico']) ? filter_var($input['id_tecnico'], FILTER_VALIDATE_INT) : null;
    $accion = $input['accion'] ?? '';
    
    // Validar datos
    if (!$id_ticket || !$id_tecnico || !in_array($accion, ['aceptar', 'finalizar'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Datos inválidos. Se requiere: id_ticket, id_tecnico y accion (aceptar/finalizar)'
        ]);
        return;
    }
    
    // Validar que el id_tecnico coincida con el usuario autenticado
    if ($id_tecnico != $token_data['user_id']) {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'message' => 'El ID de técnico no coincide con el usuario autenticado'
        ]);
        return;
    }
    
    try {
        if ($accion === 'aceptar') {
            // ACCIÓN: ACEPTAR TICKET
            // Verificar que el ticket no esté ya asignado a otro técnico
            $query_verificar = "SELECT Id_Tecnico, Estado FROM Ticket WHERE Id_Ticket = $1";
            $ticket_actual = seleccionar($query_verificar, array($id_ticket));
            
            if (!$ticket_actual || count($ticket_actual) === 0) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Ticket no encontrado'
                ]);
                return;
            }
            
            $ticket_data = $ticket_actual[0];
            if ($ticket_data['id_tecnico'] !== null && $ticket_data['id_tecnico'] != $id_tecnico) {
                http_response_code(409);
                echo json_encode([
                    'success' => false,
                    'message' => 'El ticket ya está asignado a otro técnico'
                ]);
                return;
            }
            
            // Asignar el ticket al técnico
            $query_update = "UPDATE Ticket SET Id_Tecnico = $1 WHERE Id_Ticket = $2 AND Id_Tecnico IS NULL";
            $datos_update = array($id_tecnico, $id_ticket);
            modificar($query_update, $datos_update);
            
            echo json_encode([
                'success' => true,
                'message' => 'Ticket aceptado exitosamente'
            ]);
            
        } elseif ($accion === 'finalizar') {
            // ACCIÓN: FINALIZAR TICKET
            // Verificar que el ticket esté asignado a este técnico
            $query_verificar = "SELECT Id_Tecnico, Estado FROM Ticket WHERE Id_Ticket = $1";
            $ticket_actual = seleccionar($query_verificar, array($id_ticket));
            
            if (!$ticket_actual || count($ticket_actual) === 0) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Ticket no encontrado'
                ]);
                return;
            }
            
            $ticket_data = $ticket_actual[0];
            if ($ticket_data['id_tecnico'] != $id_tecnico) {
                http_response_code(403);
                echo json_encode([
                    'success' => false,
                    'message' => 'El ticket no está asignado a este técnico'
                ]);
                return;
            }
            
            if ($ticket_data['estado'] === true || $ticket_data['estado'] === 't') {
                http_response_code(409);
                echo json_encode([
                    'success' => false,
                    'message' => 'El ticket ya está finalizado'
                ]);
                return;
            }
            
            // Finalizar el ticket
            $query_update = "UPDATE Ticket SET Estado = TRUE, Fecha_Terminado = CURRENT_TIMESTAMP WHERE Id_Ticket = $1 AND Id_Tecnico = $2";
            $datos_update = array($id_ticket, $id_tecnico);
            modificar($query_update, $datos_update);
            
            echo json_encode([
                'success' => true,
                'message' => 'Ticket finalizado exitosamente'
            ]);
        }
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error en el servidor: ' . $e->getMessage()
        ]);
    }
}
?>

