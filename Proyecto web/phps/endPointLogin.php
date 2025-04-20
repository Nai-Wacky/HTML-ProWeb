<?php

require "DBManager.php";

if (isset($_POST['corr']) && isset($_POST['pass'])) {
    $db = new DBManager();
    $resultado = $db->login($_POST['corr'], $_POST['pass']);
    echo json_encode($resultado);
} else {
    die('Error, se requiere el correo y pass');
}
