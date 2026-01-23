<?php
/**
 * Valida si la contraseña ingresada coincide con la almacenada
 */
function verificarPasswordActual($conexion, $id_usuario, $password_proporcionada){
    // Escapamos el ID aunque venga de la sesión, por buena práctica
    $id_usuario = mysqli_real_escape_string($conexion, $id_usuario);
    
    $sql = "SELECT password FROM Usuario WHERE id_usuario = '$id_usuario'";
    $resultado = $conexion->query($sql);
    
    if ($usuario = $resultado->fetch_assoc()) {
        // Comparamos la clave que escribió el usuario con el hash guardado en la BD
        return password_verify($password_proporcionada, $usuario['password']);
    }
    return false;
}
function generarTarjetaProducto($nombre, $precio, $stock) {
    // Determinamos el color del stock para dar feedback visual
    $claseStock = ($stock > 0) ? 'con-stock' : 'sin-stock';
    $textoStock = ($stock > 0) ? "En stock: $stock" : "Agotado";

    echo "
    <article class='card-producto'>
        <div class='card-cuerpo'>
            <h3 class='card-titulo'>" . htmlspecialchars($nombre) . "</h3>
            <p class='card-precio'>" . number_format($precio, 2) . "€</p>
            <span class='card-stock $claseStock'>$textoStock</span>
        </div>
        <div class='card-acciones'>
            <button class='btn-detalle' " . ($stock <= 0 ? 'disabled' : '') . ">
                Ver más
            </button>
        </div>
    </article>
    ";
}
?>
<!-- CRUD -->