<?php
session_start();
include 'bd.php'; // Incluye el archivo para la conexión y consultas

// Si no es un usuario estándar (Id_rol = 3), lo redirige al login
if (!isset($_SESSION['Id_rol']) || $_SESSION['Id_rol'] != 3) {
    header("Location: login.php");
    exit;
}

$nombre_usuario = $_SESSION['Nombre']; 
$id_usuario = $_SESSION['Id_usuario']; 
$view = $_GET['view'] ?? 'new_ticket'; // Define la vista por defecto (nuevo ticket)

$tickets = [];
if ($view === 'tickets') {
    // Lógica para obtener solo los tickets del usuario logueado
    $query_tickets = "SELECT 
                        T.Id_Ticket, T.Titulo, T.Descripcion, T.Fecha_Inicio, T.Estado, 
                        P.Nombre_Prioridad AS PrioridadActual,
                        COALESCE(U_Tec.Nombre || ' ' || U_Tec.Apellido, 'Sin Asignar') AS TecnicoAsignado
                      FROM Ticket T
                      INNER JOIN Prioridad P ON T.Id_Prioridad = P.Id_Prioridad
                      LEFT JOIN Usuarios U_Tec ON T.Id_Tecnico = U_Tec.Id_Usuario
                      WHERE T.Id_Usuario = $1
                      ORDER BY T.Fecha_Inicio DESC";

    $tickets = seleccionar($query_tickets, array($id_usuario));
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Estándar | Shadow Ticket Support</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; } 
        .navbar-custom { background-color: #2c5364; } 
        .card-custom {
            border: 1px solid #2c5364;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
    <div class="container-fluid">
        <a class="navbar-brand" href="estandar.php">Shadow Ticket Support</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <span class="nav-link">Hola, <?php echo $nombre_usuario; ?> (Usuario Estándar)</span>
                </li>
                <li class="nav-item">
                    <a class="btn btn-outline-light ms-2" href="logout.php">Cerrar Sesión</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    
    <div class="row justify-content-center">
        <div class="col-md-10">
            
            <ul class="nav nav-tabs mb-4 justify-content-center">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($view === 'new_ticket') ? 'active' : ''; ?>" 
                       href="estandar.php?view=new_ticket" 
                       style="color: <?php echo ($view === 'new_ticket') ? '#2c5364' : 'gray'; ?>; border-color: <?php echo ($view === 'new_ticket') ? '#2c5364' : '#dee2e6'; ?>;">
                        Crear Nuevo Ticket
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($view === 'tickets') ? 'active' : ''; ?>" 
                       href="estandar.php?view=tickets"
                       style="color: <?php echo ($view === 'tickets') ? '#2c5364' : 'gray'; ?>; border-color: <?php echo ($view === 'tickets') ? '#2c5364' : '#dee2e6'; ?>;">
                        Ver Mis Tickets Actuales
                    </a>
                </li>
            </ul>


            <?php if ($view === 'new_ticket'): ?>
                <h2 class="mb-4 text-center">Crear Nuevo Ticket de Soporte</h2>
                
                <?php
                if (isset($_GET['status']) && $_GET['status'] == 'success') {
                    echo '<div class="alert alert-success" role="alert">Ticket creado exitosamente.</div>';
                }
                if (isset($_GET['status']) && $_GET['status'] == 'error') {
                    echo '<div class="alert alert-danger" role="alert">Error al crear el ticket. Por favor, inténtelo de nuevo.</div>';
                }
                ?>
                
                <div class="card card-custom">
                    <div class="card-body">
                        <form action="crear_ticket.php" method="POST">
                            <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">
                            
                            <div class="mb-3">
                                <label for="asunto" class="form-label">Asunto del Ticket (Título)</label>
                                <input type="text" class="form-control" id="asunto" name="asunto" required maxlength="255">
                            </div>

                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción del Problema</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="5" required></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100" style="background-color: #2c5364; border-color: #2c5364;">Enviar Ticket</button>
                        </form>
                    </div>
                </div>

            <?php elseif ($view === 'tickets'): ?>
                <h2 class="mb-4 text-center">Mis Tickets Enviados</h2>

                <?php if (empty($tickets)): ?>
                    <div class="alert alert-info text-center">Aún no has creado ningún ticket de soporte.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Título</th>
                                    <th>Fecha Inicio</th>
                                    <th>Estado</th>
                                    <th>Prioridad</th>
                                    <th>Técnico Asignado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tickets as $ticket): ?>
                                <tr>
                                    <td><?php echo $ticket['id_ticket']; ?></td>
                                    <td title="<?php echo htmlspecialchars($ticket['descripcion']); ?>">
                                        <strong><?php echo htmlspecialchars($ticket['titulo']); ?></strong>
                                    </td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($ticket['fecha_inicio'])); ?></td>
                                    <td>
                                        <?php 
                                            if ($ticket['estado'] == 'f') { // 'f' es FALSE (Abierto)
                                                echo '<span class="badge bg-danger">ABIERTO</span>';
                                            } else {
                                                echo '<span class="badge bg-success">FINALIZADO</span>';
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-info text-dark"><?php echo htmlspecialchars($ticket['prioridadactual']); ?></span>
                                    </td>
                                    <td><?php echo htmlspecialchars($ticket['tecnicoasignado']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>