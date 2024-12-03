<?php
session_start(); // Inicia la sesión para poder usar $_SESSION

// Cambiar la línea de conexión dependiendo de si prefieres usar PDO o MySQLi
// Incluye la conexión PDO o MySQLi
include_once('pdo.php'); // Usar PDO
// include_once('mysqli.php'); // Usar MySQLi

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir y filtrar los datos del formulario
    $id = $_POST['id'];
    $username = trim($_POST['username']);
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $contrasena = $_POST['contrasena']; // Asegúrate de cifrar la contraseña antes de guardarla

    // Validar que el usuario existe en la base de datos
    if (isset($pdo)) { // Usando PDO
        $query = "SELECT * FROM usuarios WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Asegúrate de que $id sea una variable
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    } elseif (isset($mysqli)) { // Usando MySQLi
        $query = "SELECT * FROM usuarios WHERE id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc();
    }

    if ($usuario) {
        if (empty($contrasena)) {
            $contrasena = $usuario['contrasena'];
        } else {
            $contrasena = $contrasena;
        }

        // Actualizar el usuario en la base de datos
        if (isset($pdo)) { // Usando PDO
            $query = "UPDATE usuarios SET username = :username, nombre = :nombre, apellidos = :apellidos, contrasena = :contrasena WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':apellidos', $apellidos, PDO::PARAM_STR);
            $stmt->bindParam(':contrasena', $contrasena, PDO::PARAM_STR); // Cifra la contraseña
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $_SESSION['message'] = "Usuario actualizado correctamente.";
                $_SESSION['message_type'] = 'success'; // Tipo de mensaje
            } else {
                $_SESSION['message'] = "Error al actualizar el usuario.";
                $_SESSION['message_type'] = 'danger'; // Tipo de mensaje
            }
        } elseif (isset($mysqli)) { // Usando MySQLi
            $query = "UPDATE usuarios SET username = ?, nombre = ?, apellidos = ?, contrasena = ? WHERE id = ?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("ssssi", $username, $nombre, $apellidos, $contrasena, $id);

            if ($stmt->execute()) {
                $_SESSION['message'] = "Usuario actualizado correctamente.";
                $_SESSION['message_type'] = 'success'; // Tipo de mensaje
            } else {
                $_SESSION['message'] = "Error al actualizar el usuario.";
                $_SESSION['message_type'] = 'danger'; // Tipo de mensaje
            }
        }
    } else {
        $_SESSION['message'] = "Usuario no encontrado.";
        $_SESSION['message_type'] = 'danger'; // Tipo de mensaje
    }

    // Redirigir de nuevo a la página de edición para mostrar el mensaje
    header("Location: editaUsuario.php?id=" . $id); // Asegúrate de redirigir correctamente
    exit(); // Asegúrate de detener la ejecución del script después de redirigir
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
