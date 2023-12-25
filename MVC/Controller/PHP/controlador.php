<?php

require_once '../../Model/modelo.php';

class AdministradorController {

    public function __construct() {}

    public function procesarSolicitud($data, $modelo) {
        if (isset($data["funcion"]) && method_exists($modelo, $data["funcion"])) {
            $method = $data["funcion"];
            echo $modelo->$method($data);  // Corregir aquí
        } else {
            // Devolver una respuesta de error si la función no existe
            $response = ['status' => 'error', 'message' => 'Función no válida'];
            return json_encode($response);
        }
    }
}

$controlador = new AdministradorController();
$data = json_decode(file_get_contents("php://input"), true);

// Verificar si se recibieron datos JSON válidos
if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
    $controlador->procesarSolicitud($data, new AdministradorModel());
} else {
    // Devolver una respuesta de error si los datos no son válidos
    $response = ['status' => 'error', 'message' => 'Datos no válidos'];
    return json_encode($response);
}
?>
