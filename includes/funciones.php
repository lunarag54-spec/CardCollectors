<?php
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