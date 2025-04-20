<?php

require "DBManager.php";

if (isset($_POST['nombre']) && isset($_POST['correo']) && isset($_POST['password']) && isset($_POST['numerotel'])) {

    $db = new DBManager();

    $c = new Usuario();

    $c->id_usuario = isset($_POST['id_usuario']) ? $_POST['id_usuario'] : 0;
    $c->nombre = $_POST['nombre'];
    $c->correo = $_POST['correo'];
    $c->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $c->numerotel = $_POST['numerotel'];

    $resultado = $db->addUser($c);

    if ($resultado) {
        // Registro exitoso, redirigir
        header("Location: ../AnyJobHome.html");
        exit;
    } else {
        echo "Error al registrar usuario.";
    }

    header("Location: ../AnyJobHome.html");
    exit;
} else {
    echo "Error: Faltan campos obligatorios";
}
