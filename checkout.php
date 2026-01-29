<?php
session_start();
require_once 'includes/conexion.php';

// Seguridad: Solo usuarios logueados
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// 1. Obtener el carrito actual
$query_carrito = "SELECT cp.id_producto, cp.cantidad, p.precio, p.stock 
                  FROM Carrito_Producto cp 
                  JOIN Producto p ON cp.id_producto = p.id_producto 
                  WHERE cp.id_carrito = (SELECT id_carrito FROM Carrito WHERE id_usuario = $id_usuario)";
$resultado = $conn->query($query_carrito);

if ($resultado->num_rows > 0) {
    $total = 0;
    $items = [];
    while ($row = $resultado->fetch_assoc()) {
        // Validación de Stock según la Guía Técnica
        if ($row['stock'] < $row['cantidad']) {
            die("Error: Stock insuficiente para uno de los productos.");
        }
        $total += $row['precio'] * $row['cantidad'];
        $items[] = $row;
    }

    // 2. Insertar en tabla Compra
    $sql_compra = "INSERT INTO Compra (id_usuario, fecha_compra, total, estado_pago) 
                   VALUES ($id_usuario, NOW(), $total, 'pagado')";
    
    if ($conn->query($sql_compra)) {
        $id_compra = $conn->insert_id;

        foreach ($items as $item) {
            $id_p = $item['id_producto'];
            $cant = $item['cantidad'];
            $precio = $item['precio'];

            // 3. Insertar Detalle
            $conn->query("INSERT INTO Detalle_compra (id_compra, id_producto, cantidad, precio_unitario) 
                          VALUES ($id_compra, $id_p, $cant, $precio)");

            // 4. Actualizar Stock
            $conn->query("UPDATE Producto SET stock = stock - $cant WHERE id_producto = $id_p");
        }

        // 5. Vaciar Carrito (Limpieza)
        $conn->query("DELETE FROM Carrito_Producto WHERE id_carrito = (SELECT id_carrito FROM Carrito WHERE id_usuario = $id_usuario)");

        // Redirigir al éxito (Tu perfil con el historial actualizado)
        header("Location: perfil.php?status=success");
    }
} else {
    header("Location: carrito.php?error=vacio");
}
?>