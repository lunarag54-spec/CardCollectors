<?php
// 1. Incluimos la conexión
require_once 'includes/conexion.php';

// 2. Detectamos si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        echo "<script>alert('Las contraseñas no coinciden');</script>";
    } else {
        $checkEmail = "SELECT id_usuario FROM usuario WHERE email = ?";
        $stmtCheck = $conn->prepare($checkEmail);
        $stmtCheck->bind_param("s", $email);
        $stmtCheck->execute();
        $stmtCheck->store_result();
        
        if ($stmtCheck->num_rows > 0) {
            echo "<script>alert('Este correo ya está registrado. Intenta con otro.'); window.history.back();</script>";
            $stmtCheck->close();
        } else {
            $stmtCheck->close();
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO usuario(nombre, email, password, rol) VALUES (?, ?, ?, 'cliente')";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $nombre, $email, $password_hash);

            if ($stmt->execute()) {
                echo "<script>alert('¡Usuario registrado con éxito!'); window.location.href='login.php';</script>";
            } else {
                echo "Error al registrar: " . $conn->error;
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Card Collector | Registro</title>
    <link rel="stylesheet" href="css/registro.css"> </head>
<body>
    <div class="cursor-glow-red" id="glow-red"></div>
    <div class="cursor-glow-blue" id="glow-blue"></div>

    <div class="login-container">
        <div class="card-wrapper" id="card">
            <div class="card-content">
                <div class="card-header">
                    <h2>NUEVO MAZO</h2>
                    <span class="rarity">LEVEL 1 COLLECTOR</span>
                </div>
                
                <form action="registro.php" method="POST">
                    <div class="input-group">
                        <input type="text" name="nombre" required>
                        <label>Nombre Completo</label>
                    </div>

                    <div class="input-group">
                        <input type="email" name="email" required>
                        <label>Correo Electrónico</label>
                    </div>

                    <div class="input-group">
                        <input type="password" name="password" required>
                        <label>Contraseña</label>
                    </div>

                    <div class="input-group">
                        <input type="password" name="confirm_password" required>
                        <label>Confirmar Contraseña</label>
                    </div>

                    <button type="submit" class="btn-login">FORJAR CUENTA</button>
                </form>
                
                <p class="footer-text">¿Ya eres miembro? <a href="login.php">Inicia sesión</a></p>
            </div>
            <div class="shine"></div>
        </div>
    </div>

    <script src="js/login.js"></script>
</body>
</html>