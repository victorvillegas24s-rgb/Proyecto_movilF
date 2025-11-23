<?php
/**
 * Helper para validación de tokens de autenticación
 */

// Función simple para generar un token simulado (en producción usar JWT real)
function generarToken($user_id, $role) {
    $payload = [
        'user_id' => $user_id,
        'role' => $role,
        'timestamp' => time()
    ];
    return base64_encode(json_encode($payload));
}

// Función para validar y decodificar token
function validarToken($token) {
    if (empty($token)) {
        return null;
    }
    
    try {
        $decoded = json_decode(base64_decode($token), true);
        if ($decoded && isset($decoded['user_id']) && isset($decoded['role'])) {
            // Validar que el token no sea muy antiguo (opcional, 24 horas)
            $token_age = time() - $decoded['timestamp'];
            if ($token_age > 86400) { // 24 horas
                return null;
            }
            return $decoded;
        }
    } catch (Exception $e) {
        return null;
    }
    
    return null;
}

// Función para obtener el token de los headers
function obtenerTokenDelHeader() {
    $headers = getallheaders();
    
    // Buscar token en Authorization header
    if (isset($headers['Authorization'])) {
        $auth_header = $headers['Authorization'];
        if (preg_match('/Bearer\s+(.*)$/i', $auth_header, $matches)) {
            return $matches[1];
        }
    }
    
    // También buscar en header personalizado
    if (isset($headers['X-Auth-Token'])) {
        return $headers['X-Auth-Token'];
    }
    
    // Buscar en $_SERVER como fallback
    if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $auth_header = $_SERVER['HTTP_AUTHORIZATION'];
        if (preg_match('/Bearer\s+(.*)$/i', $auth_header, $matches)) {
            return $matches[1];
        }
    }
    
    return null;
}

// Función para validar autenticación requerida
function requerirAutenticacion() {
    $token = obtenerTokenDelHeader();
    $token_data = validarToken($token);
    
    if (!$token_data) {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'message' => 'Token de autenticación inválido o faltante'
        ]);
        exit();
    }
    
    return $token_data;
}

?>


