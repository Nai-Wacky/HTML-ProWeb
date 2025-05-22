<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/debug.log');

$host = "localhost";
$user = "root";
$password = null;
$database = "dbanyjob";
$port = 3306;

$link = mysqli_connect($host, $user, $password, $database, $port);

if (!$link) {
    error_log("Error de conexión: " . mysqli_connect_error());
    die(json_encode(['error' => 'Error de conexión a la base de datos']));
}

mysqli_set_charset($link, "utf8");
?> 
<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

$host = "localhost";
$user = "root";
$password = "";
$database = "anyjob";

$link = mysqli_connect($host, $user, $password, $database);

if (!$link) {
    error_log("Error de conexión: " . mysqli_connect_error());
    die(json_encode(['error' => 'Error de conexión a la base de datos']));
}

mysqli_set_charset($link, "utf8");
?> 