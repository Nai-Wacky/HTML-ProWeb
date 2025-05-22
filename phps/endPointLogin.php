<?php
session_start();
require "DBManager.php";

if (isset($_POST['corr']) && isset($_POST['password'])) {
    $db = new DBManager();

    $resultado = $db->login($_POST['corr'], $_POST['password']);

    if (!isset($resultado['error'])) {
        // Si el login fue exitoso, guardar datos en la sesión
        $_SESSION['idCliente'] = $resultado['id_usuario'];
        $_SESSION['nombre'] = $resultado['nombre'];
        $_SESSION['correo'] = $resultado['correo'];
    }

    echo json_encode($resultado);
} else {
    echo json_encode(["error" => "Error, el correo o la contraseña no coincide"]);
}
