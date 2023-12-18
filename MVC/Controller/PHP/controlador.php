<?php

require_once '../../Model/modelo.php';

class AdministradorController {
    private $modelo;

    public function __construct() {
        $this->modelo = new AdministradorModel();
    }

    public function procesarSolicitud($data) {
        if (isset($data["funcion"]) && method_exists($this->modelo, $data["funcion"])) {
            $method = $data["funcion"];
            echo $this->modelo->$method($data);
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
    $controlador->procesarSolicitud($data);
} else {
    // Devolver una respuesta de error si los datos no son válidos
    $response = ['status' => 'error', 'message' => 'Datos no válidos'];
    echo json_encode($response);
}
?>
