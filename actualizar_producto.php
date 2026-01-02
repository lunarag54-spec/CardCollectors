<?php
require_once 'includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id_producto']);
    $nuevo_precio = floatval($_POST['precio']);
    $nuevo_stock = intval($_POST['stock']);

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