<?php
    // 1. FUNCIÓN CENTRAL DE CONEXIÓN
    // Conexión a PostgreSQL en Render
    // URL de Conexión: postgresql://ivan:lRQPy6PBPUaXTQOHpTqe5ZvbEkLKYGqS@dpg-d4evo8i4d50c73e4emlg-a.oregon-postgres.render.com/shadowticketsupport_jqr2
    function conectar() {
        // Credenciales de Render PostgreSQL
        $host = "dpg-d4evo8i4d50c73e4emlg-a.oregon-postgres.render.com";
        $port = "5432";
        $user = "ivan";
        $password = "lRQPy6PBPUaXTQOHpTqe5ZvbEkLKYGqS";
        $database = "shadowticketsupport_jqr2";
        
        $connection_string = "host={$host} port={$port} user={$user} password={$password} dbname={$database}";
        return pg_connect($connection_string);
    }

    // 2. FUNCIONES CRUD BÁSICAS (usando conectar())
    function insertar($query, $datos){
        $con = conectar();
        $respuesta = pg_prepare($con, "insertar_stmt", $query);
        $respuesta2 = pg_execute($con, "insertar_stmt", $datos);
        pg_close($con);
    }
    
    function eliminar($query, $datos){
        $con = conectar();
        $respuesta = pg_prepare($con, "delete_stmt", $query);
        $respuesta2 = pg_execute($con, "delete_stmt", $datos);
        pg_close($con);
    }
    
    function modificar($query, $datos){
        $con = conectar();
        $respuesta = pg_prepare($con, "update_stmt", $query);
        $respuesta2 = pg_execute($con, "update_stmt", $datos);
        pg_close($con);
    }
    
    function seleccionar($query, $datos){
        $con = conectar();
        // Usamos un nombre único para pg_prepare en cada llamada para evitar conflictos
        $stmt_name = "select_" . uniqid(); 
        $respuesta = pg_prepare($con, $stmt_name, $query);
        $respuesta2 = pg_execute($con, $stmt_name, $datos);

        $resultados = pg_fetch_all($respuesta2);

        pg_close($con);
        return $resultados;
    }

    // 3. FUNCIONES ADICIONALES (usando conectar())
    
    // Función para obtener la lista de prioridades
    function seleccionar_prioridades(){
        $con = conectar();
        $query = "SELECT Id_Prioridad, Nombre_Prioridad FROM Prioridad ORDER BY Id_Prioridad";
        $respuesta = pg_prepare($con, "select_prioridades", $query);
        $respuesta2 = pg_execute($con, "select_prioridades", array());

        $resultados = pg_fetch_all($respuesta2);

        pg_close($con);
        return $resultados;
    }

    // Función para obtener la lista de técnicos de soporte (Id_Rol = 2)
    function seleccionar_tecnicos(){
        $con = conectar(); // ESTA LÍNEA AHORA FUNCIONA
        $query = "SELECT Id_Usuario, Nombre, Apellido FROM Usuarios WHERE Id_Rol = 2 ORDER BY Apellido, Nombre";
        $respuesta = pg_prepare($con, "select_tecnicos", $query);
        $respuesta2 = pg_execute($con, "select_tecnicos", array());

        $resultados = pg_fetch_all($respuesta2);

        pg_close($con);
        return $resultados;
    }
?>