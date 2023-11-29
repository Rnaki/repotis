// sql.php
<?php

// Ajusta la ruta según la ubicación de tu archivo

function obtenerSentenciaSQL() {
    try {
        $sqlSelect = "SELECT * FROM Trabajador";
        return $sqlSelect;
    } catch (Exception $e) {
        // Manejar cualquier error que pueda ocurrir al leer el archivo
        // Puedes ajustar el manejo de errores según tus necesidades
        die('Error al obtener la sentencia SQL: ' . $e->getMessage());
    }
}

?>
