<?php
session_start();

// 1. CONTROL DE ACCESO: Solo usuarios logueados
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

require_once 'includes/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Capturamos el ID del vendedor directamente de la sesión (Seguridad de Identidad)
    $id_vendedor = $_SESSION['id_usuario']; 
    
    // Limpieza de datos básicos
    $nombre = trim($_POST['nombre']);
    $desc = trim($_POST['descripcion']);
    $cat = intval($_POST['id_categoria']);
    $precio = floatval($_POST['precio']);
    $stock = intval($_POST['stock']);

    // Validaciones básicas de negocio
    if (empty($nombre) || $precio <= 0) {
        header("Location: admin_productos.php?msg=err_nombre");
        exit();
    }

    // --- SEGURIDAD DE IMAGEN (ANTIMALWARE) ---
    $nombre_archivo_final = "default.jpg"; 

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        $directorio_subida = "img/productos/";
        
        // Creamos la carpeta si no existe
        if (!file_exists($directorio_subida)) {
            mkdir($directorio_subida, 0777, true);
        }

        $tmp_name = $_FILES['foto']['tmp_name'];
        $info_archivo = pathinfo($_FILES['foto']['name']);
        $extension = strtolower($info_archivo['extension']);
        $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'webp'];
        
        // A. Validar extensión
        if (in_array($extension, $extensiones_permitidas)) {
            
            // B. VALIDACIÓN REAL DE CONTENIDO: ¿Es realmente una imagen?
            // getimagesize() devuelve falso si el archivo no es una imagen real (aunque tenga extensión .jpg)
            $check = getimagesize($tmp_name);
            if ($check !== false) {
                
                // C. RENOMBRADO SEGURO
                // Limpiamos el nombre para evitar caracteres extraños y añadimos timestamp
                $nombre_limpio = substr(preg_replace("/[^a-zA-Z0-9]/", "", $nombre), 0, 15);
                $nombre_archivo_final = "prod_u" . $id_vendedor . "_" . $nombre_limpio . "_" . time() . "." . $extension;
                
                $ruta_destino = $directorio_subida . $nombre_archivo_final;

                if (!move_uploaded_file($tmp_name, $ruta_destino)) {
                    $nombre_archivo_final = "default.jpg"; // Si falla la subida, ponemos la de por defecto
                }
            } else {
                // El archivo parece una imagen por la extensión, pero contiene código malicioso
                header("Location: admin_productos.php?msg=err_img_fake");
                exit();
            }
        }
    }

    /**
     * 2. INSERCIÓN SEGURA (SENTENCIAS PREPARADAS)
     * Insertamos el id_vendedor capturado de la sesión.
     */
    $sql = "INSERT INTO Producto (nombre, descripcion, imagen, id_categoria, precio, stock, estado, id_vendedor) 
            VALUES (?, ?, ?, ?, ?, ?, 'activo', ?)";
    
    $stmt = $conn->prepare($sql);
    
    // Tipos: s=string, i=int, d=double
    // bind_param: nombre(s), desc(s), imagen(s), cat(i), precio(d), stock(i), vendedor(i)
    $stmt->bind_param("sssidii", $nombre, $desc, $nombre_archivo_final, $cat, $precio, $stock, $id_vendedor);

    if ($stmt->execute()) {
        header("Location: admin_productos.php?msg=ok");
        exit();
    } else {
        // En producción, mejor registrar el error en un log y mostrar mensaje genérico
        die("Error crítico en la forja de la reliquia: " . $conn->error);
    }

} else {
    // Si intentan entrar al archivo por URL sin POST, los echamos
    header("Location: subir_producto.php");
    exit();
}