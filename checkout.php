<?php
session_start();
require_once 'includes/conexion.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = intval($_SESSION['id_usuario']);

// 1. Obtener el carrito
// Usamos comillas invertidas `` para asegurar que MySQL lea el nombre exacto
$query_carrito = "SELECT cp.id_producto, cp.cantidad, p.precio, p.stock 
                  FROM `carrito_producto` cp 
                  JOIN `producto` p ON cp.id_producto = p.id_producto 
                  WHERE cp.id_carrito = (SELECT id_carrito FROM `carrito` WHERE id_usuario = $id_usuario)";

$resultado = $conn->query($query_carrito);

if ($resultado && $resultado->num_rows > 0) {
    $total = 0;
    $items = [];

    while ($row = $resultado->fetch_assoc()) {
        if ($row['stock'] < $row['cantidad']) {
            header("Location: carrito.php?error=no_stock");
            exit();
        }
        $total += $row['precio'] * $row['cantidad'];
        $items[] = $row;
    }

    // 2. Insertar compra (LÍNEA 46 - CORREGIDA)
    // Forzamos el uso de la tabla compra con comillas invertidas
    $sql_compra = "INSERT INTO `compra` (`id_usuario`, `fecha_compra`, `total`, `estado_pago`) 
                   VALUES ($id_usuario, CURDATE(), $total, 'pagado')";
    
    if ($conn->query($sql_compra)) {
        $id_compra = $conn->insert_id;

        foreach ($items as $item) {
            $id_p = intval($item['id_producto']);
            $cant = intval($item['cantidad']);
            $precio = floatval($item['precio']);

            // 3. Insertar detalle_compra
            $conn->query("INSERT INTO `detalle_compra` (`id_compra`, `id_producto`, `cantidad`, `precio_unitario`) 
                          VALUES ($id_compra, $id_p, $cant, $precio)");

            // 4. Actualizar stock
            $conn->query("UPDATE `producto` SET `stock` = `stock` - $cant WHERE `id_producto` = $id_p");
        }

        // 5. Vaciar carrito
        $conn->query("DELETE FROM `carrito_producto` WHERE `id_carrito` = (SELECT `id_carrito` FROM `carrito` WHERE `id_usuario` = $id_usuario)");

        header("Location: perfil.php?status=success");
        exit();
    } else {
        // Esto te dirá el error real si falla de nuevo
        die("Error de MySQL: " . $conn->error);
    }
} else {
    header("Location: carrito.php?error=vacio");
    exit();
}
?>