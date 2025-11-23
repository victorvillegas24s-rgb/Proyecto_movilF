<?php
session_start();
include 'bd.php';

// Redirección si no es Administrador (Id_rol = 1)
if (!isset($_SESSION['Id_rol']) || $_SESSION['Id_rol'] != 1) {
    header("Location: login.php");
    exit;
}

$nombre_admin = $_SESSION['Nombre'];

// 1. Obtener la lista de prioridades y técnicos
$prioridades = seleccionar_prioridades();
$tecnicos = seleccionar_tecnicos(); // NUEVO: Lista de técnicos

// 2. Obtener TODOS los tickets
$query_tickets = "SELECT 
                    T.Id_Ticket, T.Titulo, T.Descripcion, T.Fecha_Inicio, T.Estado, T.Id_Tecnico, -- T.Id_Tecnico es clave
                    U_Creator.Nombre AS CreadorNombre, U_Creator.Apellido AS CreadorApellido, 
                    P.Nombre_Prioridad AS PrioridadActual, P.Id_Prioridad AS IdPrioridadActual,
                    COALESCE(U_Tec.Nombre || ' ' || U_Tec.Apellido, 'Sin Asignar') AS TecnicoAsignado,
                    U_Tec.Id_Usuario AS IdTecnicoActual
                  FROM Ticket T
                  INNER JOIN Usuarios U_Creator ON T.Id_Usuario = U_Creator.Id_Usuario
                  INNER JOIN Prioridad P ON T.Id_Prioridad = P.Id_Prioridad
                  LEFT JOIN Usuarios U_Tec ON T.Id_Tecnico = U_Tec.Id_Usuario -- NUEVO JOIN para el técnico
                  ORDER BY T.Fecha_Inicio DESC";

$tickets = seleccionar($query_tickets, array()); 

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Administrador | Shadow Ticket Support</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar-custom { background-color: #2c5364; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
    <div class="container-fluid">
        <a class="navbar-brand" href="administrador.php">Shadow Ticket Support - ADMIN</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <span class="nav-link">Hola, <?php echo $nombre_admin; ?> (Administrador)</span>
                </li>
                <li class="nav-item">
                    <a class="btn btn-outline-light ms-2" href="logout.php">Cerrar Sesión</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid mt-4">
    <h2 class="mb-4 text-center">Gestión de Tickets (Todos)</h2>

    <?php
    if (isset($_GET['update']) && $_GET['update'] == 'success') {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                Ticket actualizado correctamente (Prioridad y/o Técnico).
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    }
    if (empty($tickets)) {
        echo '<div class="alert alert-info text-center">No hay tickets registrados en el sistema.</div>';
    } else {
    ?>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Creado por</th>
                    <th>Estado</th>
                    <th>Prioridad Actual</th>
                    <th>Técnico Asignado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tickets as $ticket): ?>
                <tr>
                    <td><?php echo $ticket['id_ticket']; ?></td>
                    
                    <td title="<?php echo htmlspecialchars($ticket['descripcion']); ?>">
                        <strong><?php echo htmlspecialchars($ticket['titulo']); ?></strong>
                        <p class="text-muted small m-0"><?php echo htmlspecialchars(substr($ticket['descripcion'], 0, 50)) . '...'; ?></p>
                    </td>
                    
                    <td><?php echo htmlspecialchars($ticket['creadornombre'] . ' ' . $ticket['creadorapellido']); ?></td>
                    <td>
                        <?php 
                            if ($ticket['estado'] == 'f') { // 'f' es FALSE (Abierto) en PostgreSQL
                                echo '<span class="badge bg-danger">ABIERTO</span>';
                            } else {
                                echo '<span class="badge bg-success">CERRADO</span>';
                            }
                        ?>
                    </td>
                    <td>
                        <span class="badge bg-info text-dark"><?php echo htmlspecialchars($ticket['prioridadactual']); ?></span>
                    </td>
                    <td>
                        <span class="badge bg-warning text-dark"><?php echo htmlspecialchars($ticket['tecnicoasignado']); ?></span>
                    </td>
                    <td>
                        <form action="asignar_prioridad.php" method="POST">
                            <input type="hidden" name="id_ticket" value="<?php echo $ticket['id_ticket']; ?>">
                            
                            <div class="mb-2">
                                <select name="id_prioridad" class="form-select form-select-sm" required>
                                    <option value="" disabled>-- Prioridad --</option>
                                    <?php foreach ($prioridades as $p): ?>
                                        <option value="<?php echo $p['id_prioridad']; ?>"
                                            <?php echo ($p['id_prioridad'] == $ticket['idprioridadactual']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($p['nombre_prioridad']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="mb-2">
                                <select name="id_tecnico" class="form-select form-select-sm">
                                    <option value="" <?php echo (is_null($ticket['idtecnicoactual'])) ? 'selected' : ''; ?>>-- Sin Asignar --</option>
                                    <?php foreach ($tecnicos as $t): ?>
                                        <option value="<?php echo $t['id_usuario']; ?>"
                                            <?php echo ($t['id_usuario'] == $ticket['idtecnicoactual']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($t['nombre'] . ' ' . $t['apellido']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary btn-sm w-100" style="background-color: #2c5364; border-color: #2c5364;">
                                Guardar Cambios
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php } ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>