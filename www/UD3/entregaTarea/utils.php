<?php

$tareas = [
        [
            'id' => 1,
            'descripcion' => 'Corregir tarea unidad 2 grupo A',
            'estado' => 'Pendiente'
        ],
        [
            'id' => 2,
            'descripcion' => 'Corregir tarea unidad 2 grupo A',
            'estado' => 'Pendiente'
        ],
        [
            'id' => 3,
            'descripcion' => 'Preparación unidad 3',
            'estado' => 'En proceso'
        ],
        [
            'id' => 4,
            'descripcion' => 'Publicar en github solución de la tarea unidad 2',
            'estado' => 'Completada'
        ]
    ];

function tareas()
{
    global $tareas;
    return $tareas;
}

function filtraCampo($campo)
{
    $campo = trim($campo);
    $campo = stripslashes($campo);
    $campo = htmlspecialchars($campo);
    return $campo;
}

function esCampoValido($campo)
{
    return !empty(filtraCampo($campo));
}

function guardar($id, $desc, $estado, $id_usuario)
{
    include_once('mysqli.php');

    function esCampoValido($campo) {
        return !empty($campo) && strlen($campo) > 2;
    }

    if (esCampoValido($id) && esCampoValido($desc) && esCampoValido($estado)) {

        $stmt = $mysqli->prepare("INSERT INTO tareas (id, descripcion, estado, id_usuario) VALUES (?, ?, ?, ?)");

        if ($stmt === false) {
            die("Error al preparar la consulta: " . $mysqli->error);
        }

        $stmt->bind_param("issi", $id, $desc, $estado, $id_usuario);

        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "<p>La tarea se almacenó correctamente:</p>";
            echo "<ul><li>ID: $id</li><li>Descripción: $desc</li><li>Estado: $estado</li><li>Usuario: $id_usuario</li></ul>";
        } else {
            echo "<p class='error'>Hubo un error al guardar la tarea.</p>";
        }

        $stmt->close();
    } else {
        echo '<p class="error">Alguno de los campos no es válido.</p>';
    }

    $mysqli->close();
}

function obtenerUsuarios() {
    include_once('pdo.php');

    $stmt = $pdo->query("SELECT id, username FROM usuarios");

    $usuarios = [];
    if ($stmt) {
        while ($usuario = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $usuarios[] = $usuario;
        }
    }

    return $usuarios;
}



