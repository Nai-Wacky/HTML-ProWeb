<?php
header('Content-Type: application/json');
session_start();

// Verificar si hay un usuario logueado
if (!isset($_SESSION['idCliente'])) {
    echo json_encode(['error' => 'Debe iniciar sesión para eliminar productos del carrito']);
    exit;
}

// Verificar si se recibió el ID del producto
if (!isset($_POST['idProducto'])) {
    echo json_encode(['error' => 'No se especificó el producto a eliminar']);
    exit;
}

require_once 'DBManager.php';

try {
    $db = new DBManager();
    $link = $db->open();

    $idProducto = intval($_POST['idProducto']);
    $idCliente = intval($_SESSION['idCliente']);

    // Eliminar el producto del carrito
    $sql = "DELETE FROM carrito WHERE idproducto = ? AND idcliente = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $idProducto, $idCliente);
    
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true]);
    } else {
        throw new Exception(mysqli_error($link));
    }

    $db->close($link);

} catch (Exception $e) {
    echo json_encode(['error' => 'Error al eliminar el producto: ' . $e->getMessage()]);
} 