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

    // FASE DE VALIDACIÓN (Bucle While)
    while ($row = $resultado->fetch_assoc()) {
        if ($row['stock'] < $row['cantidad']) {
            // Si entra aquí, el exit() impide que se cree la compra
            header("Location: carrito.php?error=no_stock");
            exit();
        }
        $total += $row['precio'] * $row['cantidad'];
        $items[] = $row; // Guardamos los datos para usarlos luego
    }

    // FASE DE ESCRITURA (Si llegamos aquí, es que hay stock de todo)
    $sql_compra = "INSERT INTO Compra (id_usuario, fecha_compra, total, estado_pago) 
                   VALUES ($id_usuario, NOW(), $total, 'pagado')";
    
    if ($conn->query($sql_compra)) {
        $id_compra = $conn->insert_id;

        // Aquí es donde procesas cada producto uno por uno
        foreach ($items as $item) {
            $id_p = $item['id_producto'];
            $cant = $item['cantidad'];
            $precio = $item['precio'];

            // Insertar Detalle de la compra
            $conn->query("INSERT INTO Detalle_compra (id_compra, id_producto, cantidad, precio_unitario) 
                          VALUES ($id_compra, $id_p, $cant, $precio)");

            // Actualizar Stock restando la cantidad comprada
            $conn->query("UPDATE Producto SET stock = stock - $cant WHERE id_producto = $id_p");
        }

        // 5. Vaciar Carrito
        $conn->query("DELETE FROM Carrito_Producto WHERE id_carrito = (SELECT id_carrito FROM Carrito WHERE id_usuario = $id_usuario)");

        header("Location: perfil.php?status=success");
        exit();
    }
} else {
    header("Location: carrito.php?error=vacio");
    exit();
}
?>