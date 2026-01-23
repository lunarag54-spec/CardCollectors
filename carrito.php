<?php
session_start();

/* Simulación del carrito */
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [
        [
            'nombre' => 'Camiseta',
            'precio' => 15.99,
            'cantidad' => 2
        ],
        [
            'nombre' => 'Pantalón',
            'precio' => 29.99,
            'cantidad' => 1
        ]
    ];
}

$carrito = $_SESSION['carrito'];
$total = 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi carrito</title>

    <style>
        :root {
            --primary-color: #383838;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #000;   /* negro */
            color: #fff;              /* texto blanco */
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;    
        }

        /* ===== BORDE ANIMADO ===== */
        .animated-border {
            position: relative;
            overflow: hidden;
            z-index: 0;
        }

        .animated-border::before {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: conic-gradient(
                transparent,
                #00d4ff,
                transparent,
                #ff0000,
                transparent
            );
            animation: rotateBorder 20s linear infinite;
            z-index: -2;
        }

        .animated-border::after {
            content: "";
            position: absolute;
            inset: 4px;
            background-color: var(--primary-color);
            border-radius: 10px;
            z-index: -1;
        }

        @keyframes rotateBorder {
            100% {
                transform: rotate(360deg);
            }
        }

        /* ===== CONTENIDO CARRITO ===== */
        .carrito-container {
            width: 100%;
            max-width: 600px;
            padding: 30px;
            border-radius: 10px;
        }

        .carrito-container h1 {
            text-align: center;
            margin-bottom: 25px;
            color: #ffffff;
        }

        .carrito-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #000000;
        }

        .carrito-item:last-child {
            border-bottom: none;
        }

        .item-info {
            font-size: 0.95rem;
        }

        .item-precio {
            font-weight: bold;
        }

        .total-carrito {
            margin-top: 25px;
            text-align: right;
            font-size: 1.2rem;
            font-weight: bold;
        }

        .acciones {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            gap: 15px;
        }

        .btn {
            flex: 1;
            text-align: center;
            padding: 12px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .btn-volver {
            background: #ccc;
            color: #333;
        }

        .btn-comprar {
            background: #302a2a;
            color: #fff;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .carrito-vacio {
            text-align: center;
            font-size: 1.1rem;
            padding: 20px;
        }

        /* Luces de fondo ambientales */
body::before, body::after {
    content: "";
    position: absolute;
    width: 400px;
    height: 400px;
    border-radius: 50%;
    filter: blur(120px);
    z-index: -1;
    opacity: 0.4;
}
body::before { background: #ff0000; top: -10%; left: -10%; } /* Brillo Rojo */
body::after { background: #00d4ff; bottom: -10%; right: -10%; } /* Brillo Azul */

.card-wrapper {
    position: relative;
    width: 380px;
    padding: 45px;
    background: rgba(10, 10, 10, 0.9);
    border-radius: 20px;
    color: white;
    box-shadow: 0 0 20px rgba(0,0,0,1);
    z-index: 1;
    overflow: hidden; /* Para que el brillo no se salga */
}

/* Luces de fondo dinámicas */
.cursor-glow-red, .cursor-glow-blue {
    position: fixed;
    top: 0;
    left: 0;
    width: 600px;
    height: 600px;
    border-radius: 50%;
    filter: blur(100px);
    z-index: -1;
    opacity: 0.6;
    pointer-events: none;
    transition: transform 0.1s ease-out;
}

.cursor-glow-red {
    background: radial-gradient(circle, rgba(255, 0, 0, 0.4) 0%, transparent 70%);
}

.cursor-glow-blue {
    background: radial-gradient(circle, rgba(0, 212, 255, 0.4) 0%, transparent 70%);
}


    </style>
</head>

<body>

<div class="carrito-container animated-border">
    <h1>Mi carrito</h1>

    <?php if (empty($carrito)): ?>
        <div class="carrito-vacio">
            El carrito está vacío
        </div>
    <?php else: ?>
        <?php foreach ($carrito as $producto): ?>
            <?php
                $subtotal = $producto['precio'] * $producto['cantidad'];
                $total += $subtotal;
            ?>
            <div class="carrito-item">
                <div class="item-info">
                    <strong><?= $producto['nombre']; ?></strong><br>
                    <?= $producto['cantidad']; ?> × <?= number_format($producto['precio'], 2); ?> €
                </div>
                <div class="item-precio">
                    <?= number_format($subtotal, 2); ?> €
                </div>
            </div>
            <div></div>
        <?php endforeach; ?>

        <div class="total-carrito">
            Total: <?= number_format($total, 2); ?> €
        </div>

        <div class="acciones">
            <a href="includes/header_footer.php" class="btn btn-volver">Seguir comprando</a>
            <a href="checkout.php" class="btn btn-comprar">Finalizar compra</a>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
