<?php
session_start();

// 1. CONTROL DE ACCESO: Solo usuarios logueados
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

require_once 'includes/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Seguridad de Identidad: el vendedor siempre es el usuario logueado
    $id_vendedor = $_SESSION['id_usuario']; 
    
    // Limpieza de datos recibidos
    $nombre = trim($_POST['nombre']);
    $desc = trim($_POST['descripcion']);
    $cat = intval($_POST['id_categoria']);
    $precio = floatval($_POST['precio']);
    $stock = intval($_POST['stock']);

    // Validaciones de negocio
    if (empty($nombre) || $precio <= 0) {
        header("Location: admin_productos.php?msg=err_nombre");
        exit();
    }

    // --- LÓGICA DE PROCESAMIENTO DE IMAGEN (SEGURIDAD AVANZADA) ---
    $nombre_archivo_final = "default.jpg"; 

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        $directorio_subida = "img/productos/";
        
        if (!file_exists($directorio_subida)) {
            mkdir($directorio_subida, 0777, true);
        }

        $tmp_name = $_FILES['foto']['tmp_name'];
        $info_archivo = pathinfo($_FILES['foto']['name']);
        $extension = strtolower($info_archivo['extension']);
        $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'webp'];
        
        // A. Validar extensión permitida
        if (in_array($extension, $extensiones_permitidas)) {
            
            // B. VALIDACIÓN ANTIVIRUS/MALWARE: ¿Es realmente una imagen?
            // getimagesize() analiza los bytes del archivo, no solo el nombre.
            $check = getimagesize($tmp_name);
            if ($check !== false) {
                
                // C. RENOMBRADO SEGURO
                // Evitamos caracteres especiales y ataques de Directory Traversal
                $nombre_limpio = substr(preg_replace("/[^a-zA-Z0-9]/", "", $nombre), 0, 15);
                $nombre_archivo_final = "prod_u" . $id_vendedor . "_" . $nombre_limpio . "_" . time() . "." . $extension;
                
                $ruta_destino = $directorio_subida . $nombre_archivo_final;

                if (!move_uploaded_file($tmp_name, $ruta_destino)) {
                    $nombre_archivo_final = "default.jpg"; 
                }
            } else {
                // El archivo finge ser imagen pero es potencialmente peligroso
                header("Location: admin_productos.php?msg=err_img_fake");
                exit();
            }
        }
    }

    // --- 2. INSERCIÓN ATÓMICA (SENTENCIAS PREPARADAS) ---
    $sql = "INSERT INTO Producto (nombre, descripcion, imagen, id_categoria, precio, stock, estado, id_vendedor) 
            VALUES (?, ?, ?, ?, ?, ?, 'activo', ?)";
    
    $stmt = $conn->prepare($sql);
    
    // Tipos de datos: s (string), i (int), d (double/float)
    $stmt->bind_param("sssidii", $nombre, $desc, $nombre_archivo_final, $cat, $precio, $stock, $id_vendedor);

    if ($stmt->execute()) {
        header("Location: admin_productos.php?msg=ok");
        exit();
    } else {
        // Log de error interno y mensaje amigable
        error_log("Error en DB: " . $conn->error);
        die("Error crítico en la forja de la reliquia. Inténtelo más tarde.");
    }

} else {
    header("Location: admin_productos.php");
    exit();
}