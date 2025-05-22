-- Migrar las imágenes existentes como primera imagen (orden = 0)
INSERT INTO imagenes_producto (id_producto, ruta_imagen, orden)
SELECT id_producto, ligaIMG, 0
FROM productos
WHERE ligaIMG IS NOT NULL AND ligaIMG != '';

-- Ahora podemos agregar las columnas adicionales para las nuevas imágenes
-- Por ejemplo, para el producto con ID 1, podríamos hacer:
/*
INSERT INTO imagenes_producto (id_producto, ruta_imagen, orden) VALUES
(1, 'ruta/imagen2.jpg', 1),
(1, 'ruta/imagen3.jpg', 2),
(1, 'ruta/imagen4.jpg', 3);
*/

-- Una vez que hayamos migrado todas las imágenes y estemos seguros de que todo está bien,
-- podemos eliminar la columna ligaIMG de la tabla productos:
-- ALTER TABLE productos DROP COLUMN ligaIMG; 