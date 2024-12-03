<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Tareas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <?php include_once('header.php'); ?>

    <div class="container-fluid">
        <div class="row">
            
            <?php include_once('menu.php'); ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="container justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2>Buscar Tareas</h2>
                </div>

                <div class="container justify-content-between">
                    <form action="tareasUsuario.php" method="GET" class="mb-5 w-75">
                    <div class="mb-3">
                            <label for="usuario" class="form-label">Usuario</label>
                            <select class="form-select" id="usuario" name="usuario" required>
                                <option value="" selected disabled>Seleccione un usuario</option>
                                <?php
                                    require_once('utils.php');
                                    
                                    $usuarios = obtenerUsuarios();
                                    
                                    foreach ($usuarios as $usuario) {
                                        echo "<option value='" . $usuario['id'] . "'>" . $usuario['username'] . "</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" id="estado" name="estado">
                                <option value="" selected disabled>Seleccione el estado</option>
                                <option value="en_proceso">En Proceso</option>
                                <option value="pendiente">Pendiente</option>
                                <option value="completada">Completada</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <?php include_once('footer.php'); ?>
    
</body>
</html>
