<?php

require "DBManager.php";

if (isset($_POST['id_usuario']) && isset($_POST['password'])) {
    $id_usuario = $_POST['id_usuario'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

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
