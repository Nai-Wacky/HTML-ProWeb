CREATE TABLE IF NOT EXISTS imagenes_producto (
    id_imagen INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    ruta_imagen VARCHAR(255) NOT NULL,
    orden INT NOT NULL DEFAULT 0,
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto) ON DELETE CASCADE,
    INDEX idx_producto (id_producto),
    UNIQUE KEY unique_orden_por_producto (id_producto, orden)
); 