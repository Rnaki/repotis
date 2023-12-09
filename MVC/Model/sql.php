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

function obtenerdatosEliminados(){
    try {
        $sqlSelect = "SELECT * FROM Usuario where eliminado = TRUE LIMIT :filas OFFSET :datos_pagina";
        return $sqlSelect;
    } catch (Exception $e) {
        // Manejar cualquier error que pueda ocurrir al leer el archivo
        // Puedes ajustar el manejo de errores según tus necesidades
        die('Error al obtener la sentencia SQL: ' . $e->getMessage());
    }

}

function mostrarUpdateDatos(){
    try {
        $sqlSelect = "SELECT rut_usuario, nombre_usuario, apellido_usuario FROM Usuario where rut_usuario = :rut_usuario";
        return $sqlSelect;
    } catch (Exception $e) {
        // Manejar cualquier error que pueda ocurrir al leer el archivo
        // Puedes ajustar el manejo de errores según tus necesidades
        die('Error al obtener la sentencia SQL: ' . $e->getMessage());
    }
}

function sqlUpdateDatos(){
    try {
        $sqlSelect = "UPDATE Usuario SET 
                                        nombre_usuario = :nombre_usuario,
                                        apellido_usuario = :apellido_usuario
                                        where rut_usuario = :rut_usuario";
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
            WHEN cl.crearUsuario = 0 AND EXISTS (SELECT 1 FROM Usuario WHERE codigo_nfc_usuario = :nfc AND eliminado = 0) THEN 'Logear'
            WHEN cl.crearUsuario = 1 AND :nfc IN (SELECT nfc_administrador FROM administrador) THEN 
                CONCAT('Create ', :nfc) -- Retorna 'Create' seguido del valor de :nfc
            WHEN cl.crearUsuario = 1 AND EXISTS (SELECT 1 FROM Usuario WHERE codigo_nfc_usuario = :nfc AND eliminado = 0) THEN 'Logear'
            WHEN cl.crearUsuario = 1 AND NOT EXISTS (SELECT 1 FROM Usuario WHERE codigo_nfc_usuario = :nfc) THEN 'False'
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

function recuperarUsuarioEliminadoSql() {
    try {
        $sqlDelete = "UPDATE Usuario SET eliminado = FALSE WHERE rut_usuario = :rut_usuario";
        return $sqlDelete;
    } catch (Exception $e) {
        // Manejar cualquier error que pueda ocurrir al leer el archivo
        // Puedes ajustar el manejo de errores según tus necesidades
        die('Error al obtener la sentencia SQL: ' . $e->getMessage());
    }
}

function registrarDatos(){
    try {
        $registrarDatos = "INSERT into Usuario (rut_usuario,
                                                nombre_usuario,
                                                apellido_usuario,
                                                password_usuario,
                                                confirmacion_password_usuario,
                                                eliminado)
                                                VALUES (:rut_usuario,
                                                        :nombre_usuario,
                                                        :apellido_usuario,
                                                        :password_usuario,
                                                        :confirmacion_password_usuario,
                                                        FALSE);";
        return $registrarDatos;
    } catch (Exception $e) {
        // Manejar cualquier error que pueda ocurrir al leer el archivo
        // Puedes ajustar el manejo de errores según tus necesidades
        die('Error al obtener la sentencia SQL: ' . $e->getMessage());
    }

}

function ControladorLogin() {
    try {
        $controladorLogin = "INSERT INTO controladorLogin (rut_administrador,
                                                    rut_usuario,
                                                    crearNFC)
                                                    VALUES
                                                    (:rut_administrador,
                                                     :rut_usuario,
                                                     TRUE)";
        return $controladorLogin;
    } catch (Exception $e) {
        // Manejar cualquier error que pueda ocurrir al leer el archivo
        // Puedes ajustar el manejo de errores según tus necesidades
        die('Error al obtener la sentencia SQL: ' . $e->getMessage());
    }
}

function registrarNFC(){
    try {
        $registrarNFC = "INSERT INTO USUARIO (codigo_nfc_usuario) VALUES (:nfc_codigo_usuario)";
        return $registrarNFC;
    } catch (Exception $e) {
        // Manejar cualquier error que pueda ocurrir al leer el archivo
        // Puedes ajustar el manejo de errores según tus necesidades
        die('Error al obtener la sentencia SQL: ' . $e->getMessage());
    }
}

function preparaNFC(){
    try {
        $preparaNFC = "UPDATE Usuario
        SET 
            prepara_nfc = CASE 
                WHEN rut_usuario = :rut_usuario THEN 1
                ELSE 0
            END,
            rut_administrador = COALESCE(:rut_administrador, rut_administrador)
        WHERE rut_administrador = :rut_administrador OR rut_usuario = :rut_usuario;";
        return $preparaNFC;
    }catch (Exception $e) {
        // Manejar cualquier error que pueda ocurrir al leer el archivo
        // Puedes ajustar el manejo de errores según tus necesidades
        die('Error al obtener la sentencia SQL: ' . $e->getMessage());
    }
}

function loginAdministrador(){
    try {
        $loginAdministrador = "SELECT 
        CASE 
          WHEN COUNT(*) > 0 THEN 1
          ELSE 0
        END as resultado
      FROM administrador 
      WHERE rut_administrador = :rutAdministrador AND password_administrador = :passwordAdministrador;or ";
    return $loginAdministrador;
    }catch (Exception $e) {
        // Manejar cualquier error que pueda ocurrir al leer el archivo
        // Puedes ajustar el manejo de errores según tus necesidades
        die('Error al obtener la sentencia SQL: ' . $e->getMessage());
    }
}



?>
