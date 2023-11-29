<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include '../../Model/conexion.php';
class NFCProcessor {
    private $action;

    public function __construct() {
        // Inicializamos la acción por defecto como 'lecturaNFC'
        $this->action = 'lecturaNFC';
    }

    public function processRequest() {
        // Comprobamos la acción actual y ejecutamos la lógica correspondiente
        switch ($this->action) {
            case 'lecturaNFC':
                $this->handleLecturaNFC();
                break;
            case 'registroUsuarioNFC':
                $this->handleRegistroUsuarioNFC();
                break;
            default:
                $this->respondError('Acción no válida');
        }
    }
    private function handleLecturaNFC() {
        $postData = file_get_contents('php://input');
        $decodedData = json_decode($postData, true);
        // ...
        if ($decodedData["nfc"] !== null && $decodedData["rf"] == null) {
            //$this->processNFCData($decodedData["nfc"]);
            echo "logrado";  // Añade un mensaje para verificar si llega a este punto
        } elseif ($decodedData["rf"] == true && $decodedData["nfc"] == null) {
            echo "Welcome to the system";
        } elseif ($decodedData["rf"] == false && $decodedData["nfc"] == null) {
            echo "U cannot Pass";
        } else {
            $this->respondError('Datos inválidos');
        }
    }
    private function handleRegistroUsuarioNFC() {
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postData = file_get_contents('php://input');
            $decodedData = json_decode($postData, true);

            // Lógica para el registro de usuario con NFC
            if ($decodedData["nfc"] !== null) {
                $this->processRegistroUsuarioNFC($decodedData["nfc"]);
            } else {
                $this->respondError('Datos inválidos para el registro de usuario con NFC');
            }
        } else {
            $this->respondError('Only POST requests are allowed.');
        }
    }

    private function respondError($message) {
        header("HTTP/1.1 400 Bad Request");
        echo $message;
    }

    public function setAction($action) {
        // Método para cambiar la acción desde el lado del cliente
        $this->action = $action;
    }
}
    $nfcProcessor = new NFCProcessor();
    $nfcProcessor->processRequest();
?>
