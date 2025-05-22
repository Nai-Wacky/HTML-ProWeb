<?php
// Asegurarnos de que PHP no envíe HTML en caso de error
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Establecer el tipo de contenido como JSON
header('Content-Type: application/json');

try {
    // Incluir el archivo donde se encuentra la clase DBManager
    require_once 'DBManager.php';

    // Crear una instancia de DBManager
    $dbManager = new DBManager();
    $link = $dbManager->open();

    if (!$link) {
        throw new Exception("Error de conexión a la base de datos");
    }

    // Consulta SQL para obtener productos con sus imágenes
    $sql = "SELECT p.*, GROUP_CONCAT(i.ruta) as imagenes 
            FROM productos p 
            LEFT JOIN images i ON p.id_producto = i.idproducto 
            GROUP BY p.id_producto";

    $result = mysqli_query($link, $sql);

    if (!$result) {
        throw new Exception(mysqli_error($link));
    }

    $productos = [];
    while ($row = mysqli_fetch_assoc($result)) {
        // Convertir el string de imágenes en un array
        $row['imagenes'] = $row['imagenes'] ? explode(',', $row['imagenes']) : [];
        
        // Si no hay imágenes, usar una imagen por defecto
        if (empty($row['imagenes'])) {
            $row['imagenes'] = ['images/default-product.jpg'];
        }

        // Asegurar que todos los campos necesarios existan
        $row['nombre'] = $row['nombre'] ?? 'Producto sin nombre';
        $row['Marca'] = $row['Marca'] ?? 'Marca no especificada';
        $row['precio'] = $row['precio'] ?? '0.00';
        $row['Categoria'] = $row['Categoria'] ?? 'Categoría no especificada';
        $row['descripcion'] = $row['descripcion'] ?? 'Sin descripción disponible';
        
        $productos[] = $row;
    }

    mysqli_free_result($result);
    $dbManager->close($link);

    if (empty($productos)) {
        echo json_encode([]);
    } else {
        echo json_encode($productos);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error al obtener los productos: " . $e->getMessage()]);
}
?>
