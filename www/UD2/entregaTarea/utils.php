<?php
$tareas = [
    ["id" => 1, "descripcion" => "Estudiar PHP", "estado" => "pendiente"],
    ["id" => 2, "descripcion" => "Finalizar el proyecto de desarrollo", "estado" => "en proceso"],
    ["id" => 3, "descripcion" => "Revisar documentación de HTML y CSS", "estado" => "completada"],
];

function obtenerTareas() {
    global $tareas;
    return $tareas;
}

function filtrarCampo($campo) {
    $campo = trim(preg_replace('/\s+/', ' ', $campo));
    return $campo;
}

function esCampoValido($campo) {
    $campo = filtrarCampo($campo);
    return !empty($campo) && strlen($campo) >= 3;
}

function guardarTarea($titulo, $descripcion, $prioridad)  {
    global $tareas;
    
    $titulo = filtrarCampo($titulo);
    $descripcion = filtrarCampo($descripcion);
    $prioridad = filtrarCampo($prioridad);

    if (esCampoValido($descripcion) && in_array($prioridad, ['baja', 'media', 'alta'])) {
        $id = count($tareas) + 1;
        $nuevaTarea = [
            "id" => $id,
            "titulo" => $titulo,
            "descripcion" => $descripcion,
            "prioridad" => $prioridad,
            "estado" => "pendiente"
        ];

        $tareas[] = $nuevaTarea;
        return true;
    } else {
        return false;
    }
}
?>
