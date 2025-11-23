<?php
session_start();
include 'bd.php';

// Redirección si no es Técnico (Id_rol = 2)
if (!isset($_SESSION['Id_rol']) || $_SESSION['Id_rol'] != 2) {
    header("Location: login.php");
    exit;
}

$nombre_tecnico = $_SESSION['Nombre'];
$id_tecnico = $_SESSION['Id_usuario']; // El ID del técnico logueado

// 1. Obtener TODOS los tickets
// Mostraremos: 
// - Tickets sin asignar (Id_Tecnico IS NULL)
// - Tickets asignados a ESTE técnico (Id_Tecnico = $id_tecnico)
// Unimos Ticket, Usuarios (creador) y Prioridad
$query_tickets = "SELECT 
                    T.Id_Ticket, T.Titulo, T.Descripcion, T.Fecha_Inicio, T.Estado, T.Id_Tecnico,
                    U.Nombre AS CreadorNombre, U.Apellido AS CreadorApellido, 
                    P.Nombre_Prioridad AS PrioridadActual
                  FROM Ticket T
                  INNER JOIN Usuarios U ON T.Id_Usuario = U.Id_Usuario
                  INNER JOIN Prioridad P ON T.Id_Prioridad = P.Id_Prioridad
                  WHERE T.Estado = FALSE -- Solo tickets Abiertos/Pendientes
                  ORDER BY P.Id_Prioridad DESC, T.Fecha_Inicio ASC"; // Prioridad más alta primero

$tickets = seleccionar($query_tickets, array()); 

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Técnico | Shadow Ticket Support</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar-custom { background-color: #2c5364; }
        .prioridad-1 { color: #198754; font-weight: bold; } /* Baja */
        .prioridad-2 { color: #ffc107; font-weight: bold; } /* Media */
        .prioridad-3 { color: #dc3545; font-weight: bold; } /* Alta */
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
    <div class="container-fluid">
        <a class="navbar-brand" href="tecnico.php">Shadow Ticket Support - TÉCNICO</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <span class="nav-link">Hola, <?php echo $nombre_tecnico; ?> (Técnico)</span>
                </li>
                <li class="nav-item">
                    <a class="btn btn-outline-light ms-2" href="logout.php">Cerrar Sesión</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid mt-4">
    <h2 class="mb-4 text-center">Tickets Pendientes y Asignados</h2>

    <?php
    if (isset($_GET['action']) && $_GET['action'] == 'success') {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                Acción realizada correctamente.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    }
    if (empty($tickets)) {
        echo '<div class="alert alert-info text-center">¡Genial! No hay tickets pendientes en el sistema.</div>';
    } else {
    ?>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Prioridad</th>
                    <th>Título</th>
                    <th>Creado por</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tickets as $ticket): 
                    $es_asignado = $ticket['id_tecnico'] != null;
                    $es_mio = $ticket['id_tecnico'] == $id_tecnico;
                ?>
                <tr>
                    <td><?php echo $ticket['id_ticket']; ?></td>
                    <td>
                        <span class="badge bg-secondary"><?php echo htmlspecialchars($ticket['prioridadactual']); ?></span>
                    </td>
                    <td title="<?php echo htmlspecialchars($ticket['descripcion']); ?>">
                        <strong><?php echo htmlspecialchars($ticket['titulo']); ?></strong>
                        <p class="text-muted small m-0"><?php echo htmlspecialchars(substr($ticket['descripcion'], 0, 80)) . '...'; ?></p>
                    </td>
                    <td><?php echo htmlspecialchars($ticket['creadornombre'] . ' ' . $ticket['creadorapellido']); ?></td>
                    <td>
                        <?php 
                            if ($es_asignado) {
                                $clase = $es_mio ? 'bg-warning text-dark' : 'bg-secondary';
                                $texto = $es_mio ? 'ASIGNADO A MÍ' : 'ASIGNADO';
                                echo '<span class="badge ' . $clase . '">' . $texto . '</span>';
                            } else {
                                echo '<span class="badge bg-danger">PENDIENTE</span>';
                            }
                        ?>
                    </td>
                    <td>
                        <form action="gestionar_ticket.php" method="POST" class="d-flex gap-2">
                            <input type="hidden" name="id_ticket" value="<?php echo $ticket['id_ticket']; ?>">
                            <input type="hidden" name="id_tecnico" value="<?php echo $id_tecnico; ?>">

                            <?php if (!$es_asignado): ?>
                                <button type="submit" name="accion" value="aceptar" class="btn btn-success btn-sm">
                                    <i class="fas fa-check"></i> Aceptar
                                </button>
                            <?php elseif ($es_mio): ?>
                                <button type="submit" name="accion" value="finalizar" class="btn btn-primary btn-sm">
                                    <i class="fas fa-times"></i> Finalizar
                                </button>
                            <?php else: ?>
                                <button type="button" class="btn btn-secondary btn-sm" disabled>
                                    Asignado a otro
                                </button>
                            <?php endif; ?>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php } ?>

</div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>