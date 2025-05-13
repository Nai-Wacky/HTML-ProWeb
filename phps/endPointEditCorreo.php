<?php

require "DBManager.php";

if (isset($_POST['id_usuario']) && isset($_POST['correo'])) {
    $id_usuario = $_POST['id_usuario'];
    $correo = $_POST['correo'];

    $db = new DBManager();
    $resultado = $db->editCorreo($id_usuario, $correo);

    if ($resultado) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => "Error al registrar usuario"]);
    }
} else {
    echo json_encode(["error" => "No se pudo modificar"]);
}
