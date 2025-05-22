<?php
// Asegurarnos de que PHP no envíe HTML en caso de error
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Establecer el tipo de contenido como JSON
header('Content-Type: application/json');

try {
    // Incluir el archivo donde se encuentra la clase DBManager
    require_once 'DBManager.php';

    // Verificar que se recibió el término de búsqueda
    if (!isset($_GET['query']) || empty($_GET['query'])) {
        throw new Exception('No se proporcionó un término de búsqueda');
    }

    // Obtener y sanitizar el término de búsqueda
    $searchTerm = trim($_GET['query']);
    
    // Verificar la longitud del término de búsqueda
    if (strlen($searchTerm) > 30) {
        throw new Exception('El término de búsqueda es demasiado largo');
    }

    // Crear una instancia de DBManager
    $dbManager = new DBManager();
    $link = $dbManager->open();

    // Preparar el término de búsqueda para SQL
    $searchTerm = '%' . mysqli_real_escape_string($link, $searchTerm) . '%';

    // Consulta SQL para buscar productos
    $sql = "SELECT p.*, GROUP_CONCAT(i.ruta) as imagenes 
            FROM productos p 
            LEFT JOIN images i ON p.id_producto = i.idproducto 
            WHERE p.nombre LIKE ? 
               OR p.descripcion LIKE ? 
               OR p.Marca LIKE ? 
               OR p.Categoria LIKE ?
            GROUP BY p.id_producto";

    // Preparar la consulta
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $productos = [];
    while ($row = mysqli_fetch_assoc($result)) {
        // Convertir el string de imágenes en un array
        $row['imagenes'] = $row['imagenes'] ? explode(',', $row['imagenes']) : [];
        // Si no hay imágenes, usar una imagen por defecto
        if (empty($row['imagenes'])) {
            $row['imagenes'] = ['images/default-product.jpg'];
        }
        $productos[] = $row;
    }

    $dbManager->close($link);
    
    // Devolver los resultados
    echo json_encode([
        'success' => true,
        'productos' => $productos,
        'total' => count($productos)
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?> 