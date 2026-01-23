<?php
session_start(); 
require_once 'includes/conexion.php';

// Verificación de sesión activa. 
// Mientras se implementa el Login, usamos el ID 1 (Administrador) del SQL.
if (!isset($_SESSION['id_usuario'])) {
   header("Location: login.php");
    exit(); 
}

$id_usuario = $_SESSION['id_usuario'];

// Recuperar Nombre, Email y Rol de la tabla Usuario
$sql = "SELECT nombre, email, rol FROM Usuario WHERE id_usuario = $id_usuario";
$resultado = $conn->query($sql);

if ($usuario = $resultado->fetch_assoc()) {
    $nombre = $usuario['nombre'];
    $email = $usuario['email'];
    $rol = $usuario['rol'];
} else {
    die("Error: Usuario no encontrado.");
}

include 'includes/cabecera.php'; 
?>

<link rel="stylesheet" href="css/usuarios.css">

<main class="contenedor-perfil">
    <div class="card-usuario">
        <h2 class="titulo-perfil">Perfil de Usuario</h2>
        
        <div class="perfil-info">
            <div class="dato-grupo">
                <strong class="dato-label">Nombre Completo:</strong>
                <span class="dato-valor"><?php echo htmlspecialchars($nombre); ?></span>
            </div>
            
            <div class="dato-grupo">
                <strong class="dato-label">Correo Electrónico:</strong>
                <span class="dato-valor"><?php echo htmlspecialchars($email); ?></span>
            </div>
            
            <div class="dato-grupo">
                <strong class="dato-label">Rol de Usuario:</strong>
                <span class="badge-rol"><?php echo htmlspecialchars($rol); ?></span>
            </div>
        </div>

        <div class="perfil-acciones">
            <a href="editar_perfil.php" class="btn btn-primario">Editar Información</a>
            <a href="logout.php" class="btn btn-peligro">Cerrar Sesión</a>
        </div>
    </div>
</main>

<?php include 'includes/pie.php'; ?>