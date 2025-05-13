<?php

require "DBManager.php";

if (isset($_POST['id_usuario']) && isset($_POST['nombre'])) {
    $id_usuario = $_POST['id_usuario'];
    $nombre = $_POST['nombre'];

    $db = new DBManager();
    $resultado = $db->editNombre($id_usuario, $nombre);

    if ($resultado) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => "Error al registrar usuario"]);
    }
} else {
    echo json_encode(["error" => "No se pudo modificar"]);
}
