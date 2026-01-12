<?php
session_start();
require_once 'includes/conexion.php';

// Verificación de sesión activa. 
// Mientras se implementa el Login, usamos el ID 1 (Administrador) del SQL.
if (!isset($_SESSION['id_usuario'])) {
    $_SESSION['id_usuario'] = 1; 
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

<main class="contenedor">
    <div class="perfil-container" style="max-width: 600px; margin: 40px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <h2 style="text-align: center; color: #333; margin-bottom: 25px;">Perfil de Usuario</h2>
        
        <div class="perfil-info" style="border-top: 1px solid #eee; padding-top: 20px;">
            <p style="margin-bottom: 15px;">
                <strong style="color: #666;">Nombre Completo:</strong><br>
                <span style="font-size: 1.1em; color: #333;"><?php echo htmlspecialchars($nombre); ?></span>
            </p>
            
            <p style="margin-bottom: 15px;">
                <strong style="color: #666;">Correo Electrónico:</strong><br>
                <span style="font-size: 1.1em; color: #333;"><?php echo htmlspecialchars($email); ?></span>
            </p>
            
            <p style="margin-bottom: 25px;">
                <strong style="color: #666;">Rol de Usuario:</strong><br>
                <span style="display: inline-block; padding: 4px 12px; background: #f0f4f8; color: #1a73e8; border-radius: 4px; font-weight: bold; text-transform: uppercase; font-size: 0.85em;">
                    <?php echo htmlspecialchars($rol); ?>
                </span>
            </p>
        </div>

        <div class="perfil-acciones" style="display: flex; gap: 10px;">
            <a href="editar_perfil.php" style="flex: 1; text-align: center; background: #4CAF50; color: white; padding: 12px; border-radius: 4px; text-decoration: none; font-weight: bold;">Editar Información</a>
            <a href="logout.php" style="flex: 1; text-align: center; background: #f44336; color: white; padding: 12px; border-radius: 4px; text-decoration: none; font-weight: bold;">Cerrar Sesión</a>
        </div>
    </div>
</main>

<?php include 'includes/pie.php'; ?>