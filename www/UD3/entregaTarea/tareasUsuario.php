<?php
require_once('pdo.php');

function obtenerTareas($usuario = null, $estado = null) {
    global $pdo;

    $query = "SELECT t.id, t.titulo, t.descripcion, t.estado, u.username FROM tareas t 
              JOIN usuarios u ON t.id_usuario = u.id";
    
    $conditions = [];
    $params = [];

    if ($usuario) {
        $conditions[] = "t.id_usuario = :usuario";
        $params[':usuario'] = $usuario;
    }

    if ($estado) {
        $conditions[] = "t.estado = :estado";
        $params[':estado'] = $estado;
    }

    if (count($conditions) > 0) {
        $query .= " WHERE " . implode(" AND ", $conditions);
    }

    $stmt = $pdo->prepare($query);

    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$usuario = isset($_GET['usuario']) ? $_GET['usuario'] : null;
$estado = isset($_GET['estado']) ? $_GET['estado'] : null;

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
                                <tr>
                                    <th>Identificador</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                    <th>Usuario</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
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
