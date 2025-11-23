<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shadow Ticket Support - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Fondo degradado oscuro para la página */
        body {
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #f8f9fa; /* Color de texto claro */
        }
        /* Estilo para el contenedor del login */
        .login-container {
            background-color: #1a2c34; /* Un azul muy oscuro para el fondo del formulario */
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            padding: 40px;
            max-width: 400px;
            width: 100%;
        }
        /* Estilo para los inputs */
        .form-control {
            background-color: #0d1a20; /* Inputs más oscuros */
            border: 1px solid #2c5364; /* Borde azul oscuro */
            color: #f8f9fa;
        }
        .form-control:focus {
            background-color: #0d1a20;
            border-color: #4CAF50; /* Un toque de color para el focus */
            box-shadow: 0 0 0 0.25rem rgba(76, 175, 80, 0.25);
            color: #f8f9fa;
        }
        /* Estilo para el botón */
        .btn-primary-custom {
            background-color: #2c5364;
            border-color: #2c5364;
            transition: background-color 0.3s ease;
        }
        .btn-primary-custom:hover {
            background-color: #203a43;
            border-color: #203a43;
        }
        /* Estilo para la imagen del logo */
        .logo {
            width: 150px;
            margin-bottom: 20px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body>

<div class="login-container">
    <img src="LogoSinFondo.png" alt="Shadow Ticket Support Logo" class="logo">
    <h2 class="text-center mb-4 text-light">Inicio de Sesión</h2>

    <?php
    // Mostrar mensajes de error si existen
    if (isset($_GET['error'])) {
        echo '<div class="alert alert-danger text-center" role="alert">
                Correo o contraseña incorrectos.
              </div>';
    }
    ?>

    <form action="procesar_login.php" method="POST">
        <div class="mb-3">
            <label for="correo" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" id="correo" name="correo" required>
        </div>
        <div class="mb-4">
            <label for="pass" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="pass" name="pass" required>
        </div>
        <button type="submit" class="btn btn-primary-custom w-100">Acceder</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>