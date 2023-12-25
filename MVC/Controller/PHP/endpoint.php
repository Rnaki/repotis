<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include '../../Model/conexion.php';
require_once '../../Model/sql.php';
class NFCProcessor {
    private $conexion;
    private $action;
    private $sentenciasSQLManager;

    public function __construct() {
        $this->conexion = Connection::obtenerInstancia()->obtenerConexion();
        $this->action = 'lecturaNFC';
        $this->sentenciasSQLManager = new SentenciasSQLManager();
        
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
    
        // Verificar si solo hay datos NFC y no hay reconocimiento facial
        if (isset($decodedData["nfc"]) && $decodedData["nfc"] !== null) {
                $valor1 = $decodedData["nfc"]; // Ajusta según tu implementación
                $sql = $this->sentenciasSQLManager->buscarNfc($valor1); // Ajusta esto según tu implementación
                $stmt = $this->conexion->prepare($sql);
                
                $stmt->bindParam(':nfc', $valor1, PDO::PARAM_STR);
                
            try {
                $stmt->execute();
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                $resultado;
                // Access the 'resultado' value
                echo $fullResult = $resultado['resultado'];

            // Extract the action and NFC value
            list($action, $nfc_administrador) = explode(':', $fullResult);
            if($action == 'Create'){
                session_start();
                $_SESSION["nfc_administrador"] = $nfc_administrador;
                $sql2 = $this->sentenciasSQLManager->administradorCreaNfc();
                $stmt2 = $this->conexion->prepare($sql2);
                $stmt2->bindParam(':nfcAdmin', $_SESSION["nfc_administrador"], PDO::PARAM_STR);
                try {
                    $stmt2->execute();
                    $resultado2 = $stmt2->fetch(PDO::FETCH_ASSOC);
                    if($resultado2 == 1){
                        echo $resultado2;
                    }
                    
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }

            }else if($action == 'Update'){
                session_start();
                $_SESSION["nfc_administrador"] = $nfc_administrador;
                $sql3 = $this->sentenciasSQLManager->administradorUpdateNfc();
                $stmt3 = $this->conexion->prepare($sql3);
                $stmt3->bindParam(':nfcAdmin', $_SESSION["nfc_administrador"], PDO::PARAM_STR);
                try {
                    $stmt3->execute();
                    $resultado3 = $stmt3->fetch(PDO::FETCH_ASSOC);
                    echo $resultado3;
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
            }else if($action == 'Login'){
                echo $action;
            }else if($action == 'NotLogin') {
                echo 'NO ESTAS REGISTRADO NO PUEDES Logear';
            }
            
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        } 
        //Verifica si el post corresponde a create
        elseif (isset($decodedData["nfcCreate"]) && $decodedData["nfcCreate"] !== null) {
            $nfcNewUser = $decodedData["nfcCreate"];
            $nfc_administrador = $decodedData["nfcAdmin"];
            $sql3 = $this->sentenciasSQLManager->createUserNfc();

            $stmt3 = $this->conexion->prepare($sql3);
            $stmt3->bindParam(':nfcNewUser', $nfcNewUser, PDO::PARAM_STR);
            $stmt3->bindParam(':nfc_administrador', $nfc_administrador, PDO::PARAM_STR);
        
            try {
                $stmt3->execute();
                $resultado3 = $stmt3->fetch(PDO::FETCH_ASSOC);
                 echo $resultado3;
             } catch (PDOException $e) {
                 echo "Error: " . $e->getMessage();
            }

        } 
        elseif (isset($decodedData["nfcUpdate"]) && $decodedData["nfcUpdate"] !== null) {
            $nfcUpdateUser = $decodedData["nfcUpdate"];
            $nfc_administrador = $decodedData["nfcAdmin"];
            echo $new_time_update = date("Y-m-d H:i:s");
            $sql3 = $this->sentenciasSQLManager->UpdateUserNfc();
        
            $stmt3 = $this->conexion->prepare($sql3);
            $stmt3->bindParam(':nfcUpdateUser', $nfcUpdateUser, PDO::PARAM_STR);
            $stmt3->bindParam(':nfc_administrador', $nfc_administrador, PDO::PARAM_STR);
            $stmt3->bindParam(':new_time_update', $new_time_update, PDO::PARAM_STR);
        
            try {
                $stmt3->execute();
                $resultado3 = $stmt3->fetch(PDO::FETCH_ASSOC);
                return 1;
             } catch (PDOException $e) {
                 echo "Error: " . $e->getMessage();
            }

        } 
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
