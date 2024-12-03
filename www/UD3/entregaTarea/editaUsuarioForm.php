<?php
session_start(); // Inicia la sesión para poder usar $_SESSION

// Incluir la conexión PDO
include_once('pdo.php'); // Si prefieres PDO, descomenta esta línea.

// Obtener el ID del usuario desde la URL
$id = $_GET['id'] ?? null;

if ($id) {
    // Recuperar los datos del usuario para precargar en el formulario usando PDO
    $query = "SELECT * FROM usuarios WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$usuario) {
        $_SESSION['message'] = "Usuario no encontrado.";
        $_SESSION['message_type'] = 'danger';
        header("Location: listaUsuarios.php"); // Redirigir a la lista de usuarios si no se encuentra el usuario
        exit();
    }
} else {
    $_SESSION['message'] = "ID de usuario inválido.";
    $_SESSION['message_type'] = 'danger';
    header("Location: listaUsuarios.php"); // Redirigir a la lista de usuarios si no se proporciona el ID
    exit();
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
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
                    <h2>Editar Usuario</h2>
                </div>

                <!-- Mostrar el mensaje si existe en la sesión -->
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-<?php echo $_SESSION['message_type']; ?>">
                        <?php
                            echo $_SESSION['message'];
                            unset($_SESSION['message']);
                            unset($_SESSION['message_type']);
                        ?>
                    </div>
                <?php endif; ?>

                <!-- Formulario de edición -->
                <form method="POST" action="editaUsuario.php">
                    <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                    
                    <div class="mb-3">
                        <label for="username" class="form-label">Usuario</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo $usuario['username']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $usuario['nombre']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="apellidos" class="form-label">Apellidos</label>
                        <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?php echo $usuario['apellidos']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="contrasena" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="contrasena" name="contrasena">
                    </div>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </form>
            </main>
        </div>
    </div>

    <?php include_once('footer.php'); ?>

</body>
</html>
