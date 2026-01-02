<?php
// 1. Conexión a la base de datos
include 'includes/conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 2. Recoger datos del formulario
    // Usamos mysqli_real_escape_string para evitar errores con comillas (ej. "L'Oreal")
    $nombre      = mysqli_real_escape_string($conn, $_POST['nombre']);
    $descripcion = mysqli_real_escape_string($conn, $_POST['descripcion']);
    $id_categoria= $_POST['id_categoria'];
    $precio      = $_POST['precio'];
    $stock       = $_POST['stock'];
    
    // Forzamos el id_vendedor al 1 (nuestro admin creado en el script SQL)
    $id_vendedor = 1; 

    // 3. Consulta SQL de inserción
    $sql = "INSERT INTO Producto (nombre, descripcion, id_categoria, precio, stock, estado, id_vendedor) 
            VALUES ('$nombre', '$descripcion', $id_categoria, $precio, $stock, 'activo', $id_vendedor)";

    // 4. Ejecutar y redirigir
    if ($conn->query($sql) === TRUE) {
        // Redirigimos de vuelta con un parámetro de éxito
        header("Location: admin_productos.php?msg=ok");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>