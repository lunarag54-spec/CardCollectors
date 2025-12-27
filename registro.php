<?php //include 'includes/header.php'; ?>

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
<?php //include 'includes/footer.php'; ?>
<!-- Página de registro del sitio web -->