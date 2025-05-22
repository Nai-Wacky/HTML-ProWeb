<?php

require "DBManager.php";

if (isset($_POST['id_usuario']) && isset($_POST['telefono'])) {
    $id_usuario = $_POST['id_usuario'];
    $telefono = $_POST['telefono'];

    $db = new DBManager();
    $resultado = $db->editTelefono($id_usuario, $telefono);

    if ($resultado) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => "Error al modificar el telefono"]);
    }
} else {
    echo json_encode(["error" => "No se pudo modificar el telefono"]);
}
