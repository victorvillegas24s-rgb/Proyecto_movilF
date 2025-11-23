<?php
/**
 * Script simple de prueba para verificar extensión pgsql
 * Ejecutar: php test_pgsql.php
 */

echo "=== Prueba de Extensión PostgreSQL ===\n\n";

// Verificar si la extensión está cargada
if (extension_loaded('pgsql')) {
    echo "✓ Extensión pgsql está HABILITADA\n";
    echo "  Versión: " . phpversion('pgsql') . "\n\n";
    
    // Verificar funciones principales
    $funciones = ['pg_connect', 'pg_prepare', 'pg_execute', 'pg_fetch_all'];
    echo "Funciones disponibles:\n";
    foreach ($funciones as $func) {
        echo "  " . (function_exists($func) ? "✓" : "✗") . " $func\n";
    }
    
    echo "\n";
    
    // Intentar conexión de prueba
    echo "Intentando conectar a la base de datos...\n";
    require_once 'bd.php';
    
    try {
        $con = conectar();
        if ($con) {
            echo "✓ Conexión exitosa a PostgreSQL\n";
            pg_close($con);
        } else {
            echo "✗ Error al conectar (verifica credenciales en bd.php)\n";
        }
    } catch (Exception $e) {
        echo "✗ Error: " . $e->getMessage() . "\n";
    }
    
} else {
    echo "✗ Extensión pgsql NO está habilitada\n\n";
    echo "Sigue estos pasos:\n";
    echo "1. Abre tu archivo php.ini\n";
    echo "2. Busca: ;extension=pgsql\n";
    echo "3. Quita el punto y coma: extension=pgsql\n";
    echo "4. Guarda y reinicia tu servidor web\n\n";
    echo "Para más detalles, consulta: INSTRUCCIONES_PGSQL.md\n";
}

echo "\n=== Fin de la prueba ===\n";
?>




