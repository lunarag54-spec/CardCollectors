<?php
/**
 * En lugar de usar DELETE, usamos UPDATE para cambiar el estado.
 */

// 1. Incluimos la conexión (usando tu ruta actual)
require_once 'includes/conexion.php';

// 2. Verificamos que recibimos el ID por la URL
if (isset($_GET['id'])) {
    // Limpiamos el ID para asegurarnos de que sea un número entero
    $id = intval($_GET['id']);

    // 3. Ejecutamos el UPDATE (Borrado lógico)
    // Cambiamos el estado a 'inactivo' para que no salga en el SELECT de la tabla
    $sql = "UPDATE Producto SET estado = 'inactivo' WHERE id_producto = $id";

    if ($conn->query($sql) === TRUE) {
        // 4. Si funciona, redirigimos con el mensaje de 'deleted'
        header("Location: admin_productos.php?msg=deleted");
        exit();
    } else {
        // En caso de error de base de datos
        echo "Error al intentar desactivar el producto: " . $conn->error;
    }
} else {
    // Si alguien intenta entrar al archivo sin un ID, lo devolvemos al panel
    header("Location: admin_productos.php");
    exit();
}

// Cerramos la conexión
$conn->close();
?>