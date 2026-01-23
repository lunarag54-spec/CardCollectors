<?php
require_once 'includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id_producto']);
    $nuevo_precio = floatval($_POST['precio']);
    $nuevo_stock = intval($_POST['stock']);

    // Verificamos que el precio sea mayor que 0 y el stock no sea negativo
    if ($nuevo_precio <= 0 || $nuevo_stock < 0) {
        // Redirigimos de vuelta al formulario de edición con un mensaje de error
        // Pasamos el ID para que pueda volver a cargar el producto
        header("Location: editar_producto.php?id=$id&msg=err_data");
        exit();
    }

    // SQL para actualizar solo precio y stock como pide la subtarea
    $sql = "UPDATE Producto 
            SET precio = $nuevo_precio, stock = $nuevo_stock 
            WHERE id_producto = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: admin_productos.php?msg=updated");
        exit();
    } else {
        echo "Error al actualizar: " . $conn->error;
    }
}
?>