<?php
session_start();
require_once 'includes/conexion.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

$sql = "SELECT nombre, email FROM Usuario WHERE id_usuario = $id_usuario";
$resultado = $conn->query($sql);
$usuario = $resultado->fetch_assoc();

include 'includes/header.php'; 
?>

<link rel="stylesheet" href="css/editar_perfil.css">

<main class="contenedor-perfil">
    <div class="edit-card">
        
        <div class="edit-card-header">
            <h2>Editar Perfil</h2>
            <p>Actualiza tu información personal</p>
        </div>

        <form action="actualizar_perfil.php" method="POST">
            
            <div class="form-group">
                <label class="form-label">Nombre Completo</label>
                <input type="text" name="nombre" class="form-input" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Correo Electrónico</label>
                <input type="email" name="email" class="form-input" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
            </div>

            <div class="seccion-password">
                <label class="form-label">Cambiar Contraseña</label>
                <input type="password" name="nueva_password" class="form-input" placeholder="Dejar en blanco para no cambiar">
                <small>Solo completa este campo si deseas una nueva clave.</small>
            </div>

            <div class="seccion-seguridad">
                <label class="form-label">Confirmar con Contraseña Actual</label>
                <input type="password" name="password_confirmacion" class="form-input" required 
                       placeholder="Ingresa tu clave actual para autorizar los cambios">
                <small class="texto-importante">Requerido para cualquier modificación.</small>
            </div>

            <div class="btn-container">
                <button type="submit" class="btn-guardar">Guardar Cambios</button>
                <a href="perfil.php" class="btn-cancelar">Cancelar y Volver</a>
            </div>

        </form>
    </div>
</main>

<?php include 'includes/pie.php'; ?>