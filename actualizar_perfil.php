<?php
session_start();
require_once 'includes/conexion.php';
require_once 'includes/funciones.php';

// Verificación de sesión (Seguimos con ID 1 temporalmente por desarrollo)
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Solo procesamos si los datos vienen por POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //Primero capturamos el dato que viene del formulario
    $password_confirm = $_POST['password_confirmacion'];

    // VALIDACIÓN DE SEGURIDAD
    if (!verificarPasswordActual($conn, $id_usuario, $password_confirm)) {
        header("Location: editar_perfil.php?error=pass_incorrecta");
        exit();
    }
    
    // 1. Saneamiento de datos para evitar Inyección SQL
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $email  = mysqli_real_escape_string($conn, $_POST['email']);
    $nueva_pass = $_POST['nueva_password'];

    // 2. Preparar la consulta de actualización básica (Nombre y Email)
    $sql = "UPDATE Usuario SET nombre = '$nombre', email = '$email' WHERE id_usuario = $id_usuario";
    
    if ($conn->query($sql)) {
        
        // 3. Lógica para la contraseña: Solo se actualiza si el usuario escribió algo
        if (!empty($nueva_pass)) {
            // Encriptamos la nueva contraseña antes de guardarla
            $pass_encriptada = password_hash($nueva_pass, PASSWORD_DEFAULT);
            $sql_pass = "UPDATE Usuario SET password = '$pass_encriptada' WHERE id_usuario = $id_usuario";
            $conn->query($sql_pass);
        }
        
        // 4. Redirección al perfil con mensaje de éxito
        header("Location: perfil.php?actualizado=1");
        exit();

    } else {
        // En caso de error, lo mostramos (útil en desarrollo)
        echo "Error al actualizar los datos: " . $conn->error;
    }
} else {
    // Si alguien intenta entrar a este archivo directamente sin el formulario
    header("Location: perfil.php");
    exit();
}
?>