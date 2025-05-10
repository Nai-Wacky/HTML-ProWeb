<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "DBManager.php";

if (isset($_POST['nombre']) && isset($_POST['correo']) && isset($_POST['password']) && isset($_POST['numerotel'])) {

    $db = new DBManager();

    
    $link = $db->open();

    $correo = $_POST['correo'];

    // Verifica si el correo ya está registrado
    $checkSql = "SELECT id_usuario FROM usuarios WHERE correo = ?";
    $checkStmt = mysqli_prepare($link, $checkSql);
    mysqli_stmt_bind_param($checkStmt, "s", $correo);
    mysqli_stmt_execute($checkStmt);
    $result = mysqli_stmt_get_result($checkStmt);

    if (mysqli_num_rows($result) > 0) {
        echo json_encode(["error" => "El correo ya está registrado"]);
        $db->close($link);
        exit;
    }

    

    $c = new Usuario();

    $c->id_usuario = isset($_POST['id_usuario']) ? $_POST['id_usuario'] : 0;
    $c->nombre = $_POST['nombre'];
    $c->correo = $_POST['correo'];
    $c->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $c->numerotel = $_POST['numerotel'];

    $resultado = $db->addUser($c);
    $db->close($link);
    if ($resultado) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => "Error al registrar usuario"]);
    }
} else {
    echo json_encode(["error" => "Faltan campos obligatorios"]);
}
