<meta name="viewport" content="width=device-width, initial-scale=1.0">
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
//Recuperar Historial de Compras (JOIN entre Compra, Detalle y Producto)
$sql_historial = "SELECT C.id_compra, C.fecha_compra, C.total, C.estado_pago, 
                         GROUP_CONCAT(P.nombre SEPARATOR ', ') AS lista_productos
                  FROM Compra C
                  INNER JOIN Detalle_compra DC ON C.id_compra = DC.id_compra
                  INNER JOIN Producto P ON DC.id_producto = P.id_producto
                  WHERE C.id_usuario = $id_usuario
                  GROUP BY C.id_compra
                  ORDER BY C.fecha_compra DESC, C.id_compra DESC";

$resultado_historial = $conn->query($sql_historial);
include 'includes/header.php'; 
?>

<link rel="stylesheet" href="css/perfil.css">

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
    <section class="seccion-historial">
        <h2 class="titulo-historial">Historial de Compras</h2>
        
        <?php if ($resultado_historial && $resultado_historial->num_rows > 0): ?>
            <div class="tabla-contenedor">
                <table class="tabla-compras">
                    <thead>
                        <tr>
                            <th>ID Pedido</th>
                            <th>Fecha</th>
                            <th>Productos</th>
                            <th>Total</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($compra = $resultado_historial->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo $compra['id_compra']; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($compra['fecha_compra'])); ?></td>
                                <td><?php echo htmlspecialchars($compra['lista_productos']); ?></td>
                                <td><?php echo number_format($compra['total'], 2); ?>€</td>
                                <td>
                                    <span class="estado-pago <?php echo $compra['estado_pago']; ?>">
                                        <?php echo ucfirst($compra['estado_pago']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="mensaje-vacio">
                <p>No tienes transacciones registradas actualmente.</p>
            </div>
        <?php endif; ?>
    </section>
    <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
    <div id="notification" class="cyber-toast success">
        <div class="toast-content">
            <i class="fas fa-check-double"></i>
            <div class="message">
                <span class="title">TRANSACCIÓN COMPLETADA</span>
                <span class="desc">Los productos han sido añadidos a tu colección.</span>
            </div>
        </div>
        <div class="toast-timer"></div>
    </div>

    <script>
        setTimeout(() => {
            const toast = document.getElementById('notification');
            toast.style.transform = 'translateX(120%)';
            setTimeout(() => toast.remove(), 500);
        }, 5000);
    </script>
<?php endif; ?>
</main>

<?php include 'includes/pie.php'; ?>