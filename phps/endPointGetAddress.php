<?php

header('Content-Type: application/json');

require "DBManager.php";

if (isset($_POST['id_usuario'])) {
    $id_usuario = $_POST['id_usuario'];

    $db = new DBManager();
    $resultado = $db->getDirecciones($id_usuario);

    if ($resultado) {
        echo json_encode($resultado); // $resultado debe ser un array
    } else {
        echo json_encode(["error" => "Error al obtener las direcciones"]);
    }
} else {
    echo json_encode(["error" => "No se pudo obtener las direcciones"]);
}
