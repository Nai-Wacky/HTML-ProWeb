<?php

require "DBManager.php";

if (isset($_POST['nombre']) && isset($_POST['correo']) && isset($_POST['password']) && isset($_POST['numerotel'])) {

    $db = new DBManager();

    $c = new Usuario();

    $c->id_usuario = isset($_POST['id_usuario']) ? $_POST['id_usuario'] : 0;
    $c->nombre = $_POST['nombre'];
    $c->correo = $_POST['correo'];
    $c->password = $_POST['password'];
    $c->numerotel = $_POST['numerotel'];

    echo $db->addUser($c);
} else {
    echo "Error, no se pueden agregar calificaciones vacios";
    echo $_POST['nombre'];
    echo $_POST['correo'];
    echo $_POST['password'];
    echo $_POST['numerotel'];
}
