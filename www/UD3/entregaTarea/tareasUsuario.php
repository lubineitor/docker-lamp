<?php
// Incluir la conexión PDO
require_once('pdo.php');

// Función para obtener tareas filtradas por usuario y estado
function obtenerTareas($usuario = null, $estado = null) {
    global $pdo;

    // Crear la consulta base
    $query = "SELECT t.id, t.titulo, t.descripcion, t.estado, u.username FROM tareas t 
              JOIN usuarios u ON t.id_usuario = u.id";
    
    // Condiciones para la búsqueda
    $conditions = [];
    $params = [];

    if ($usuario) {
        // Si el usuario está presente, se filtra por el nombre de usuario
        $conditions[] = "t.id_usuario = :usuario";
        $params[':usuario'] = $usuario;
    }

    if ($estado) {
        // Si el estado está presente, se filtra por el estado
        $conditions[] = "t.estado = :estado";
        $params[':estado'] = $estado;
    }

    // Agregar las condiciones a la consulta si existen
    if (count($conditions) > 0) {
        $query .= " WHERE " . implode(" AND ", $conditions);
    }

    // Preparar la consulta
    $stmt = $pdo->prepare($query);

    // Ejecutar la consulta con los parámetros
    $stmt->execute($params);

    // Obtener y devolver los resultados
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Comprobar si se ha enviado el formulario de búsqueda
$usuario = isset($_GET['usuario']) ? $_GET['usuario'] : null;
$estado = isset($_GET['estado']) ? $_GET['estado'] : null;

// Obtener las tareas filtradas
$tareas = obtenerTareas($usuario, $estado);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Tareas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <?php include_once('header.php'); ?>

    <div class="container-fluid">
        <div class="row">
            <?php include_once('menu.php'); ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="container justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2>Mis Tareas</h2>
                </div>

                <div class="container justify-content-between">
                    <div class="table">
                        <table class="table table-striped table-hover">
                            <thead class="thead">
                                <tr>                             <!-- Encabezado de la tabla -->
                                    <th>Identificador</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                    <th>Usuario</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    // Si se recibieron parámetros, mostrar tareas filtradas
                                    if ($tareas && count($tareas) > 0) {
                                        foreach ($tareas as $tarea) {
                                            echo '<tr>';
                                            echo '<td>' . $tarea['id'] . '</td>';
                                            echo '<td>' . $tarea['descripcion'] . '</td>';
                                            echo '<td>' . $tarea['estado'] . '</td>';
                                            echo '<td>' . $tarea['username'] . '</td>';
                                            echo '</tr>';
                                        }
                                    } else {
                                        echo '<tr><td colspan="5">No se encontraron tareas para los criterios de búsqueda proporcionados.</td></tr>';
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <?php include_once('footer.php'); ?>

</body>
</html>
