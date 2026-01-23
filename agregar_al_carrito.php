<?php
session_start();
require_once 'includes/conexion.php';

if (!isset($_SESSION['id_usuario']) || !isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$id_producto = intval($_GET['id']);

// 1. Verificar si el usuario ya tiene un carrito en la tabla 'Carrito'
$check_carrito = $conn->query("SELECT id_carrito FROM Carrito WHERE id_usuario = $id_usuario");

if ($check_carrito->num_rows == 0) {
    // No tiene carrito, lo creamos
    $conn->query("INSERT INTO Carrito (id_usuario, total) VALUES ($id_usuario, 0)");
    $id_carrito = $conn->insert_id;
} else {
    $row = $check_carrito->fetch_assoc();
    $id_carrito = $row['id_carrito'];
}

// 2. Verificar si el producto ya está en 'Carrito_Producto'
$check_prod = $conn->query("SELECT cantidad FROM Carrito_Producto WHERE id_carrito = $id_carrito AND id_producto = $id_producto");

if ($check_prod->num_rows > 0) {
    // Ya está, aumentamos cantidad
    $conn->query("UPDATE Carrito_Producto SET cantidad = cantidad + 1 WHERE id_carrito = $id_carrito AND id_producto = $id_producto");
} else {
    // No está, lo insertamos
    $conn->query("INSERT INTO Carrito_Producto (id_carrito, id_producto, cantidad) VALUES ($id_carrito, $id_producto, 1)");
}

// Redirigir de vuelta a la tienda
header("Location: index.php");
exit();