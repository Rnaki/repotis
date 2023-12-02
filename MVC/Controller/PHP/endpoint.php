<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include '../../Model/conexion.php';
require_once '../../Model/sql.php';
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
        $conexion = establishConnection();
    
        // Verificar si solo hay datos NFC y no hay reconocimiento facial
        if ($decodedData["nfc"] !== null) {
                $valor1 = $decodedData["nfc"]; // Ajusta según tu implementación
                $sql = buscarNfc($valor1); // Ajusta esto según tu implementación
                $stmt = $conexion->prepare($sql);
                
                $stmt->bindParam(':nfc', $valor1, PDO::PARAM_STR);
                
            try {
                $stmt->execute();
                $resultado = $stmt->fetchColumn();
            
                echo $resultado;
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
                
          //  echo $decodedData["nfc"];
            // $this->processNFCData($decodedData["nfc"]);
           // echo "logrado nfc";  // Añade un mensaje para verificar si llega a este punto
        } 
        // Verificar si solo hay reconocimiento facial y no hay datos NFC
        elseif ($decodedData["nfc"] === null && $decodedData["rf"] !== null) {
            echo "logrado reconocimiento facial";
        } 
        // En caso de que haya ambos o ninguno
        else {
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
