<?php
session_start();
// 1. Verificación de seguridad: solo usuarios logueados
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

require_once 'includes/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = $_SESSION['id_usuario'];
    $nombre = $_POST['nombre'];
    $desc = $_POST['descripcion'];
    $cat = intval($_POST['id_categoria']);
    $precio = floatval($_POST['precio']);
    $stock = intval($_POST['stock']);

    // --- LÓGICA DE PROCESAMIENTO DE IMAGEN ---
    $nombre_archivo_final = "default.jpg"; // Valor por defecto

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        $directorio_subida = "img/productos/";
        
        // Crear carpeta si no existe
        if (!file_exists($directorio_subida)) {
            mkdir($directorio_subida, 0777, true);
        }

        // Obtener extensión y limpiar el nombre para el archivo
        $info_archivo = pathinfo($_FILES['foto']['name']);
        $extension = strtolower($info_archivo['extension']);
        
        // Solo permitimos extensiones seguras
        $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'webp'];
        
        if (in_array($extension, $extensiones_permitidas)) {
            // Renombrado: prod_u{ID}_nombreLimpio_{TIMESTAMP}.ext
            $nombre_producto_limpio = substr(preg_replace("/[^a-zA-Z0-9]/", "", $nombre), 0, 15);
            $nombre_archivo_final = "prod_u" . $id_usuario . "_" . $nombre_producto_limpio . "_" . time() . "." . $extension;
            
            $ruta_completa = $directorio_subida . $nombre_archivo_final;

            if (!move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_completa)) {
                // Si falla la subida física, volvemos al default
                $nombre_archivo_final = "default.jpg";
            }
        }
    }

    // --- INSERCIÓN SEGURA EN LA BASE DE DATOS ---
    // Usamos sentencias preparadas para evitar inyección SQL
    $sql = "INSERT INTO Producto (nombre, descripcion, imagen, id_categoria, precio, stock, estado, id_vendedor) 
            VALUES (?, ?, ?, ?, ?, ?, 'activo', ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssidii", $nombre, $desc, $nombre_archivo_final, $cat, $precio, $stock, $id_usuario);

    if ($stmt->execute()) {
        // Éxito: volvemos al panel de admin o al index
        header("Location: admin_productos.php?msg=ok");
        exit();
    } else {
        // Error de base de datos
        die("Error al guardar el producto: " . $conn->error);
    }
} else {
    // Si alguien intenta entrar directamente al PHP sin POST
    header("Location: subir_producto.php");
    exit();
}