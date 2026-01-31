<?php
session_start();
require_once 'includes/conexion.php';

// 1. SEGURIDAD: Verificar sesión y existencia de ID
if (!isset($_SESSION['id_usuario']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit();
}

$id_prod = intval($_GET['id']);
$id_user = $_SESSION['id_usuario'];

$sql = "SELECT * FROM Producto WHERE id_producto = ? AND id_vendedor = ? AND estado = 'activo'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_prod, $id_user);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    header("Location: admin_productos.php?msg=err_permiso");
    exit();
}

$producto = $resultado->fetch_assoc();
$query_cat = "SELECT * FROM Categoria";
$res_cat = $conn->query($query_cat);

include 'includes/header.php';
?>

<style>
    body {
        background: #050505 !important;
        color: #fff;
        font-family: 'Rajdhani', sans-serif;
    }

    .edit-container {
        max-width: 700px;
        margin: 60px auto;
        padding: 0 20px;
    }

    .card-editar {
        background: rgba(10, 10, 10, 0.95);
        border: 1px solid rgba(0, 212, 255, 0.3);
        border-radius: 15px;
        padding: 40px;
        position: relative;
        box-shadow: 0 0 40px rgba(0, 0, 0, 1);
    }

    h2 { 
        font-family: 'Orbitron', sans-serif; 
        color: #00d4ff; 
        text-align: center; 
        margin-bottom: 40px; 
        letter-spacing: 4px; 
        text-shadow: 0 0 10px rgba(0, 212, 255, 0.5);
    }

    .form-group { margin-bottom: 25px; }
    .form-group label { color: #555; font-family: 'Orbitron', sans-serif; font-size: 0.7rem; display: block; margin-bottom: 10px; letter-spacing: 1px;}
    
    .form-control {
        width: 100%;
        padding: 14px;
        background: #000;
        border: 1px solid #222;
        color: #fff;
        border-radius: 4px;
        box-sizing: border-box;
        transition: 0.3s;
    }

    .form-control:focus {
        outline: none;
        border-color: #00d4ff;
        box-shadow: 0 0 10px rgba(0, 212, 255, 0.2);
    }

    /* --- SOLUCIÓN AL BOTÓN DE ARCHIVO FEO --- */
    .file-upload-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        gap: 20px;
        background: rgba(255,255,255,0.02);
        padding: 15px;
        border-radius: 8px;
        border: 1px dashed #333;
    }

    .img-preview {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 4px;
        border: 1px solid #00d4ff;
    }

    /* Ocultamos el input real */
    input[type="file"] {
        display: none;
    }

    /* Creamos el botón falso */
    .custom-file-button {
        background: transparent;
        border: 1px solid #00d4ff;
        color: #00d4ff;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
        font-family: 'Orbitron';
        font-size: 0.7rem;
        transition: 0.3s;
    }

    .custom-file-button:hover {
        background: #00d4ff;
        color: #000;
    }

    #file-name {
        font-size: 0.8rem;
        color: #888;
        font-style: italic;
    }

    /* --- BOTONES DE ACCIÓN --- */
    .btn-update {
        background: #00d4ff;
        color: #000;
        border: none;
        padding: 18px;
        font-family: 'Orbitron', sans-serif;
        font-weight: 900;
        cursor: pointer;
        border-radius: 4px;
        width: 100%;
        transition: 0.3s;
        margin-top: 20px;
        letter-spacing: 2px;
    }

    .btn-update:hover {
        background: #fff;
        box-shadow: 0 0 30px rgba(0, 212, 255, 0.6);
    }

    .abort-link {
        display: block;
        text-align: center;
        color: #444;
        margin-top: 25px;
        text-decoration: none;
        font-family: 'Orbitron';
        font-size: 0.65rem;
        transition: 0.3s;
    }

    .abort-link:hover { color: #ff4444; }
</style>

<div class="edit-container">
    <div class="card-editar">
        <h2>RECONFIGURAR</h2>
        
        <form action="procesar_edicion.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_producto" value="<?= $producto['id_producto'] ?>">

            <div class="form-group">
                <label>IDENTIFICADOR DE LA RELIQUIA</label>
                <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($producto['nombre']) ?>" required>
            </div>

            <div class="form-group">
                <label>NÚCLEO DE CATEGORÍA</label>
                <select name="id_categoria" class="form-control">
                    <?php while($cat = $res_cat->fetch_assoc()): ?>
                        <option value="<?= $cat['id_categoria'] ?>" <?= ($cat['id_categoria'] == $producto['id_categoria']) ? 'selected' : '' ?>>
                            <?= strtoupper($cat['nombre_categoria']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>ESCANEO VISUAL DEL ARCHIVO</label>
                <div class="file-upload-wrapper">
                    <img src="img/productos/<?= $producto['imagen'] ?>" class="img-preview" id="preview-img" onerror="this.src='img/productos/default.jpg'">
                    
                    <div>
                        <label for="foto" class="custom-file-button">CAMBIAR ARCHIVO</label>
                        <input type="file" name="foto" id="foto" accept="image/*" onchange="previewFile()">
                        <p id="file-name">Sin cambios detectados</p>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 30px;">
                <div class="form-group" style="flex:1;">
                    <label>VALOR (€)</label>
                    <input type="number" name="precio" step="0.01" class="form-control" value="<?= $producto['precio'] ?>" required>
                </div>
                <div class="form-group" style="flex:1;">
                    <label>RESERVAS</label>
                    <input type="number" name="stock" class="form-control" value="<?= $producto['stock'] ?>" required>
                </div>
            </div>

            <button type="submit" class="btn-update">SINCRONIZAR CAMBIOS</button>
            <a href="admin_productos.php" class="abort-link">TERMINAR CONEXIÓN (VOLVER)</a>
        </form>
    </div>
</div>

<script>
    function previewFile() {
        const file = document.querySelector('input[type=file]').files[0];
        const preview = document.getElementById('preview-img');
        const fileName = document.getElementById('file-name');
        const reader = new FileReader();

        reader.onloadend = function () {
            preview.src = reader.result;
            fileName.textContent = file.name;
            fileName.style.color = "#00d4ff";
        }

        if (file) {
            reader.readAsDataURL(file);
        }
    }
</script>

<?php include 'includes/pie.php'; ?>