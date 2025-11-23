<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Manejar preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

include 'bd.php'; // Incluye el archivo de conexión

// Obtener el parámetro opcion
$opcion = $_GET['opcion'] ?? $_POST['opcion'] ?? '';

switch ($opcion) {
    case 'login':
        // Endpoint de login
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            exit();
        }

        $correo = $_POST['correo'] ?? '';
        $pass = $_POST['pass'] ?? '';

        if (empty($correo) || empty($pass)) {
            echo json_encode(['success' => false, 'message' => 'Correo y contraseña son requeridos']);
            exit();
        }

        // Consulta a la base de datos
        $query = "SELECT Id_usuario, Nombre, Id_rol FROM Usuarios WHERE Correo = $1 AND Pass = $2";
        $datos = array($correo, $pass);

        $usuario = seleccionar($query, $datos);

        if ($usuario && count($usuario) > 0) {
            $user_data = $usuario[0];
            
            // Verificar que sea usuario estándar (rol 3)
            if ($user_data['id_rol'] == 3) {
                echo json_encode([
                    'success' => true,
                    'id_usuario' => (int)$user_data['id_usuario'],
                    'nombre' => $user_data['nombre'],
                    'rol' => (int)$user_data['id_rol']
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Acceso denegado. Solo usuarios estándar pueden usar la aplicación móvil.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Correo o contraseña incorrectos']);
        }
        break;

    case 'crear_ticket':
        // Endpoint para crear un nuevo ticket
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            exit();
        }

        $id_usuario = $_POST['id_usuario'] ?? '';
        $titulo = trim($_POST['titulo'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');

        if (empty($id_usuario) || empty($titulo) || empty($descripcion)) {
            echo json_encode(['success' => false, 'message' => 'Todos los campos son requeridos']);
            exit();
        }

        // Valores iniciales
        $estado_inicial = 'FALSE'; // Estado abierto
        $id_prioridad_default = 1; // Prioridad por defecto

        // Preparar la consulta SQL
        $query_insercion = "INSERT INTO Ticket (Id_Usuario, Titulo, Descripcion, Estado, Id_Prioridad) VALUES ($1, $2, $3, $4, $5)";
        $datos_a_insertar = array($id_usuario, $titulo, $descripcion, $estado_inicial, $id_prioridad_default);

        try {
            insertar($query_insercion, $datos_a_insertar);
            echo json_encode(['success' => true, 'message' => 'Ticket creado exitosamente']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error al crear el ticket: ' . $e->getMessage()]);
        }
        break;

    case 'listar_tickets':
        // Endpoint para listar tickets del usuario
        if ($_SERVER['REQUEST_METHOD'] !== 'GET' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            exit();
        }

        $id_usuario = $_GET['id_usuario'] ?? $_POST['id_usuario'] ?? '';

        if (empty($id_usuario)) {
            echo json_encode(['success' => false, 'message' => 'ID de usuario requerido']);
            exit();
        }

        // Query para obtener tickets con prioridad
        $query_tickets = "SELECT 
                            T.Id_Ticket, T.Titulo, T.Descripcion, T.Fecha_Inicio, T.Estado, 
                            P.Nombre_Prioridad
                          FROM Ticket T
                          INNER JOIN Prioridad P ON T.Id_Prioridad = P.Id_Prioridad
                          WHERE T.Id_Usuario = $1
                          ORDER BY T.Fecha_Inicio DESC";

        $tickets = seleccionar($query_tickets, array($id_usuario));

        if ($tickets === false) {
            echo json_encode(['success' => false, 'message' => 'Error al consultar tickets']);
        } else {
            // Convertir los resultados a formato JSON amigable
            $tickets_formateados = [];
            foreach ($tickets as $ticket) {
                $tickets_formateados[] = [
                    'id_ticket' => (int)$ticket['id_ticket'],
                    'titulo' => $ticket['titulo'],
                    'descripcion' => $ticket['descripcion'],
                    'fecha_inicio' => $ticket['fecha_inicio'],
                    'estado' => $ticket['estado'],
                    'nombre_prioridad' => $ticket['nombre_prioridad']
                ];
            }
            echo json_encode(['success' => true, 'tickets' => $tickets_formateados]);
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Opción no válida']);
        break;
}
?>

