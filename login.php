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
                
                <form action="login_process.php" method="POST">
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