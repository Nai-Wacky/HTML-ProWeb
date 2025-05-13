<?php

require "DBManager.php";

if (isset($_POST['id_usuario']) && isset($_POST['password'])) {
    $id_usuario = $_POST['id_usuario'];
    $password = $_POST['password'];

    $db = new DBManager();
    $resultado = $db->editPassword($id_usuario, $password);

    if ($resultado) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => "Error al registrar usuario"]);
    }
} else {
    echo json_encode(["error" => "No se pudo modificar"]);
}
