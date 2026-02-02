<?php
$categoria_id = isset($_GET['categoria']) ? intval($_GET['categoria']) : 0;
$orden = isset($_GET['orden']) ? $_GET['orden'] : 'reciente';
$pagina_actual = basename($_SERVER['PHP_SELF']); 
?>

<style>
    /* Contenedor principal */
    .filter-wrapper {
        display: flex;
        justify-content: flex-end;
        max-width: 1400px;
        margin: 0 auto 20px auto;
        padding: 0 20px;
        position: relative;
        z-index: 1001; /* Un nivel por encima para evitar superposiciones */
    }

    .dropdown-filter {
        position: relative;
        display: inline-block;
    }

    .btn-filter-trigger {
        background: #000;
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 10px 25px;
        font-family: 'Orbitron', sans-serif;
        font-size: 0.7rem;
        letter-spacing: 2px;
        cursor: pointer;
        transition: 0.3s;
        text-transform: uppercase;
    }

    .btn-filter-trigger:hover, .btn-filter-trigger.active {
        border-color: #00d4ff;
        color: #00d4ff;
    }

    /* Menú Desplegable Negro */
    .filter-content {
        display: none;
        position: absolute;
        right: 0;
        top: calc(100% + 10px);
        background: #000; 
        min-width: 260px;
        border: 1px solid rgba(0, 212, 255, 0.4);
        padding: 25px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.9);
    }

    .filter-content.show { display: block; }

    /* Estilos de Formulario */
    .filter-content label {
        display: block;
        font-family: 'Orbitron';
        font-size: 0.6rem;
        color: #00d4ff;
        margin: 15px 0 8px 0;
        text-transform: uppercase;
    }

    .filter-content select {
        width: 100%;
        background: #111;
        border: 1px solid #333;
        color: #fff;
        padding: 10px;
        font-family: 'Rajdhani';
        font-size: 0.9rem;
        outline: none;
    }

    .btn-apply-cyber {
        width: 100%;
        margin-top: 25px;
        background: #00d4ff;
        color: #000;
        border: none;
        padding: 12px;
        font-family: 'Orbitron';
        font-size: 0.75rem;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
    }

    .btn-apply-cyber:hover {
        background: #fff;
        box-shadow: 0 0 15px #00d4ff;
    }

    .btn-reset-link {
        display: block;
        text-align: center;
        margin-top: 20px;
        color: #444;
        text-decoration: none;
        font-size: 0.6rem;
        font-family: 'Orbitron';
    }
</style>

<div class="filter-wrapper">
    <div class="dropdown-filter" id="mainFilterContainer">
        <button type="button" class="btn-filter-trigger" id="filterBtn">
            FILTROS ▼
        </button>

        <div id="filterMenu" class="filter-content">
            <form method="GET" action="<?php echo $pagina_actual; ?>">
                
                <div style="display: none;">
    <label>Categoría</label>
    <select name="categoria">
        <option value="0">TODOS</option>
        <option value="1" <?= ($categoria_id == 1) ? 'selected' : '' ?>>CARTAS</option>
        <option value="6" <?= ($categoria_id == 6) ? 'selected' : '' ?>>CARTAS POKEMON</option>
        <option value="7" <?= ($categoria_id == 7) ? 'selected' : '' ?>>CARTAS MAGIC</option>
        <option value="8" <?= ($categoria_id == 8) ? 'selected' : '' ?>>FIGURAS</option>
        <option value="9" <?= ($categoria_id == 9) ? 'selected' : '' ?>>LIBROS</option>
        <option value="3" <?= ($categoria_id == 3) ? 'selected' : '' ?>>MANGAS</option>
        <option value="4" <?= ($categoria_id == 4) ? 'selected' : '' ?>>COMICS</option>
        <option value="5" <?= ($categoria_id == 5) ? 'selected' : '' ?>>NOVELAS</option>
    </select>
</div>

                <label>Ordenar Por</label>
                <select name="orden">
                    <option value="reciente" <?= ($orden == 'reciente') ? 'selected' : '' ?>>MÁS RECIENTE</option>
                    <option value="precio_min" <?= ($orden == 'precio_min') ? 'selected' : '' ?>>PRECIO MÁS BAJO</option>
                    <option value="precio_max" <?= ($orden == 'precio_max') ? 'selected' : '' ?>>PRECIO MÁS ALTO</option>
                    <option value="az" <?= ($orden == 'az') ? 'selected' : '' ?>>NOMBRE A-Z</option>
                    <option value="za" <?= ($orden == 'za') ? 'selected' : '' ?>>NOMBRE Z-A</option>
                </select>

                <button type="submit" class="btn-apply-cyber">EJECUTAR SCAN</button>
                <a href="<?php echo $pagina_actual; ?>" class="btn-reset-link">[ RESETEAR ]</a>
            </form>
        </div>
    </div>
</div>

<script>
    const filterBtn = document.getElementById('filterBtn');
    const filterMenu = document.getElementById('filterMenu');
    const container = document.getElementById('mainFilterContainer');

    // Toggle del menú
    filterBtn.addEventListener('click', (e) => {
        filterMenu.classList.toggle('show');
        filterBtn.classList.toggle('active');
        e.stopPropagation();
    });

    // Evitar que los clics dentro del menú lo cierren
    filterMenu.addEventListener('click', (e) => {
        e.stopPropagation();
    });

    // Cerrar al hacer clic en cualquier otro lugar de la pantalla
    document.addEventListener('click', (e) => {
        if (!container.contains(e.target)) {
            filterMenu.classList.remove('show');
            filterBtn.classList.remove('active');
        }
    });
</script>