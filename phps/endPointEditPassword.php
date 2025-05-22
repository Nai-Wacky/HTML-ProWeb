<?php

require "DBManager.php";

if (isset($_POST['id_usuario']) && isset($_POST['password']) && isset($_POST['password2'])) {
    $id_usuario = $_POST['id_usuario'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $password2 = password_hash($_POST['password2'], PASSWORD_DEFAULT);

    $db = new DBManager();
    $resultado = $db->editPassword($id_usuario, $password, $password2);

    if ($resultado) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => "Los datos no coinciden"]);
    }
} else {
    echo json_encode(["error" => "Hacen falta datos"]);
}
