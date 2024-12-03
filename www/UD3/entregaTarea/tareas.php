<?php
// Cambia esta línea dependiendo de la conexión que desees usar:
// include_once('pdo.php');  // Para PDO
include_once('mysqli.php');  // Para MySQLi

// Función para obtener las tareas con el nombre de usuario asociado
function getTareas() {
    global $mysqli;  // Usamos la conexión global

    $query = "
        SELECT t.id, t.titulo, t.descripcion, t.estado, u.username 
        FROM tareas t 
        JOIN usuarios u ON t.id_usuario = u.id
    ";

    // Ejecutar la consulta
    $result = $mysqli->query($query);

    // Comprobar si la consulta devuelve filas
    if ($result->num_rows > 0) {
        $tareas = [];
        while ($row = $result->fetch_assoc()) {
            $tareas[] = $row;
        }
        return $tareas;  // Devolver las tareas obtenidas
    } else {
        return [];  // Si no hay resultados, devolver un array vacío
    }
}

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
                    <h2>Mis tareas</h2>
                </div>

                <div class="container justify-content-between">
                    <div class="table">
                        <table class="table table-striped table-hover">
                            <thead class="thead">
                                <tr>                            
                                    <th>Identificador</th>
                                    <th>Titulo</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                    <th>Usuario</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    // Obtener la lista de tareas
                                    $tareas = getTareas();
                                    
                                    // Mostrar las tareas
                                    foreach ($tareas as $tarea) {
                                        echo '<tr>';
                                        echo '<td>' . $tarea['id'] . '</td>';
                                        echo '<td>' . $tarea['titulo'] . '</td>';
                                        echo '<td>' . $tarea['descripcion'] . '</td>';
                                        echo '<td>' . $tarea['estado'] . '</td>';
                                        echo '<td>' . $tarea['username'] . '</td>';
                                        // Botones de acción
                                        echo '<td>';
                                        echo '<a href="editaTareaForm.php?id=' . $tarea['id'] . '" class="btn btn-warning btn-sm">Editar</a> ';
                                        echo '<a href="borrarTarea.php?id=' . $tarea['id'] . '" class="btn btn-danger btn-sm">Borrar</a>';
                                        echo '</td>';
                                        echo '</tr>';
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
