<?php

require "DBManager.php";

if (isset($_POST['corr']) && isset($_POST['password'])) {
    $db = new DBManager();

    $resultado = $db->login($_POST['corr'], $_POST['password']);

    echo json_encode($resultado);
} else {
    echo json_encode(["error" => "Error, el correo o la contraseña no coincide"]);
}
