<?php
session_start();
require_once 'includes/conexion.php';

// 1. SEGURIDAD: Solo usuarios logueados y método POST
if (!isset($_SESSION['id_usuario']) || $_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['id_usuario'];
$id_prod = intval($_POST['id_producto']);
$nombre = trim($_POST['nombre']);
$id_cat = intval($_POST['id_categoria']);
$precio = floatval($_POST['precio']);
$stock = intval($_POST['stock']);

// 2. VERIFICACIÓN DE PERTENENCIA
// Antes de hacer nada, confirmamos que el producto realmente le pertenece a este usuario
$check_sql = "SELECT imagen FROM Producto WHERE id_producto = ? AND id_vendedor = ?";
$stmt_check = $conn->prepare($check_sql);
$stmt_check->bind_param("ii", $id_prod, $id_user);
$stmt_check->execute();
$res_check = $stmt_check->get_result();

if ($res_check->num_rows === 0) {
    die("Error: No tienes permisos para modificar este artículo.");
}

$datos_actuales = $res_check->fetch_assoc();
$nombre_imagen = $datos_actuales['imagen']; // Por defecto dejamos la imagen que ya tenía

// 3. PROCESAMIENTO DE NUEVA IMAGEN (Si se ha subido una)
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
    $dir = "img/productos/";
    $info = pathinfo($_FILES['foto']['name']);
    $ext = strtolower($info['extension']);
    $ext_permitidas = ['jpg', 'jpeg', 'png', 'webp'];

    if (in_array($ext, $ext_permitidas)) {
        // Validación de contenido real
        if (getimagesize($_FILES['foto']['tmp_name']) !== false) {

            // Si el producto tenía una imagen anterior (y no es la default), la borramos para no llenar el server de basura
            if ($nombre_imagen != 'default.jpg' && file_exists($dir . $nombre_imagen)) {
                unlink($dir . $nombre_imagen);
            }

            // Generar nuevo nombre único
            $nombre_limpio = substr(preg_replace("/[^a-zA-Z0-9]/", "", $nombre), 0, 15);
            $nombre_imagen = "prod_u" . $id_user . "_" . $nombre_limpio . "_" . time() . "." . $ext;

            move_uploaded_file($_FILES['foto']['tmp_name'], $dir . $nombre_imagen);
        }
    }
}

// 4. ACTUALIZACIÓN EN LA BASE DE DATOS
$sql_update = "UPDATE Producto 
               SET nombre = ?, id_categoria = ?, precio = ?, stock = ?, imagen = ? 
               WHERE id_producto = ? AND id_vendedor = ?";

$stmt_upd = $conn->prepare($sql_update);
// Tipos: s=string, i=int, d=double
$stmt_upd->bind_param("sidiisi", $nombre, $id_cat, $precio, $stock, $nombre_imagen, $id_prod, $id_user);

if ($stmt_upd->execute()) {
    header("Location: admin_productos.php?msg=updated");
} else {
    echo "Error al actualizar: " . $conn->error;
}

$stmt_upd->close();
$conn->close();
