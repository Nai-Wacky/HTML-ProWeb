<?php

require "DBManager.php";

if (isset($_POST['id_direccion'])) {
    $id_direccion = $_POST['id_direccion'];

    $db = new DBManager();
    $resultado = $db->deleteDireccion($id_direccion);

    if ($resultado) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => "Error al registrar usuario"]);
    }
} else {
    echo json_encode(["error" => "No se pudo modificar"]);
}
