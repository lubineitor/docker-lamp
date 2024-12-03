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
    // Incluir el archivo de conexión a la base de datos
    include_once('mysqli.php');  // Se asume que mysqli.php está en el mismo directorio

    // Función de validación
    function esCampoValido($campo) {
        return !empty($campo) && strlen($campo) > 2;
    }

    // Comprobar que todos los campos son válidos
    if (esCampoValido($id) && esCampoValido($desc) && esCampoValido($estado)) {

        // Preparar la consulta de inserción
        $stmt = $mysqli->prepare("INSERT INTO tareas (id, descripcion, estado, id_usuario) VALUES (?, ?, ?, ?)");

        if ($stmt === false) {
            die("Error al preparar la consulta: " . $mysqli->error);
        }

        // Vincular los parámetros
        $stmt->bind_param("issi", $id, $desc, $estado, $id_usuario); // "issi" especifica el tipo de los parámetros

        // Ejecutar la consulta
        $stmt->execute();

        // Verificar si la inserción fue exitosa
        if ($stmt->affected_rows > 0) {
            echo "<p>La tarea se almacenó correctamente:</p>";
            echo "<ul><li>ID: $id</li><li>Descripción: $desc</li><li>Estado: $estado</li><li>Usuario: $id_usuario</li></ul>";
        } else {
            echo "<p class='error'>Hubo un error al guardar la tarea.</p>";
        }

        // Cerrar el statement
        $stmt->close();
    } else {
        echo '<p class="error">Alguno de los campos no es válido.</p>';
    }

    // Cerrar la conexión
    $mysqli->close();
}

function obtenerUsuarios() {
    // Incluir el archivo de conexión a la base de datos (PDO)
    include_once('pdo.php');  // Asumiendo que el archivo pdo.php está en el mismo directorio

    // Ejecutar la consulta para obtener los usuarios
    $stmt = $pdo->query("SELECT id, username FROM usuarios");

    // Comprobar si la consulta devolvió resultados
    $usuarios = [];
    if ($stmt) {
        // Recorrer el resultado y almacenar los usuarios en un array
        while ($usuario = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $usuarios[] = $usuario;
        }
    }

    // Retornar el array de usuarios
    return $usuarios;
}



