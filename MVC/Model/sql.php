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
        COALESCE(
            (SELECT CONCAT('Create:', a.nfc_administrador) 
            FROM Usuario u
            JOIN administrador a ON u.rut_administrador = a.rut_administrador
            WHERE u.prepara_nfc = 1
                AND a.nfc_administrador = :nfc
                AND u.codigo_nfc_usuario IS NULL
                AND u.eliminado = 0
            LIMIT 1),
            
            (SELECT CONCAT('Update:', a.nfc_administrador)
            FROM Usuario u
            JOIN administrador a ON u.rut_administrador = a.rut_administrador
            WHERE u.prepara_nfc = 1
                AND a.nfc_administrador = :nfc
                AND u.codigo_nfc_usuario IS NOT NULL
                AND u.eliminado = 0
            LIMIT 1),
            
            (SELECT 'Login1:' 
            FROM Usuario u
            JOIN administrador a ON u.rut_administrador = a.rut_administrador
            WHERE u.prepara_nfc = 0
                AND a.nfc_administrador != :nfc
                AND u.codigo_nfc_usuario = :nfc
                AND u.eliminado = 0
            LIMIT 1),
            
            (SELECT 'Login2:' 
            FROM Usuario u
            JOIN administrador a ON u.rut_administrador = a.rut_administrador
            WHERE u.prepara_nfc = 0
                AND a.nfc_administrador = :nfc
                AND u.codigo_nfc_usuario IS NOT NULL
                AND u.eliminado = 0
            LIMIT 1),
            
            (SELECT 'Login3:' 
            FROM Usuario u
            JOIN administrador a ON u.rut_administrador = a.rut_administrador
            WHERE u.prepara_nfc = 1
                AND a.nfc_administrador != :nfc
                AND u.codigo_nfc_usuario = :nfc
                AND u.eliminado = 0
            LIMIT 1)
        ) as resultado;";
    return $sqlSelect;
    } catch (Exception $e) {
        // Manejar cualquier error que pueda ocurrir al leer el archivo
        // Puedes ajustar el manejo de errores según tus necesidades
        die('Error al obtener la sentencia SQL: ' . $e->getMessage());

// function buscarNfc($nfc) {
//     try {
//         $sqlSelect = "SELECT
//         CASE
//             WHEN cl.crearUsuario = 0 AND EXISTS (SELECT 1 FROM Usuario WHERE codigo_nfc_usuario = :nfc AND eliminado = 0) THEN 'Logear'
//             WHEN cl.crearUsuario = 1 AND :nfc IN (SELECT nfc_administrador FROM administrador) THEN 
//                 CASE 
//                     WHEN EXISTS (SELECT 1 FROM Usuario WHERE codigo_nfc_usuario = :nfc AND prepara_nfc = true AND eliminado = 0) THEN
//                         CONCAT('Create ', :nfc) -- Retorna 'Create' seguido del valor de :nfc
//                     ELSE 'False'
//                 END
//             WHEN cl.crearUsuario = 1 AND EXISTS (SELECT 1 FROM Usuario WHERE codigo_nfc_usuario = :nfc AND prepara_nfc = true AND eliminado = 0) THEN
//                 'Update'
//             WHEN cl.crearUsuario = 1 AND EXISTS (SELECT 1 FROM Usuario WHERE codigo_nfc_usuario = :nfc AND eliminado = 0) THEN 'Logear'
//             WHEN cl.crearUsuario = 1 AND NOT EXISTS (SELECT 1 FROM Usuario WHERE codigo_nfc_usuario = :nfc) THEN 'False'
//             ELSE 'False'
//         END AS resultado
//     FROM
//         controladorLogin cl;";
//         return $sqlSelect;
//     } catch (Exception $e) {
//         // Manejar cualquier error que pueda ocurrir al leer el archivo
//         // Puedes ajustar el manejo de errores según tus necesidades
//         die('Error al obtener la sentencia SQL: ' . $e->getMessage());
    }
}

////////////////////////////////////////
///////ENDPOINT/////////CREATE//////////
////////////////////////////////////////

//FUNCION QUE SE EJECUTA DESDE EL ENDPOINT PARA SABER QUE SE LEYO LA NFC DEL ADMIN PARA !!!CREAR!!!
function administradorCreaNfc(){
    try {
        $sqlUpdate = "UPDATE Usuario u 
        JOIN administrador a ON a.rut_administrador = u.rut_administrador 
        SET u.admin_prepara_nfc = 
            CASE 
                WHEN u.prepara_Nfc = TRUE AND a.nfc_administrador = :nfcAdmin THEN TRUE
                WHEN u.prepara_Nfc = FALSE AND a.nfc_administrador = :nfcAdmin THEN FALSE
            END
        WHERE (u.prepara_Nfc = TRUE AND a.nfc_administrador = :nfcAdmin)
           OR (u.prepara_Nfc = FALSE AND a.nfc_administrador = :nfcAdmin);";
        return $sqlUpdate;
    } catch (Exception $e) {
        // Handle any error that may occur while generating the SQL statement
        // You can adjust error handling based on your needs
        die('Error getting SQL statement: ' . $e->getMessage());
    }
}

//FUNCION QUE SE EJECUTA DESDE ENDPOINT PARA SABER QUE SE LEYO LA NFC DEL NUEVO USUARIO 

function createUserNfc() {
    try {
        $sqlUpdate = "UPDATE Usuario u 
                      JOIN administrador a ON a.rut_administrador = u.rut_administrador 
                      SET u.codigo_nfc_usuario = :nfcNewUser 
                      WHERE a.nfc_administrador = :nfc_administrador 
                      AND u.prepara_Nfc = TRUE
                      AND u.admin_prepara_nfc = TRUE";
        return $sqlUpdate;
    } catch (Exception $e) {
        // Handle any error that may occur while generating the SQL statement
        // You can adjust error handling based on your needs
        die('Error getting SQL statement: ' . $e->getMessage());
    }
}

////////////////////////////////////////////////
/////ENDOPOINT/////////////UPDATE//////////////
//////////////////////////////////////////////

function administradorUpdateNfc(){
    try {
        $sqlUpdate = "UPDATE Usuario u 
        JOIN administrador a ON a.rut_administrador = u.rut_administrador 
        SET u.admin_prepara_nfc_update = 
            CASE 
                WHEN u.prepara_Nfc = TRUE AND a.nfc_administrador = :nfcAdmin THEN TRUE
                WHEN u.prepara_Nfc = FALSE AND a.nfc_administrador = :nfcAdmin THEN FALSE
            END
        WHERE (u.prepara_Nfc = TRUE AND a.nfc_administrador = :nfcAdmin)
           OR (u.prepara_Nfc = FALSE AND a.nfc_administrador = :nfcAdmin);";
        return $sqlUpdate;
    } catch (Exception $e) {
        // Handle any error that may occur while generating the SQL statement
        // You can adjust error handling based on your needs
        die('Error getting SQL statement: ' . $e->getMessage());
    }
}
// FUNCION QUE UPDATEA NFC

function UpdateUserNfc() {
    try {
        $sqlUpdate = "UPDATE Usuario u 
                      JOIN administrador a ON a.rut_administrador = u.rut_administrador 
                      SET u.codigo_nfc_usuario = :nfcUpdateUser,
                          u.updated_at_usuario = :new_time_update
                      WHERE a.nfc_administrador = :nfc_administrador 
                      AND u.prepara_Nfc = TRUE
                      AND u.admin_prepara_nfc_update = TRUE;";
        return $sqlUpdate;
    } catch (Exception $e) {
        // Handle any error that may occur while generating the SQL statement
        // You can adjust error handling based on your needs
        die('Error getting SQL statement: ' . $e->getMessage());
    }
}

////////////////////////////////////////////////
//////CLIENTE-SERVIDOR//////////CREATE//////////
////////////////////////////////////////////////

//FUNCION QUE LEE DESDE EL CLIENTE WEB SI HA INGREASDO LA NFC DEL ADMINISTRADOR
function leerNfcAdm(){
    try {
        $loginAdministrador = "SELECT 
        CASE 
          WHEN EXISTS (SELECT 1 FROM Usuario WHERE admin_prepara_nfc = TRUE
                                                AND (codigo_nfc_usuario IS NULL OR codigo_nfc_usuario = '') 
                                                AND rut_usuario = :rut_usuario) THEN 1 
          WHEN EXISTS (SELECT 1 FROM Usuario WHERE admin_prepara_nfc_update = TRUE
                                                AND (codigo_nfc_usuario IS NOT NULL OR codigo_nfc_usuario != '')
                                                AND rut_usuario = :rut_usuario) THEN 2
          ELSE 0 
        END AS resultado;";
    return $loginAdministrador;
    }catch (Exception $e) {
        // Manejar cualquier error que pueda ocurrir al leer el archivo
        // Puedes ajustar el manejo de errores según tus necesidades
        die('Error al obtener la sentencia SQL: ' . $e->getMessage());
    }
}

//FUNCION QUE LEE DESDE EL CLIENTE WEB SI HA INGRESADO LA NFC DEL NUEVO USUARIO
function leerNfcNuevaIngresada(){
    try {
        $sqlUpdate = "SELECT 
            CASE 
                WHEN EXISTS (SELECT 1 FROM Usuario WHERE codigo_nfc_usuario IS NOT NULL
                                                AND rut_usuario = :rut_usuario) THEN 1 
                ELSE 0 
            END AS resultado";
        return $sqlUpdate;
    } catch (Exception $e) {
        // Handle any error that may occur while reading the file
        // You can adjust error handling based on your needs
        die('Error getting SQL statement: ' . $e->getMessage());
    }
}

function obtenerTimeNfc(){
    try {
        $sqlUpdate = "SELECT updated_at_usuario from Usuario Where rut_usuario = :rut_usuario
                                                            AND rut_administrador = :rut_administrador
                                                            AND prepara_nfc = TRUE
                                                            AND admin_prepara_nfc_update = TRUE";
        return $sqlUpdate;
    } catch (Exception $e) {
        // Handle any error that may occur while reading the file
        // You can adjust error handling based on your needs
        die('Error getting SQL statement: ' . $e->getMessage());
    }

}

//FUNCION QUE LEE DESDE EL CLIENTE WEB SI HA INGRESADO LA NFC PARA UPDATEAR AL USUARIO
function leerNfcNuevaIngresadaUpdate(){
    try {
        $sqlUpdate = "SELECT updated_at_usuario from Usuario Where rut_usuario = :rut_usuario
                                                            AND rut_administrador = :rut_administrador
                                                            AND prepara_nfc = TRUE
                                                            AND admin_prepara_nfc_update = TRUE";
        return $sqlUpdate;
    } catch (Exception $e) {
        // Handle any error that may occur while reading the file
        // You can adjust error handling based on your needs
        die('Error getting SQL statement: ' . $e->getMessage());
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
