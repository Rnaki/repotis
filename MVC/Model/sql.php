<?php
// Ajusta la ruta según la ubicación de tu archivo

function obtenerSentenciaSQL() {
    try {
        $sqlSelect = "SELECT * FROM Usuario where eliminado = FALSE LIMIT :filas OFFSET :datos_pagina";
        return $sqlSelect;
    } catch (Exception $e) {
        // Manejar cualquier error que pueda ocurrir al leer el archivo
        // Puedes ajustar el manejo de errores según tus necesidades
        die('Error al obtener la sentencia SQL: ' . $e->getMessage());
    }
}

function buscarNfc($nfc) {
    try {
        $sqlSelect = "SELECT
        CASE
            WHEN EXISTS (SELECT 1 FROM Usuario WHERE codigo_nfc_usuario = :nfc AND eliminado = 0) AND cl.crearUsuario = 0 THEN 'Logear'
            WHEN EXISTS (SELECT 1 FROM Usuario WHERE codigo_nfc_usuario = :nfc AND eliminado = 0) AND cl.crearUsuario = 1 THEN 'Logear'
            WHEN NOT EXISTS (SELECT 1 FROM Usuario WHERE codigo_nfc_usuario = :nfc AND eliminado = 0) AND cl.crearUsuario = 1 THEN 'Crear'
            ELSE 'False'
        END AS resultado
    FROM
        controladorLogin cl;";
        return $sqlSelect;
    } catch (Exception $e) {
        // Manejar cualquier error que pueda ocurrir al leer el archivo
        // Puedes ajustar el manejo de errores según tus necesidades
        die('Error al obtener la sentencia SQL: ' . $e->getMessage());
    }
}

function borrarUsuarioSQL() {
    try {
        $sqlDelete = "UPDATE Usuario SET eliminado = TRUE WHERE rut_usuario = :rut_usuario";
        return $sqlDelete;
    } catch (Exception $e) {
        // Manejar cualquier error que pueda ocurrir al leer el archivo
        // Puedes ajustar el manejo de errores según tus necesidades
        die('Error al obtener la sentencia SQL: ' . $e->getMessage());
    }
}

?>
