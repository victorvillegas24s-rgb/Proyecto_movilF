<?php
/**
 * Endpoint: POST /api/login
 * Autentica usuarios y devuelve token de sesión
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Manejar preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../bd.php';
require_once 'auth_helper.php';

// Solo aceptar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido'
    ]);
    exit();
}

// Obtener datos del body (JSON o form-data)
$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    $input = $_POST;
}

$correo = $input['correo'] ?? '';
$pass = $input['pass'] ?? '';

// Validar campos requeridos
if (empty($correo) || empty($pass)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Correo y contraseña son requeridos'
    ]);
    exit();
}

try {
    // Consulta a la base de datos
    $query = "SELECT Id_usuario, Nombre, Id_rol FROM Usuarios WHERE Correo = $1 AND Pass = $2";
    $datos = array($correo, $pass);
    
    $usuario = seleccionar($query, $datos);
    
    if ($usuario && count($usuario) > 0) {
        $user_data = $usuario[0];
        
        // Generar token de sesión
        $token = generarToken($user_data['id_usuario'], $user_data['id_rol']);
        
        // Respuesta exitosa según el contrato
        echo json_encode([
            'success' => true,
            'user' => [
                'Id_usuario' => (int)$user_data['id_usuario'],
                'Nombre' => $user_data['nombre'],
                'Id_rol' => (int)$user_data['id_rol'],
                'token' => $token
            ]
        ]);
    } else {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'message' => 'Credenciales inválidas.'
        ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error en el servidor: ' . $e->getMessage()
    ]);
}
?>


