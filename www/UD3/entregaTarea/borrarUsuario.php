<?php
session_start(); // Inicia la sesión para poder usar $_SESSION

// Cambia esta línea dependiendo de la conexión que desees usar:
// Incluye el archivo para la conexión PDO o MySQLi
include_once('pdo.php');  // Para PDO
// include_once('mysqli.php');  // Para MySQLi

// Verificar que el ID del usuario esté presente en la URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Comprobar si el usuario existe
    if (isset($pdo)) { // Usando PDO
        $query = "SELECT * FROM usuarios WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
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
        try {
            // Comenzar una transacción para garantizar que ambas operaciones (eliminar tareas y el usuario) sean atómicas
            if (isset($pdo)) { // Usando PDO
                $pdo->beginTransaction();
            } elseif (isset($mysqli)) { // Usando MySQLi
                // Iniciar transacción con MySQLi no es necesario, pero puedes hacerlo si es necesario
                // MySQLi no soporta transacciones con el mismo flujo que PDO, por lo que esto es opcional
            }

            // Eliminar todas las tareas asociadas al usuario
            if (isset($pdo)) { // Usando PDO
                $query = "DELETE FROM tareas WHERE id_usuario = :id_usuario";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':id_usuario', $id, PDO::PARAM_INT);
                $stmt->execute();
            } elseif (isset($mysqli)) { // Usando MySQLi
                $query = "DELETE FROM tareas WHERE id_usuario = ?";
                $stmt = $mysqli->prepare($query);
                $stmt->bind_param("i", $id);
                $stmt->execute();
            }

            // Eliminar el usuario
            if (isset($pdo)) { // Usando PDO
                $query = "DELETE FROM usuarios WHERE id = :id";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            } elseif (isset($mysqli)) { // Usando MySQLi
                $query = "DELETE FROM usuarios WHERE id = ?";
                $stmt = $mysqli->prepare($query);
                $stmt->bind_param("i", $id);
                $stmt->execute();
            }

            // Confirmar la transacción
            if (isset($pdo)) { // Usando PDO
                $pdo->commit();
            } elseif (isset($mysqli)) { // Usando MySQLi
                // Con MySQLi, puedes manejar la transacción si la necesitas explícitamente
            }

            // Establecer un mensaje de éxito
            $_SESSION['message'] = "Usuario y sus tareas eliminadas correctamente.";
            $_SESSION['message_type'] = 'success'; // Tipo de mensaje
        } catch (Exception $e) {
            // Si algo falla, revertir la transacción
            if (isset($pdo)) { // Usando PDO
                $pdo->rollBack();
            } elseif (isset($mysqli)) { // Usando MySQLi
                // No es necesario manejar rollBack explícitamente en MySQLi sin transacciones
            }

            $_SESSION['message'] = "Error al borrar el usuario: " . $e->getMessage();
            $_SESSION['message_type'] = 'danger'; // Tipo de mensaje de error
        }
    } else {
        // Si el usuario no existe
        $_SESSION['message'] = "Usuario no encontrado.";
        $_SESSION['message_type'] = 'danger'; // Tipo de mensaje de error
    }

    // Redirigir correctamente a la página de lista de usuarios sin crear un bucle de redirección
    header("Location: borrarUsuario.php"); // Redirigir sin el ID, ya que es innecesario en este caso
    exit(); // Asegurarse de que el script termine aquí para evitar que se ejecute código posterior
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
