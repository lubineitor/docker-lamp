<?php
// Iniciar la sesión
session_start();

// Conexión a la base de datos
include_once('mysqli.php'); // Asegúrate de que este archivo incluya la conexión mysqli

// Función para almacenar la tarea en la base de datos
function guardarTarea($titulo, $descripcion, $estado, $usuario) {
    global $mysqli;  // Usamos la conexión global

    // Preparamos la consulta
    $stmt = $mysqli->prepare("INSERT INTO tareas (titulo, descripcion, estado, id_usuario) VALUES (?, ?, ?, ?)");
    if ($stmt === false) {
        die("Error al preparar la consulta: " . $mysqli->error);
    }

    // Hacemos un bind de los parámetros y ejecutamos
    $stmt->bind_param('ssss', $titulo, $descripcion, $estado, $usuario);
    $result = $stmt->execute();

    // Cerramos el statement
    $stmt->close();

    return $result;
}

// Validación y filtrado de los datos del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir y filtrar los datos
    $titulo = filter_var(trim($_POST['titulo']), FILTER_SANITIZE_STRING);
    $descripcion = filter_var(trim($_POST['descripcion']), FILTER_SANITIZE_STRING);
    $estado = filter_var($_POST['estado'], FILTER_SANITIZE_STRING);
    $usuario = filter_var(trim($_POST['usuario']), FILTER_SANITIZE_STRING);

    // Validación de los campos
    if (empty($titulo) || empty($descripcion) || empty($estado) || empty($usuario)) {
        // Guardar mensaje de error en la sesión
        $_SESSION['message'] = "Todos los campos son obligatorios.";
        $_SESSION['message_type'] = "error";
    } else {
        // Intentamos guardar la tarea
        if (guardarTarea($titulo, $descripcion, $estado, $usuario)) {
            // Guardar mensaje de éxito en la sesión
            $_SESSION['message'] = "La tarea se almacenó correctamente.";
            $_SESSION['message_type'] = "success";
        } else {
            // Guardar mensaje de error en la sesión
            $_SESSION['message'] = "Hubo un error al guardar la tarea.";
            $_SESSION['message_type'] = "error";
        }
    }

    // Redirigir para evitar el reenvío de formularios
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Usuario</title>
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
                    <h2>Gestión de Usuario</h2>
                </div>

                <div class="container justify-content-between">
                    <div class="alert-container">
                        <?php if (isset($_SESSION['message'])): ?>
                            <div class="alert <?php echo 'alert-' . $_SESSION['message_type']; ?>">
                                <?php
                                    echo $_SESSION['message'];
                                    unset($_SESSION['message']);
                                    unset($_SESSION['message_type']);
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <?php include_once('footer.php'); ?>
    
</body>
</html>

