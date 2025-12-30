<?php
//Iniciamos sesión para verificar el rol del usuario
session_start();

/**
 * CONTROL DE ACCESO
 * Verificamos si existe la sesión y si el rol es 'admin'.
 * Nota: Por ahora, mientras probamos, si te redirige al login, 
 * se pueden comentar estas líneas (del 12 al 15).
 */
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit(); // Detiene la carga del resto de la página
}

include 'includes/cabecera.php';
require_once 'includes/conexion.php';
?>

<main class="contenedor">
    <div class="admin-header">
        <h2>Panel de Administración</h2>
        <p>Gestión de Inventario - Acceso Restringido</p>
    </div>

    <section class="admin-dashboard">
        <p>Control de acceso verificado correctamente. El usuario actual tiene privilegios de administrador.</p>
    </section>
</main>

<?php include 'includes/pie.php'; ?>