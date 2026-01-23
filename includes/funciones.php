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
?>
<!-- CRUD -->