<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

include 'includes/header.php';
require_once 'includes/conexion.php';

$query_categorias = "SELECT id_categoria, nombre_categoria, tipo_libro FROM Categoria";
$resultado_categorias = $conn->query($query_categorias);
?>

<style>
    /* 1. Layout Base */
    main {
        position: relative;
        display: flex !important;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 80vh; 
        padding: 40px 20px;
        background: #000; 
        overflow: hidden;
    }

    /* 2. Luces dinámicas de fondo */
    .cursor-glow-red, .cursor-glow-blue {
        position: fixed;
        width: 500px;
        height: 500px;
        border-radius: 50%;
        filter: blur(100px);
        z-index: 1; 
        opacity: 0.5;
        pointer-events: none;
        top: 0;
        left: 0;
    }

    .cursor-glow-red { background: radial-gradient(circle, rgba(255, 0, 0, 0.4) 0%, transparent 70%); }
    .cursor-glow-blue { background: radial-gradient(circle, rgba(0, 212, 255, 0.4) 0%, transparent 70%); }

    /* 3. Estética de la Carta de Formulario */
    .card-publicar {
        position: relative;
        z-index: 10; 
        width: 100%;
        max-width: 600px;
    }

    .borde-animado-interno {
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        padding: 3px;
        background: transparent;
    }

    .borde-animado-interno::before {
        content: "";
        position: absolute;
        top: -50%; left: -50%;
        width: 200%; height: 200%;
        background: conic-gradient(transparent, #00d4ff, transparent, #ff0000, transparent);
        animation: rotateBorder 15s linear infinite;
        z-index: -1;
    }

    .borde-animado-interno::after {
        content: "";
        position: absolute;
        inset: 0;
        background-color: #0a0a0a;
        border-radius: 17px;
        z-index: -1;
    }

    @keyframes rotateBorder {
        100% { transform: rotate(360deg); }
    }

    /* Formulario */
    .form-content { padding: 40px; color: #fff; }
    .input-group { margin-bottom: 20px; }
    .input-group label { display: block; color: #00d4ff; font-family: 'Orbitron'; font-size: 0.75rem; margin-bottom: 8px; text-transform: uppercase; }
    .form-control { width: 100%; padding: 12px; background: #151515; border: 1px solid #333; color: #fff; border-radius: 8px; }
    .flex-row { display: flex; gap: 15px; margin-bottom: 25px; }
    .resaltado label { color: #ff4757; }
    .resaltado .form-control { border-color: #ff4757; }
</style>

<div class="cursor-glow-red" id="glow-red-form"></div>
<div class="cursor-glow-blue" id="glow-blue-form"></div>

<div class="card-publicar">
    <div class="borde-animado-interno">
        <div class="form-content">
            <h2 style="font-family: 'Orbitron'; color: #00d4ff; text-align: center; margin-bottom: 10px;">PUBLICAR PRODUCTO</h2>
            <p style="text-align: center; color: #888; font-size: 0.8rem; margin-bottom: 30px;">Añade un nuevo objeto a la colección</p>

            <form action="procesar_producto.php" method="POST" enctype="multipart/form-data">
                <div class="input-group">
                    <label>Nombre de la Reliquia</label>
                    <input type="text" name="nombre" class="form-control" placeholder="Ej: Espada de Plasma" required>
                </div>

                <div class="input-group">
                    <label>Imagen del Artefacto</label>
                    <input type="file" name="foto" class="form-control" accept="image/*" required>
                </div>

                <div class="input-group">
                    <label>Categoría</label>
                    <select name="id_categoria" class="form-control" required>
                        <option value="">-- Selecciona --</option>
                        <?php while ($cat = $resultado_categorias->fetch_assoc()): ?>
                            <option value="<?= $cat['id_categoria'] ?>">
                                <?= htmlspecialchars($cat['nombre_categoria']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="flex-row">
                    <div class="input-group resaltado" style="flex:1;">
                        <label>Precio (€)</label>
                        <input type="number" name="precio" step="0.01" class="form-control" required>
                    </div>
                    <div class="input-group resaltado" style="flex:1;">
                        <label>Stock</label>
                        <input type="number" name="stock" value="1" class="form-control" required>
                    </div>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" style="flex: 2; background: transparent; border: 2px solid #ff0000; color: #ff0000; padding: 14px; cursor: pointer; font-weight: bold; font-family: 'Orbitron'; border-radius: 8px; transition: 0.3s;" onmouseover="this.style.background='#ff0000'; this.style.color='#000'" onmouseout="this.style.background='transparent'; this.style.color='#ff0000'">FORJAR RELIQUIA</button>
                    <a href="admin_productos.php" style="flex: 1; background: #333; color: #fff; text-align: center; padding: 14px; text-decoration: none; border-radius: 8px; font-weight: bold; font-family: 'Orbitron'; font-size: 0.8rem; display: flex; align-items: center; justify-content: center;">CANCELAR</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Efecto de seguimiento de luces neón
    document.addEventListener('mousemove', (e) => {
        const glowRed = document.getElementById('glow-red-form');
        const glowBlue = document.getElementById('glow-blue-form');
        
        if(glowRed && glowBlue) {
            glowRed.style.transform = `translate(${e.clientX - 250}px, ${e.clientY - 250}px)`;
            glowBlue.style.transform = `translate(${e.clientX - 100}px, ${e.clientY - 100}px)`;
        }
    });
</script>

<?php include 'includes/pie.php'; ?>