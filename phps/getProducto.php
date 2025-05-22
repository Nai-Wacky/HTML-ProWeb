<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/debug.log');

require_once 'DBManager.php';

try {
    error_log("=== Inicio de getProducto.php ===");
    
    if (!isset($_GET['id'])) {
        throw new Exception('ID de producto no especificado');
    }

    $idProducto = $_GET['id'];
    error_log("Buscando producto con ID: " . $idProducto);
    
    $db = new DBManager();
    $link = $db->open();
    
    if (!$link) {
        error_log("Error: No hay conexión a la base de datos");
        throw new Exception("Error de conexión a la base de datos");
    }
    
    error_log("Conexión a la base de datos establecida");

    // Consulta principal
    $sql = "SELECT p.*, GROUP_CONCAT(i.ruta) as imagenes 
            FROM productos p 
            LEFT JOIN images i ON p.id_producto = i.idproducto 
            WHERE p.id_producto = ?
            GROUP BY p.id_producto";
            
    error_log("SQL Query: " . $sql);
    
    $stmt = mysqli_prepare($link, $sql);
    if (!$stmt) {
        error_log("Error en prepare: " . mysqli_error($link));
        throw new Exception("Error al preparar la consulta");
    }

    mysqli_stmt_bind_param($stmt, "i", $idProducto);
    
    if (!mysqli_stmt_execute($stmt)) {
        error_log("Error en execute: " . mysqli_error($link));
        throw new Exception("Error al ejecutar la consulta");
    }
    
    $resultado = mysqli_stmt_get_result($stmt);
    
    if ($producto = mysqli_fetch_assoc($resultado)) {
        error_log("Producto encontrado: " . json_encode($producto));
        
        // Convertir el string de imágenes en un array
        $producto['imagenes'] = $producto['imagenes'] ? explode(',', $producto['imagenes']) : [];
        
        // Si no hay imágenes, usar una imagen por defecto
        if (empty($producto['imagenes'])) {
            $producto['imagenes'] = ['images/default-product.jpg'];
        }

        // Asegurar que todos los campos necesarios existan
        $producto['nombre'] = $producto['nombre'] ?? 'Producto sin nombre';
        $producto['Marca'] = $producto['Marca'] ?? 'Marca no especificada';
        $producto['precio'] = $producto['precio'] ?? '0.00';
        $producto['Categoria'] = $producto['Categoria'] ?? 'Categoría no especificada';
        $producto['descripcion'] = $producto['descripcion'] ?? 'Sin descripción disponible';
        
        error_log("Enviando respuesta: " . json_encode($producto));
        echo json_encode($producto);
    } else {
        throw new Exception("No se encontró el producto con ID: " . $idProducto);
    }

} catch (Exception $e) {
    $error = ['error' => $e->getMessage()];
    error_log("Error en getProducto.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode($error);
}

if (isset($db)) {
    $db->close($link);
}
error_log("=== Fin de getProducto.php ===");
?> 