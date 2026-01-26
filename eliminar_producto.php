<?php
/**
 * BORRADO LÓGICO
 * Cambia el estado a 'inactivo' en lugar de usar DELETE.
 */
session_start();
require_once 'includes/conexion.php';

// 1. SEGURIDAD: Verificar sesión y que se reciba un ID válido
if (isset($_GET['id']) && isset($_SESSION['id_usuario'])) {
    
    $id_prod = intval($_GET['id']);
    $id_user = $_SESSION['id_usuario'];

    // 2. EJECUCIÓN: Solo desactiva si el producto pertenece al vendedor logueado
    // Usamos sentencias preparadas para evitar Inyección SQL
    $sql = "UPDATE Producto SET estado = 'inactivo' 
            WHERE id_producto = ? AND id_vendedor = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_prod, $id_user);
    
    if ($stmt->execute()) {
        // 3. ÉXITO: Redirigimos al panel con mensaje de confirmación
        header("Location: admin_productos.php?msg=deleted");
        exit();
    } else {
        // ERROR: En caso de fallo en la base de datos
        die("Error en la desincronización: " . $conn->error);
    }
} else {
    // Si no hay ID o sesión, devolvemos al panel sin hacer nada
    header("Location: admin_productos.php");
    exit();
}

// Cerramos la conexión
$conn->close();
?>