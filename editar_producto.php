<?php
session_start();
require_once 'includes/conexion.php';

// 1. SEGURIDAD: Verificar sesión y pertenencia del producto
if (!isset($_SESSION['id_usuario']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit();
}

$id_prod = intval($_GET['id']);
$id_user = $_SESSION['id_usuario'];

// Buscamos el producto pero ASEGURANDO que el id_vendedor sea el del usuario actual
$sql = "SELECT * FROM Producto WHERE id_producto = ? AND id_vendedor = ? AND estado = 'activo'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_prod, $id_user);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    // Si el ID no existe o no le pertenece al usuario, lo expulsamos
    header("Location: admin_productos.php?msg=err_permiso");
    exit();
}

$producto = $resultado->fetch_assoc();

// Consulta para las categorías
$query_cat = "SELECT * FROM Categoria";
$res_cat = $conn->query($query_cat);

include 'includes/header.php';
?>

<style>
    /* Mantenemos la coherencia con el fondo vistoso */
    body {
        background: radial-gradient(circle at center, #0d1117 0%, #050505 100%) !important;
    }

    .edit-container {
        max-width: 700px;
        margin: 50px auto;
        padding: 20px;
    }

    .card-editar {
        background: #0a0a0a;
        border: 2px solid #333;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 0 30px rgba(0, 212, 255, 0.2);
        position: relative;
    }

    .card-editar::before {
        content: "";
        position: absolute;
        inset: -2px;
        background: linear-gradient(45deg, #00d4ff, transparent, #ff0000);
        z-index: -1;
        border-radius: 22px;
        opacity: 0.5;
    }

    h2 { font-family: 'Orbitron'; color: #00d4ff; text-align: center; margin-bottom: 30px; }

    .form-group { margin-bottom: 20px; }
    .form-group label { color: #888; font-family: 'Orbitron'; font-size: 0.8rem; display: block; margin-bottom: 8px; }
    
    .form-control {
        width: 100%;
        padding: 12px;
        background: #151515;
        border: 1px solid #333;
        color: #fff;
        border-radius: 8px;
    }

    .img-preview {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 10px;
        border: 2px solid #00d4ff;
        margin-bottom: 10px;
    }

    .btn-update {
        background: #00d4ff;
        color: #000;
        border: none;
        padding: 15px 30px;
        font-family: 'Orbitron';
        font-weight: bold;
        cursor: pointer;
        border-radius: 8px;
        width: 100%;
        transition: 0.3s;
    }

    .btn-update:hover {
        background: #fff;
        box-shadow: 0 0 20px #00d4ff;
    }
</style>

<div class="edit-container">
    <div class="card-editar">
        <h2>EDITAR RELIQUIA</h2>
        
        <form action="procesar_edicion.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_producto" value="<?= $producto['id_producto'] ?>">

            <div class="form-group">
                <label>Nombre del Objeto</label>
                <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($producto['nombre']) ?>" required>
            </div>

            <div class="form-group">
                <label>Categoría</label>
                <select name="id_categoria" class="form-control">
                    <?php while($cat = $res_cat->fetch_assoc()): ?>
                        <option value="<?= $cat['id_categoria'] ?>" <?= ($cat['id_categoria'] == $producto['id_categoria']) ? 'selected' : '' ?>>
                            <?= $cat['nombre_categoria'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Imagen Actual</label><br>
                <img src="img/productos/<?= $producto['imagen'] ?>" class="img-preview" onerror="this.src='img/productos/default.jpg'"><br>
                <label>Cambiar imagen (Opcional)</label>
                <input type="file" name="foto" class="form-control" accept="image/*">
            </div>

            <div style="display: flex; gap: 20px;">
                <div class="form-group" style="flex:1;">
                    <label>Precio (€)</label>
                    <input type="number" name="precio" step="0.01" class="form-control" value="<?= $producto['precio'] ?>" required>
                </div>
                <div class="form-group" style="flex:1;">
                    <label>Stock</label>
                    <input type="number" name="stock" class="form-control" value="<?= $producto['stock'] ?>" required>
                </div>
            </div>

            <button type="submit" class="btn-update">ACTUALIZAR NÚCLEO</button>
            <a href="admin_productos.php" style="display:block; text-align:center; color:#666; margin-top:15px; text-decoration:none; font-family:'Orbitron'; font-size:0.7rem;">CANCELAR</a>
        </form>
    </div>
</div>

<?php include 'includes/pie.php'; ?>