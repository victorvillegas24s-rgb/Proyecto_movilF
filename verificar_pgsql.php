<?php
/**
 * Script de verificación de extensión PostgreSQL en PHP
 * Ejecutar este archivo desde el navegador o línea de comandos para verificar la configuración
 */

echo "=== Verificación de Extensión PostgreSQL en PHP ===\n\n";

// Información de PHP
echo "Versión de PHP: " . phpversion() . "\n";
echo "Sistema Operativo: " . PHP_OS . "\n\n";

// Verificar si la extensión pgsql está cargada
echo "1. Verificando extensión pgsql:\n";
if (extension_loaded('pgsql')) {
    echo "   ✓ Extensión pgsql está CARGADA\n";
    echo "   Versión: " . phpversion('pgsql') . "\n";
} else {
    echo "   ✗ Extensión pgsql NO está cargada\n";
    echo "   ACCIÓN REQUERIDA: Necesitas habilitar la extensión pgsql\n";
}

echo "\n";

// Verificar funciones de PostgreSQL
echo "2. Verificando funciones de PostgreSQL:\n";
$funciones_pgsql = [
    'pg_connect',
    'pg_prepare',
    'pg_execute',
    'pg_fetch_all',
    'pg_close'
];

foreach ($funciones_pgsql as $funcion) {
    if (function_exists($funcion)) {
        echo "   ✓ Función $funcion disponible\n";
    } else {
        echo "   ✗ Función $funcion NO disponible\n";
    }
}

echo "\n";

// Verificar configuración de php.ini
echo "3. Información de configuración:\n";
$php_ini_path = php_ini_loaded_file();
if ($php_ini_path) {
    echo "   Archivo php.ini cargado: $php_ini_path\n";
} else {
    echo "   ⚠ No se encontró archivo php.ini cargado\n";
}

$extension_dir = ini_get('extension_dir');
echo "   Directorio de extensiones: $extension_dir\n";

echo "\n";

// Intentar conexión de prueba (solo si la extensión está cargada)
if (extension_loaded('pgsql')) {
    echo "4. Prueba de conexión a PostgreSQL:\n";
    echo "   Intentando conectar a la base de datos de Render...\n";
    
    require_once 'bd.php';
    
    try {
        $con = conectar();
        if ($con) {
            echo "   ✓ Conexión exitosa a PostgreSQL\n";
            pg_close($con);
        } else {
            echo "   ✗ Error al conectar (verifica credenciales en bd.php)\n";
        }
    } catch (Exception $e) {
        echo "   ✗ Error: " . $e->getMessage() . "\n";
    }
} else {
    echo "4. Prueba de conexión:\n";
    echo "   ⚠ No se puede probar la conexión sin la extensión pgsql\n";
}

echo "\n";
echo "=== Fin de la verificación ===\n";

// Si se ejecuta desde navegador, mostrar HTML
if (php_sapi_name() !== 'cli') {
    echo "<br><br>";
    echo "<h3>Instrucciones para habilitar pgsql:</h3>";
    echo "<h4>Windows:</h4>";
    echo "<ol>";
    echo "<li>Abre el archivo php.ini (ubicado en: $php_ini_path)</li>";
    echo "<li>Busca la línea: <code>;extension=pgsql</code></li>";
    echo "<li>Quita el punto y coma (;) al inicio: <code>extension=pgsql</code></li>";
    echo "<li>Si no existe, agrega: <code>extension=pgsql</code></li>";
    echo "<li>Guarda el archivo y reinicia el servidor web (Apache/Nginx)</li>";
    echo "</ol>";
    
    echo "<h4>Linux (Ubuntu/Debian):</h4>";
    echo "<pre>sudo apt-get install php-pgsql\nsudo systemctl restart apache2  # o nginx</pre>";
    
    echo "<h4>macOS (Homebrew):</h4>";
    echo "<pre>brew install php-pgsql\nbrew services restart php</pre>";
}
?>




