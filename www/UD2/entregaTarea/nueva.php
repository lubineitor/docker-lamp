<?php
include 'header.php';
include 'menu.php';
include 'utils.php';

$mensaje = '';
$error = false;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $prioridad = $_POST['prioridad'] ?? '';

    if (empty($titulo)) {
        $mensaje = "Error: El campo 'Título' es obligatorio.";
        $error = true;
    } elseif (empty($descripcion)) {
        $mensaje = "Error: El campo 'Descripción' es obligatorio.";
        $error = true;
    } elseif (empty($prioridad)) {
        $mensaje = "Error: Debe seleccionar una prioridad.";
        $error = true;
    } else {
        if (guardarTarea($titulo, $descripcion, $prioridad)) {
            $mensaje = "Tarea guardada correctamente.";
        } else {
            $mensaje = "Error: No se pudo guardar la tarea.";
            $error = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UD2. Resultado Nueva Tarea</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2>Resultado de la Creación de Tarea</h2>
                </div>
                <div class="container">
                    <p class="<?php echo $error ? 'text-danger' : 'text-success'; ?>">
                        <?php echo $mensaje; ?>
                    </p>
                </div>
            </main>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>