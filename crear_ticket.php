<?php
session_start();
include 'bd.php'; // Incluye el archivo que contiene la función insertar()

// 1. Verificar la sesión y el método
if (!isset($_SESSION['Id_rol']) || $_SESSION['Id_rol'] != 3) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: estandar.php");
    exit;
}

// 2. Obtener y sanear los datos del formulario
$id_usuario = $_POST['id_usuario'];
$titulo = trim($_POST['asunto']); // El formulario usa 'asunto', lo mapeamos a 'Titulo'
$descripcion = trim($_POST['descripcion']);

// Valores iniciales basados en tu nuevo esquema:
$estado_inicial = 'FALSE'; // El campo Estado es BOOLEAN, FALSE = Abierto/Pendiente
$id_prioridad_default = 1; // Asumimos que 1 es el ID para una prioridad por defecto (ej: "Baja")

// 3. Validar datos mínimos
if (empty($titulo) || empty($descripcion) || empty($id_usuario)) {
    header("Location: estandar.php?status=error&msg=faltan_datos");
    exit;
}

// 4. Preparar la consulta SQL y los datos
// Columnas actualizadas: Id_Usuario, Titulo, Descripcion, Estado, Id_Prioridad
$query_insercion = "INSERT INTO Ticket (Id_Usuario, Titulo, Descripcion, Estado, Id_Prioridad) VALUES ($1, $2, $3, $4, $5)";
$datos_a_insertar = array($id_usuario, $titulo, $descripcion, $estado_inicial, $id_prioridad_default);

try {
    // 5. Ejecutar la inserción usando la función de bd.php
    insertar($query_insercion, $datos_a_insertar);

    // 6. Redirigir al dashboard con mensaje de éxito
    header("Location: estandar.php?status=success");
    exit;

} catch (Exception $e) {
    // Si ocurre un error
    header("Location: estandar.php?status=error&msg=db_fail");
    exit;
}
?>