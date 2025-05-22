<?php
header('Content-Type: application/json');
session_start();

// Verificar si hay un usuario logueado
if (!isset($_SESSION['idCliente'])) {
    echo json_encode(['error' => 'Debe iniciar sesión para ver el carrito']);
    exit;
}

require_once 'DBManager.php';

try {
    $db = new DBManager();
    $link = $db->open();

    if (!$link) {
        throw new Exception("Error de conexión a la base de datos");
    }

    // Obtener los productos del carrito junto con sus imágenes
    $sql = "SELECT p.*, GROUP_CONCAT(i.ruta) as imagenes 
            FROM carrito c 
            INNER JOIN productos p ON c.idproducto = p.id_producto 
            LEFT JOIN images i ON p.id_producto = i.idproducto
            WHERE c.idcliente = ?
            GROUP BY p.id_producto";
            
    $stmt = mysqli_prepare($link, $sql);
    if (!$stmt) {
        throw new Exception("Error al preparar la consulta: " . mysqli_error($link));
    }

    mysqli_stmt_bind_param($stmt, "i", $_SESSION['idCliente']);
    
    // Log para depuración
    error_log("Consultando carrito para usuario ID: " . $_SESSION['idCliente']);
    
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Error al ejecutar la consulta: " . mysqli_stmt_error($stmt));
    }

    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        throw new Exception("Error al obtener los resultados: " . mysqli_error($link));
    }

    $productos = [];
    while ($row = mysqli_fetch_assoc($result)) {
        // Convertir el string de imágenes en un array
        $row['imagenes'] = $row['imagenes'] ? explode(',', $row['imagenes']) : [];
        // Si no hay imágenes, usar la imagen por defecto
        if (empty($row['imagenes'])) {
            $row['imagenes'] = ['images/default-product.jpg'];
        }
        $productos[] = $row;
    }

    // Log para depuración
    error_log("Productos encontrados: " . count($productos));

    mysqli_stmt_close($stmt);
    $db->close($link);
    
    echo json_encode($productos);

} catch (Exception $e) {
    error_log("Error en getCarrito.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener los productos del carrito: ' . $e->getMessage()]);
} 