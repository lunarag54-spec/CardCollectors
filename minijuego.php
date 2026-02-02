<?php 
require_once 'includes/conexion.php';
include 'includes/header.php'; 

// Buscamos el ID exacto del producto exclusivo 'Kaito' en la categoría 9
$query_figura = "SELECT id_producto FROM Producto WHERE id_categoria = 9 AND nombre LIKE '%Kaito%' LIMIT 1";
$res_figura = $conn->query($query_figura);
$figura = $res_figura->fetch_assoc();

// ID para el enlace de detalle
$id_secreto = $figura ? $figura['id_producto'] : '#';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bóveda de Reliquias - Minijuego Exclusivo</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Rajdhani:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --neon-cyan: #00d4ff;
            --neon-green: #00ff88;
            --neon-red: #ff4444;
            --bg-dark: #050505;
        }

        .game-universe {
            background: #000;
            min-height: 90vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Orbitron', sans-serif;
            color: #fff;
            padding: 20px;
            overflow: hidden;
        }

        .game-terminal {
            width: 100%;
            max-width: 550px;
            background: rgba(10, 10, 10, 0.95);
            border: 1px solid #333;
            position: relative;
            box-shadow: 0 0 50px rgba(0,0,0,1);
        }

        .terminal-header {
            background: #111;
            padding: 12px;
            font-size: 0.6rem;
            color: var(--neon-cyan);
            border-bottom: 1px solid #222;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .status-dot { 
            width: 8px; height: 8px; 
            background: var(--neon-green); 
            border-radius: 50%; 
            box-shadow: 0 0 10px var(--neon-green);
            animation: pulse-dot 1.5s infinite;
        }

        /* Pantalla de Juego Activo */
        .game-screen { padding: 40px; text-align: center; }
        .game-title { font-size: 1.4rem; letter-spacing: 4px; margin-bottom: 5px; text-shadow: 0 0 10px var(--neon-cyan); }
        .game-instruction { font-family: 'Rajdhani'; font-size: 0.85rem; color: #666; margin-bottom: 30px; }

        .energy-track {
            height: 40px; background: #000; margin: 15px 0;
            position: relative; border: 1px solid #222; overflow: hidden;
        }

        .target-zone {
            position: absolute; left: 70%; width: 15%; height: 100%;
            background: rgba(0, 212, 255, 0.1); border-left: 1px solid var(--neon-cyan); border-right: 1px solid var(--neon-cyan);
        }

        .energy-bar { position: absolute; left: 0; width: 30px; height: 100%; background: #fff; box-shadow: 0 0 15px #fff; }

        .btn-neon-game {
            background: transparent; border: 1px solid var(--neon-red); color: var(--neon-red);
            padding: 15px 40px; font-family: 'Orbitron'; cursor: pointer; transition: 0.3s;
        }

        .btn-neon-game:hover { background: var(--neon-red); color: #000; box-shadow: 0 0 30px var(--neon-red); }

        /* Pantalla de Recompensa Corregida (Tu Captura) */
        .reward-overlay {
            position: absolute; inset: 0; background: #000;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            z-index: 100; padding: 20px;
        }

        .reward-content { width: 100%; max-width: 450px; text-align: center; animation: fadeIn 0.5s ease-out; }

        .victory-title {
            color: var(--neon-cyan); font-size: 1.8rem; letter-spacing: 4px;
            margin: 0 0 20px 0; text-shadow: 0 0 15px var(--neon-cyan);
        }

        .trophy-display-wrapper { position: relative; margin: 10px 0; display: flex; justify-content: center; }

        .trophy-link { position: relative; display: block; transition: 0.3s; text-decoration: none; }
        .trophy-link:hover { transform: scale(1.05); }

        .trophy-img {
            width: 230px; height: auto; position: relative; z-index: 5;
            filter: drop-shadow(0 0 20px rgba(0, 212, 255, 0.4));
            animation: float 3s ease-in-out infinite;
        }

        .trophy-glow {
            position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
            width: 180px; height: 180px; background: radial-gradient(circle, rgba(0, 212, 255, 0.25) 0%, transparent 70%);
        }

        .trophy-name { font-family: 'Rajdhani'; color: #888; font-size: 0.8rem; letter-spacing: 2px; margin: 15px 0; }

        .reward-box {
            background: rgba(10, 10, 10, 0.8); border: 1px solid #222;
            padding: 20px; width: 90%; margin: 0 auto 25px auto;
        }

        .reward-label { font-size: 0.65rem; color: #555; display: block; margin-bottom: 10px; }

        .promo-code {
            font-size: 2.5rem; color: var(--neon-green); font-weight: bold;
            text-shadow: 0 0 10px var(--neon-green); line-height: 1;
        }

        .promo-coupon { color: var(--neon-green); font-size: 0.75rem; margin-top: 10px; opacity: 0.8; }

        .btn-back {
            display: block; width: 100%; background: var(--neon-cyan); color: #000;
            padding: 16px; text-decoration: none; font-weight: 900;
            font-size: 0.9rem; letter-spacing: 2px; transition: 0.3s;
        }

        .btn-back:hover { background: #fff; box-shadow: 0 0 20px #fff; }

        .btn-reset { background: none; border: none; color: #333; font-family: 'Orbitron'; cursor: pointer; font-size: 0.6rem; margin-top: 15px; }

        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-12px); } }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes pulse-dot { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }
        .hidden { display: none !important; }
    </style>
</head>
<body>

<div class="game-universe">
    <div class="game-terminal">
        <div class="terminal-header">
            <div class="status-dot"></div>
            <span>CONEXIÓN ESTABLE - PROTOCOLO DE EXTRACCIÓN v.9</span>
        </div>
        
        <div class="game-screen" id="game-core">
            <h2 class="game-title">NÚCLEO ARCANO</h2>
            <p class="game-instruction">Sincroniza los flujos de energía.<br>Usa [ESPACIO] para fijar la posición.</p>
            
            <div class="core-container">
                <div class="energy-track"><div class="target-zone"></div><div class="energy-bar" id="bar1"></div></div>
                <div class="energy-track"><div class="target-zone"></div><div class="energy-bar" id="bar2"></div></div>
                <div class="energy-track"><div class="target-zone"></div><div class="energy-bar" id="bar3"></div></div>
            </div>
            <div id="game-msg" class="message">SISTEMA EN ESPERA</div>
            <button id="action-btn" class="btn-neon-game">CARGAR NÚCLEO</button>
        </div>
        
        <div id="reward-screen" class="reward-overlay hidden">
            <div class="reward-content">
                <h2 class="victory-title">¡RELIQUIA DETECTADA!</h2>
                
                <div class="trophy-display-wrapper">
                    <a href="detalle_producto.php?id=<?= $id_secreto ?>" class="trophy-link">
                        <div class="trophy-glow"></div>
                        <img src="img/productos/figura_exclusiva.png" alt="Kaito" class="trophy-img">
                    </a>
                </div>
                
                <p class="trophy-name">PERSONAJE EXCLUSIVO: KAITO, EL COLECCIONISTA</p>
                
                <div class="reward-box">
                    <span class="reward-label">BONO DE EXTRACCIÓN:</span>
                    <div class="promo-code">5.00€</div>
                    <p class="promo-coupon">CUPÓN: RELIQUIA5</p>
                </div>
                
                <a href="catalogo.php" class="btn-back">VOLVER AL MERCADO</a>
                <button onclick="location.reload()" class="btn-reset">REINICIAR TERMINAL</button>
            </div>
        </div>
    </div>
</div>

<script>
    const bars = [
        { el: document.getElementById('bar1'), pos: 0, speed: 2.2, active: false, stopped: false },
        { el: document.getElementById('bar2'), pos: 0, speed: 3.8, active: false, stopped: false },
        { el: document.getElementById('bar3'), pos: 0, speed: 5.6, active: false, stopped: false }
    ];

    let currentBar = 0;
    let gameRunning = false;
    const actionBtn = document.getElementById('action-btn');

    function update() {
        if (!gameRunning) return;
        const b = bars[currentBar];
        if (b.active && !b.stopped) {
            b.pos += b.speed;
            if (b.pos > 94 || b.pos < 0) b.speed *= -1;
            b.el.style.left = b.pos + "%";
        }
        requestAnimationFrame(update);
    }

    function handleAction() {
        if (!gameRunning && currentBar === 0) {
            gameRunning = true;
            bars[0].active = true;
            actionBtn.innerText = "FIJAR FLUJO";
            update();
        } else if (gameRunning) {
            const b = bars[currentBar];
            if (b.pos >= 68 && b.pos <= 86) {
                b.stopped = true;
                b.el.style.background = "var(--neon-green)";
                currentBar++;
                if (currentBar < bars.length) {
                    bars[currentBar].active = true;
                } else {
                    gameRunning = false;
                    setTimeout(() => document.getElementById('reward-screen').classList.remove('hidden'), 500);
                }
            } else {
                location.reload();
            }
        }
    }

    actionBtn.onclick = handleAction;
    window.onkeydown = (e) => { if (e.code === "Space") { e.preventDefault(); handleAction(); } };
</script>

</body>
</html>
<?php include 'includes/pie.php'; ?>