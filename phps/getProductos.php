<?php
// Incluir el archivo donde se encuentra la clase DBManager
require_once 'DBManager.php';

// Crear una instancia de DBManager
$dbManager = new DBManager();

// Llamar al método para obtener los productos
$productos = $dbManager->getProductos();

// Verificar si la consulta retornó un error o los productos
if (isset($productos['error'])) {
    // Si hay un error, devolver el mensaje en formato JSON
    echo json_encode(["error" => $productos['error']]);
} else {
    // Si se obtienen los productos, devolverlos en formato JSON
    echo json_encode($productos);
}
?>
