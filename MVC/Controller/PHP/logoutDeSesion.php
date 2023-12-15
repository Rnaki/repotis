<?php

session_start();

if (isset($_SESSION['rut']))
{
    session_destroy();

    echo json_encode(['success' => true]);
} else
{
    echo json_encode(['success' => false, 'message' => 'No hay sesion para cerrar.']);
}

?>
