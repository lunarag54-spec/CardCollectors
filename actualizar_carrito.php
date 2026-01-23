<?php
session_start();
require_once 'includes/conexion.php';

if (!isset($_SESSION['id_usuario']) || !isset($_GET['id']) || !isset($_GET['action'])) {
    header("Location: carrito.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$id_producto = intval($_GET['id']);
$action = $_GET['action'];

// 1. Obtener el ID del carrito del usuario
$res_cart = $conn->query("SELECT id_carrito FROM Carrito WHERE id_usuario = $id_usuario");
if ($res_cart->num_rows > 0) {
    $carrito = $res_cart->fetch_assoc();
    $id_carrito = $carrito['id_carrito'];

    switch ($action) {
        case 'add':
            // Sumar una unidad
            $conn->query("UPDATE Carrito_Producto SET cantidad = cantidad + 1 
                          WHERE id_carrito = $id_carrito AND id_producto = $id_producto");
            break;

        case 'remove':
            // Restar una unidad si es mayor a 1
            $conn->query("UPDATE Carrito_Producto SET cantidad = cantidad - 1 
                          WHERE id_carrito = $id_carrito AND id_producto = $id_producto AND cantidad > 1");
            break;

        case 'delete':
            // Eliminar el producto por completo del mazo
            $conn->query("DELETE FROM Carrito_Producto WHERE id_carrito = $id_carrito AND id_producto = $id_producto");
            break;
    }
}

// Volver al carrito para ver los cambios reflejados
header("Location: carrito.php");
exit();