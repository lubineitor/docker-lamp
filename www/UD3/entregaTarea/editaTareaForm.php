<?php
// Incluir la conexión a la base de datos
require_once('mysqli.php');

function obtenerTarea($id) {
    global $mysqli;  // Usamos la conexión global

    $query = "SELECT id, titulo, descripcion, estado, id_usuario FROM tareas WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $id);  // Vinculamos el ID como parámetro
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;  // No se encuentra la tarea
    }
}

// Verificar si se ha pasado un ID de tarea
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $tarea = obtenerTarea($id);

    if (!$tarea) {
        echo "La tarea no existe.";
        exit;
    }
} else {
    echo "No se ha especificado un ID de tarea.";
    exit;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tarea</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <?php include_once('header.php'); ?>

    <div class="container-fluid">
        <div class="row">
            
            <?php include_once('menu.php'); ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="container justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2>Editar Tarea</h2>
                </div>

                <div class="container justify-content-between">
                    <form action="editaTarea.php" method="POST" class="mb-5 w-75">
                        <div class="mb-3">
                            <label for="id" class="form-label">Identificador</label>
                            <input type="number" class="form-control" id="id" name="id" value="<?php echo $tarea['id']; ?>" readonly required>
                        </div>
                        <div class="mb-3">
                            <label for="titulo" class="form-label">Titulo</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo $tarea['titulo']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <input type="text" class="form-control" id="descripcion" name="descripcion" value="<?php echo $tarea['descripcion']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" id="estado" name="estado" required>
                                <option value="en_proceso" <?php echo $tarea['estado'] == 'en_proceso' ? 'selected' : ''; ?>>En Proceso</option>
                                <option value="pendiente" <?php echo $tarea['estado'] == 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                                <option value="completada" <?php echo $tarea['estado'] == 'completada' ? 'selected' : ''; ?>>Completada</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="usuario" class="form-label">Usuario</label>
                            <select class="form-select" id="usuario" name="usuario" required>
                                <?php
                                    // Obtener los usuarios desde la base de datos
                                    require_once('utils.php');
                                    $usuarios = obtenerUsuarios(); 
                                    foreach ($usuarios as $usuario) {
                                        $selected = ($usuario['id'] == $tarea['id_usuario']) ? 'selected' : '';
                                        echo "<option value='" . $usuario['id'] . "' $selected>" . $usuario['username'] . "</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <?php include_once('footer.php'); ?>
    
</body>
</html>
