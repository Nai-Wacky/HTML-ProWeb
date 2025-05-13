<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "DBManager.php";

if (isset($_POST['id_usuario']) && isset($_POST['estado']) && isset($_POST['municipio']) && isset($_POST['cod_postal']) && isset($_POST['calle']) && isset($_POST['colonia']) && isset($_POST['numero'])) {

    $db = new DBManager();

    $c = new Direccion();

    $c->id_direccion = isset($_POST['id_direccion']) ? $_POST['id_direccion'] : 0;
    $c->id_usuario = $_POST['id_usuario'];
    $c->estado = $_POST['estado'];
    $c->municipio = $_POST['municipio'];
    $c->cod_postal = $_POST['cod_postal'];
    $c->calle = $_POST['calle'];
    $c->colonia = $_POST['colonia'];
    $c->numero = $_POST['numero'];

    echo $db->addAddress($c);
} else {
    echo json_encode(["error" => "Faltan campos obligatorios"]);
}
