<?php

include 'conexion.php';

// Ensure that the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST["estado"] == 1){
        //reconocimiento facial para login
    }
        
    
    if ($_POST["estado"] == 0){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Read the JSON data from the request body
        $postData = file_get_contents('php://input');
    
        // Decode JSON data
        $decodedData = json_decode($postData, true);
    
        // Process the data and insert into MySQL table
        if ($decodedData !== null) {
            // Create a connection to the database
            $conn = establishConnection();
    
            // Extract data from the decoded JSON
            //$keyValue = "logrado";  // Replace 'key' with the actual key in your JSON
            $keyValue = $decodedData["nfc"];
            // Insert data into MySQL table using prepared statement
            $sql = "INSERT INTO Trabajador (nfc) VALUES (:keyValue)";
            // Replace 'your_table_name' with the actual name of your table and 'column_name' with the actual column name
    
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':keyValue', $keyValue);
    
            try {
                $stmt->execute();
                echo "Data inserted successfully :D";
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
    
            // Close the database connection
            closeConnection($conn);
        } else {
            echo "Invalid JSON data";
        }
        // send bit raspberry
        $_POST["estado"] = 0;
        
    } else {
        // Return an error for non-POST requests
        header("HTTP/1.1 405 Method Not Allowed");
        echo "Only POST requests are allowed.";
    }
    }
} 
    ?>