<?php
header('Content-Type: application/json');

include 'DBManager.php';

session_start();

// Verificar si hay un usuario logueado
if (!isset($_SESSION['idCliente'])) {
    echo json_encode(['error' => 'Debe iniciar sesión para agregar productos al carrito']);
    exit;
}

// Obtener el ID del producto del POST
$idProducto = isset($_POST['idProducto']) ? intval($_POST['idProducto']) : null;
$idCliente = intval($_SESSION['idCliente']);

if (!$idProducto) {
    echo json_encode(['error' => 'ID de producto no proporcionado']);
    exit;
}

try {
    $db = new DBManager();
    $resultado = $db->agregarAlCarrito($idProducto, $idCliente);
    
    if (isset($resultado['success'])) {
        echo json_encode(['success' => 'Producto agregado al carrito exitosamente']);
    } else {
        echo json_encode($resultado); // Devuelve el error específico
    }
    
} catch (Exception $e) {
    echo json_encode(['error' => 'Error en el servidor: ' . $e->getMessage()]);
}
?> 