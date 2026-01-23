<?php
// 1. Iniciar sesión y conectar a la base de datos
session_start();
require_once 'includes/conexion.php';

// 2. Comprobar si ya existe una sesión (para no loguearse dos veces)
if (isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit();
}

// 3. Procesar el formulario cuando se hace POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escapamos los datos para evitar inyecciones básicas
    $email = mysqli_real_escape_string($conn, $_POST['user']);
    $password = $_POST['pass'];

    // Consultamos si el email existe
    $sql = "SELECT id_usuario, nombre, password, rol FROM usuario WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $usuario = $result->fetch_assoc();

        // Verificamos la contraseña encriptada
        if ($password === $usuario['password']) {
            // Guardamos datos en la sesión
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['rol'] = $usuario['rol'];

            // Redirigimos al index
            header("Location: index.php");
            exit();
        } else {
            $error = "Contraseña incorrecta";
        }
    } else {
        $error = "El usuario no existe";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Card Collector | Acceso</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="cursor-glow-red" id="glow-red"></div>
    <div class="cursor-glow-blue" id="glow-blue"></div>

    <div class="login-container">
        <div class="card-wrapper" id="card">
            <div class="card-content">
                <div class="card-header">
                    <h2>CARD COLLECTOR</h2>
                    <span class="rarity">ULTRA RARE</span>
                </div>
                
                <form action="login.php" method="POST">
                    
                    <?php if(isset($error)): ?>
                        <p style="color: #ff0000; text-align: center; margin-bottom: 10px; font-size: 0.8rem;">
                            <?php echo $error; ?>
                        </p>
                    <?php endif; ?>

                    <div class="input-group">
                        <input type="text" name="user" required>
                        <label>Usuario / Email</label>
                    </div>
                    <div class="input-group">
                        <input type="password" name="pass" required>
                        <label>Contraseña</label>
                    </div>
                    <button type="submit" class="btn-login">REVELAR ACCESO</button>
                </form>
                
                <p class="footer-text">¿No tienes cuenta? <a href="registro.php">Crea tu mazo aquí</a></p>
            </div>
            <div class="shine"></div>
        </div>
    </div>

    <script src="js/login.js"></script>
</body>
</html>