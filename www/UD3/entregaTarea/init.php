<?php
$host = 'db';
$user = 'root';
$password = 'test';

$message = '';
$error = '';

$conn = new mysqli($host, $user, $password);

if ($conn->connect_error) {
    $error = "Error al conectar al servidor MySQL: " . $conn->connect_error;
} else {
    $query = "SHOW DATABASES LIKE 'tareas'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        $message .= "<div class='warning'>La base de datos 'tareas' ya existe</div>";
    } else {
        $query = "CREATE DATABASE IF NOT EXISTS tareas";
        if ($conn->query($query) === TRUE) {
            $message .= "<div class='success'>Base de datos 'tareas' creada correctamente</div>";
        } else {
            $error .= "<div class='error'>Error al crear la base de datos: " . $conn->error . "</div>";
        }
    }

    $conn->select_db('tareas');

    $query = "SHOW TABLES LIKE 'usuarios'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        $message .= "<div class='warning'>La tabla 'usuarios' ya existe</div>";
    } else {
        $query = "
        CREATE TABLE usuarios (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL,
            nombre VARCHAR(50) NOT NULL,
            apellidos VARCHAR(100) NOT NULL,
            contrasena VARCHAR(100) NOT NULL
        )";
        if ($conn->query($query) === TRUE) {
            $message .= "<div class='success'>Tabla 'usuarios' creada correctamente</div>";
        } else {
            $error .= "<div class='error'>Error al crear la tabla 'usuarios': " . $conn->error . "</div>";
        }
    }

    $query = "SHOW TABLES LIKE 'tareas'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        $message .= "<div class='warning'>La tabla 'tareas' ya existe</div>";
    } else {
        $query = "
        CREATE TABLE tareas (
            id INT AUTO_INCREMENT PRIMARY KEY,
            titulo VARCHAR(50) NOT NULL,
            descripcion VARCHAR(250),
            estado VARCHAR(50) NOT NULL,
            id_usuario INT,
            FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
        )";
        if ($conn->query($query) === TRUE) {
            $message .= "<div class='success'>Tabla 'tareas' creada correctamente</div>";
        } else {
            $error .= "<div class='error'>Error al crear la tabla 'tareas': " . $conn->error . "</div>";
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UD3. Tarea</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 10px;
            text-align: center;
        }
        .container {
            margin: 20px auto;
            max-width: 600px;
            background: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .success, .error, .warning {
            padding: 8px;
            margin-bottom: 5px;
            border-radius: 4px;
            font-size: 14px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .warning {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }
        .alert-container {
            margin-bottom: 20px;
        }
        .menu {
            padding: 10px;
            margin-bottom: 10px;
        }
        a {
            display: block;
            margin-top: 10px;
            text-align: center;
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <?php include_once('header.php'); ?>

    <div class="container-fluid">
        <div class="row">
            
            <?php include_once('menu.php'); ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="container justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2>Menu</h2>
                </div>

                <div class="container justify-content-between">
                    
                <div class="alert-container">
                    <?php
                        echo $message;
                        echo $error;
                    ?>
                </div>
                </div>
            </main>
        </div>
    </div>

    <?php include_once('footer.php'); ?>
    
</body>
</html>