<?php
session_start();
require_once 'includes/conexion.php';

if (isset($_GET['id']) && isset($_SESSION['id_usuario'])) {
    $id_prod = intval($_GET['id']);
    $id_user = $_SESSION['id_usuario'];

    // Solo borra si el ID del producto COINCIDE con el ID del vendedor logueado
    $sql = "UPDATE Producto SET estado = 'inactivo' 
            WHERE id_producto = ? AND id_vendedor = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_prod, $id_user);
    
    if ($stmt->execute()) {
        header("Location: admin_productos.php?msg=deleted");
    }
}