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
    
    //VALIDACIONES 
    // Validar que el nombre no esté vacío (eliminando espacios en blanco)
    if (empty(trim($nombre))) {
        header("Location: admin_productos.php?msg=err_nombre");
        exit();
    }

    // Validar que el precio sea un número positivo
    if (!is_numeric($precio) || $precio <= 0) {
        header("Location: admin_productos.php?msg=err_precio");
        exit();
    }

    // Validar que el stock sea un número entero y no sea negativo
    if (!isset($stock) || $stock < 0) {
        header("Location: admin_productos.php?msg=err_stock");
        exit();
    }
    // Forzamos el id_vendedor al 1.
    //Nota: Cuando se termine el sistemas de usuarios y sesiones pondremos: $id_vendedor = $_SESSION['id_usuario'];
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