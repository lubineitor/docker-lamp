<?php
// Incluir la conexión a la base de datos
require_once('mysqli.php');

// Función para eliminar una tarea
function borrarTarea($id) {
    global $mysqli;  // Usamos la conexión global

    // Preparar la consulta de borrado
    $query = "DELETE FROM tareas WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $id);

    // Ejecutar la consulta y devolver el resultado
    return $stmt->execute();
}

// Verificar si se ha recibido un ID válido en la URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Verificar si la tarea existe antes de intentar eliminarla
    $query = "SELECT * FROM tareas WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Si la tarea existe, proceder a eliminarla
        if (borrarTarea($id)) {
            $success = "La tarea ha sido eliminada correctamente.";
        } else {
            $error = "Hubo un error al eliminar la tarea.";
        }
    } else {
        $error = "La tarea con el ID especificado no existe.";
    }
} else {
    $error = "No se ha especificado el ID de la tarea.";
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrar Tarea</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <?php include_once('header.php'); ?>

    <div class="container-fluid">
        <div class="row">
            
            <?php include_once('menu.php'); ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="container justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2>Borrar Tarea</h2>
                </div>

                <div class="container justify-content-between">
                    <!-- Mostrar mensajes de error o éxito -->
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php elseif (isset($success)): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>

                    <!-- Volver a la lista de tareas -->
                    <a href="tareas.php" class="btn btn-secondary mt-3">Volver a la lista de tareas</a>
                </div>
            </main>
        </div>
    </div>

    <?php include_once('footer.php'); ?>
    
</body>
</html>
