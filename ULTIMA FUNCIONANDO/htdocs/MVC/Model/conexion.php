<?php

function establishConnection() {
    // Database credentials
    $servername = "190.114.253.43";
    $username = "unap";
    $password = "unap123!";
    $dbname = "sistemarobusto";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

function closeConnection($conn) {
    $conn = null;  // Close the connection
}

?>

