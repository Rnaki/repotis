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

    private function handleRegistroUsuarioNFC() {
        $postData = file_get_contents('php://input');
        $decodedData = json_decode($postData, true);
        $conexion = establishConnection();
    
        // Verificar si solo hay datos NFC y no hay reconocimiento facial
        if ($decodedData["nfc"] !== null && $decodedData["nfcAdmin"] == "189074271987") {
                $valor1 = $decodedData["nfc"]; // Ajusta según tu implementación
                $sql = registrarNFC(); // Ajusta esto según tu implementación
                $stmt = $conexion->prepare($sql);
                
                $stmt->bindParam(':nfc_codigo_usuario', $valor1, PDO::PARAM_STR);
                
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
    $nfcProcessor->handleRegistroUsuarioNFC();
?>
