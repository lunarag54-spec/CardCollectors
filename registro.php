<?php
// 1. Incluimos la conexión
require_once 'includes/conexion.php';

// 2. Detectamos si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Recogemos los datos del formulario (los nombres deben coincidir con el 'nombre' de los inputs)
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // 3. Validación básica: ¿Las contraseñas coinciden?
    if ($password !== $confirm_password) {
        echo "<script>alert('Las contraseñas no coinciden');</script>";
    } else {
        // Verificamos si el correo ya está en la base de datos
        $checkEmail = "SELECT id_usuario FROM usuario WHERE email = ?";
        $stmtCheck = $conn->prepare($checkEmail);
        $stmtCheck->bind_param("s", $email);
        $stmtCheck->execute();
        $stmtCheck->store_result(); // Guardamos el resultado para contar las filas
        if ($stmtCheck->num_rows > 0) {
            // Si hay más de 0 filas, el email ya existe
            echo "<script>alert('Este correo ya está registrado. Intenta con otro.'); window.history.back();</script>";
            $stmtCheck->close();
        } else {
            // Si el email NO existe, procedemos a registrar (Subtarea 2)
            $stmtCheck->close();
            // 4. ENCRIPTACIÓN: Convertimos la clave en un hash seguro
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // 5. INSERTAR EN LA BASE DE DATOS
            // Usamos sentencias preparadas para evitar Inyección SQL (Seguridad)
            $sql = "INSERT INTO usuario(nombre, email, password, rol) VALUES (?, ?, ?, 'cliente')";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $nombre, $email, $password_hash);

            if ($stmt->execute()) {
                echo "<script>alert('¡usuario registrado con éxito!'); window.location.href='login.php';</script>";
            } else {
                echo "Error al registrar: " . $conn->error;
            }
            $stmt->close();
        }
    }
}
?>
<?php include 'includes/cabecera.php'; ?>
<main class="contenedor-registro">
    <h2>Crear cuenta en CardCollectors</h2>

    <form action="registro.php" method="POST" id="form-registro">
        <div class="campo">
            <label for="nombre">Nombre Completo:</label>
            <input type="text" id="nombre" name="nombre" required placeholder="Tu nombre">
        </div>

        <div class="campo">
            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" required placeholder="correo@ejemplo.com">
        </div>

        <div class="campo">
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="campo">
            <label for="confirm_password">Confirmar Contraseña:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>

        <button type="submit" class="btn-primario">Registrarse</button>
    </form>

    <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
</main>
<?php include 'includes/pie.php'; ?>
<!-- Página de registro del sitio web -->