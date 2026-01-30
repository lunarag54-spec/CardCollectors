SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
--
-- Base de datos: `tiendacoleccionismo`
--
DROP DATABASE IF EXISTS TiendaColeccionismo; -- Para pruebas, eliminar si ya existe
CREATE DATABASE IF NOT EXISTS TiendaColeccionismo;
USE TiendaColeccionismo;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrador`
--

CREATE TABLE `administrador` (
  `id_admin` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Relación adicional para identificar usuarios con privilegios administrativos';

--
-- Volcado de datos para la tabla `administrador`
--

INSERT INTO `administrador` (`id_admin`, `id_usuario`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito`
--

CREATE TABLE `carrito` (
  `id_carrito` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Contenedor temporal de productos seleccionados por el usuario';

--
-- Volcado de datos para la tabla `carrito`
--

INSERT INTO `carrito` (`id_carrito`, `id_usuario`, `total`) VALUES
(1, 2, 0.00),
(2, 1, 0.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito_producto`
--

CREATE TABLE `carrito_producto` (
  `id_carrito` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Relación de muchos a muchos entre carritos y productos';

--
-- Volcado de datos para la tabla `carrito_producto`
--

INSERT INTO `carrito_producto` (`id_carrito`, `id_producto`, `cantidad`) VALUES
(2, 12, 1),
(2, 14, 1),
(2, 46, 1),
(2, 59, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL,
  `nombre_categoria` varchar(50) NOT NULL,
  `tipo_libro` enum('Manga','Comic','Novela') DEFAULT NULL,
  `id_padre` int(11) DEFAULT NULL,
  `imagen_categoria` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Clasificación de productos (Permite sub-tipos de Libros)';

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `nombre_categoria`, `tipo_libro`, `id_padre`, `imagen_categoria`) VALUES
(1, 'Cartas', NULL, NULL, NULL),
(3, 'Libros', 'Manga', NULL, 'categorias.jpg'),
(4, 'Libros', 'Comic', NULL, NULL),
(5, 'Libros', 'Novela', NULL, NULL),
(6, 'CARTAS POKEMÓN', NULL, 1, 'categoria_pokemon.png'),
(7, 'CARTAS MAGIC', NULL, 1, 'categoria_magic.png'),
(8, 'FIGURAS COLECCIONABLES', NULL, NULL, 'categoria_figuras.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra`
--

CREATE TABLE `compra` (
  `id_compra` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `fecha_compra` date NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `estado_pago` enum('pendiente','pagado','cancelado') DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Histórico de pedidos realizados por los usuarios';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_compra`
--

CREATE TABLE `detalle_compra` (
  `id_detalle` int(11) NOT NULL,
  `id_compra` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Desglose de productos incluidos en cada compra';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libros`
--

CREATE TABLE `libros` (
  `id` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `autor` varchar(100) NOT NULL,
  `anio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id_producto` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT 'default.jpg',
  `id_categoria` int(11) DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `estado` enum('activo','inactivo') DEFAULT 'activo',
  `id_vendedor` int(11) DEFAULT NULL,
  `imagen_especial` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Inventario de artículos vinculados a una categoría y un vendedor';

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id_producto`, `nombre`, `descripcion`, `imagen`, `id_categoria`, `precio`, `stock`, `estado`, `id_vendedor`, `imagen_especial`) VALUES
(1, 'Baraja Pokémon', 'Pack de inicio', 'baraja.jpg', 6, 19.99, 10, 'activo', 1, NULL),
(2, 'Pikachu Illustrator', 'La carta más legendaria. Entregada en 1998 a ganadores de ilustración. Solo existen 39 copias.', 'pikachu.jpg', 6, 5250000.00, 1, 'activo', NULL, NULL),
(3, 'Charizard 1st Edition Shadowless', 'El icono máximo de los 90. Versión sin sombra en el borde del arte, extremadamente rara.', 'charizard.jpg', 6, 420000.00, 1, 'activo', NULL, NULL),
(4, 'Lugia 1st Edition Neo Genesis', 'La joya de plata. Casi imposible de encontrar en estado perfecto por su brillo holográfico delicado.', 'lugia.jpg', 6, 144000.00, 1, 'activo', NULL, NULL),
(5, 'Rayquaza Gold Star', 'Proveniente de la expansión EX Deoxys. Representa al dragón legendario en su forma variocolor (shiny).', 'rayquaza.jpg', 6, 45000.00, 1, 'activo', NULL, NULL),
(6, 'Umbreon Gold Star', 'Una de las cartas promocionales de Eevee-lutions más deseadas de la Pop Series 5.', 'umbreon.jpg', 6, 70000.00, 1, 'activo', NULL, NULL),
(7, 'Tropical Wind (Hawaii 2001)', 'Carta promocional exclusiva del Tropical Mega Battle celebrado en Hawaii.', 'Tropical.png', 6, 65000.00, 1, 'activo', NULL, NULL),
(8, 'Mario Pikachu', 'Crossover épico lanzado solo en Japón. Pikachu luciendo el traje del fontanero más famoso.', 'MarioPikachu.jpg', 6, 15000.00, 2, 'activo', NULL, NULL),
(9, 'Espeon Gold Star', 'Al igual que Umbreon, una pieza de arte minimalista y extremadamente difícil de conseguir.', 'Espeon.jpg', 6, 40000.00, 1, 'activo', NULL, NULL),
(10, 'Gengar Skyridge Holo', 'Considerada por muchos la ilustración más atmosférica y técnica de Gengar en el TCG.', 'Gengar.jpg', 6, 5000.00, 3, 'activo', NULL, NULL),
(11, 'Umbreon VMAX Alt Art', 'Conocida como \"Moonbreon\". La carta más deseada de la era moderna, con un arte espectacular de Umbreon alcanzando la luna.', 'umbreonvamx.jpg', 6, 950.00, 1, 'activo', NULL, NULL),
(12, 'Giratina V (Alt Art)', 'De la expansión Origen Perdido. Su arte abstracto y detallado del Mundo Distorsión es una obra maestra técnica.', 'giratina.jpg', 6, 450.00, 1, 'activo', NULL, NULL),
(13, 'Rayquaza VMAX (Alt Art)', 'El guardián de los cielos en una de las ilustraciones más detalladas jamás impresas en la serie Cielos Evolutivos.', 'rayquazavmax.jpg', 6, 400.00, 1, 'activo', NULL, NULL),
(14, 'Charizard ex (Shiny SIR)', 'De Destinos de Paldea. El Charizard Shiny en su forma Teracristal. Un efecto visual de diamantes y fuego negro.', 'charizardex.jpg', 6, 250.00, 2, 'activo', NULL, 'charizardex3d.png'),
(15, 'Mew ex (Bubblegum SIR)', 'Una de las cartas más bonitas y coloridas de la era actual, con Mew flotando entre burbujas doradas.', 'mew.jpg', 6, 120.00, 3, 'activo', NULL, 'mew3d.png'),
(16, 'Black Lotus - Alpha Edition', 'La carta más icónica de Magic. En estado Alpha y graduada, es el santo grial de cualquier coleccionista.', 'black_lotus.png', 7, 550000.00, 1, 'activo', 1, NULL),
(17, 'Ancestral Recall - Alpha Edition', 'Parte del Power Nine. Permite robar tres cartas por un solo maná azul. Una ventaja injusta.', 'ancestral_recall.jpg', 7, 38000.00, 1, 'activo', 1, NULL),
(18, 'Time Walk - Alpha Edition', 'Toma un turno extra por solo dos manás. Prohibida en casi todos los formatos por su poder absoluto.', 'time_walk.jpg', 7, 32000.00, 1, 'activo', 1, NULL),
(19, 'Mox Sapphire - Alpha Edition', 'El más valioso de los Moxes. Proporciona maná azul gratis, acelerando el juego de forma devastadora.', 'mox_sapphire.jpg', 7, 45000.00, 1, 'activo', 1, NULL),
(20, 'Mox Jet - Alpha Edition', 'Artefacto que genera maná negro. Esencial en los mazos vintage más potentes de la historia.', 'mox_jet.jpg', 7, 28000.00, 1, 'activo', 1, NULL),
(21, 'Mox Ruby - Alpha Edition', 'Fuente de maná rojo gratuita. Una joya de la edición original de 1993.', 'mox_ruby.jpg', 7, 25000.00, 1, 'activo', 1, NULL),
(22, 'Mox Emerald - Alpha Edition', 'Gema que otorga maná verde. Crucial para la aceleración en mazos de criaturas grandes.', 'mox_emerald.jpg', 7, 22000.00, 1, 'activo', 1, NULL),
(23, 'Mox Pearl - Alpha Edition', 'El Mox de maná blanco. Completa el ciclo de las joyas originales del Power Nine.', 'mox_pearl.jpg', 7, 21000.00, 1, 'activo', 1, NULL),
(24, 'Timetwister - Alpha Edition', 'La única carta del Power Nine que no está prohibida en Commander. Reinicia el juego para todos.', 'timetwister.webp', 7, 24000.00, 1, 'activo', 1, NULL),
(25, 'The One Ring (Serialized 001/001)', 'Edición única de \"Tales of Middle-earth\". Solo existe una copia en el mundo de esta versión.', 'the_one_ring_serialized.jpg', 7, 2000000.00, 1, 'activo', 1, NULL),
(26, 'Booster Box Alpha Edition (Sellada)', 'Caja de sobres original de 1993. Contiene 36 sobres sin abrir. Una pieza de museo.', 'bb_alpha.png', 7, 220000.00, 1, 'activo', 1, NULL),
(27, 'Booster Box Beta Edition (Sellada)', 'Segunda impresión de la serie original. Posibilidad de encontrar un Black Lotus en su interior.', 'bb_beta.png', 7, 140000.00, 1, 'activo', 1, NULL),
(28, 'Collector Booster Box: Lord of the Rings (Special Ed.)', 'Edición especial con tratamientos de arte alternativo y cartas seriadas.', 'lotr_collector_magic.png', 7, 1750.00, 5, 'activo', 1, NULL),
(29, 'Modern Horizons 3 Collector Booster Box', 'Caja premium para el formato Modern con las cartas más potentes del meta actual.', 'mh3_collector.png', 7, 650.00, 12, 'activo', 1, NULL),
(30, 'Secret Lair: 30th Anniversary Countdown Kit', 'Colección exclusiva de 30 cartas conmemorativas, una por cada año de historia de Magic.', 'secret_lair_30.png', 7, 450.00, 8, 'activo', 1, NULL),
(46, 'Estatua Lucy Deluxe - Cyberpunk Edgerunners', 'Escala 1/4 por Masterline. 50cm de puro detalle neón. Versión Deluxe con piezas intercambiables.', 'lucy_edgerunners.png', 8, 1970.90, 2, 'activo', 1, NULL),
(47, 'Bardock Ultra HQS 1/4 - Tsume Art', 'Una de las resinas más imponentes de Dragon Ball. Escena épica con iluminación LED integrada.', 'bardock_tsume.png', 8, 2699.99, 1, 'activo', 1, NULL),
(48, 'Luffy Bijutsu 1/4 - One Piece', 'Estatua de alta gama que captura la esencia del Capitán en Wano. Detalles de pintura hechos a mano.', 'luffy_onepiece.png', 8, 869.00, 3, 'activo', 1, NULL),
(49, 'Gojo Satoru 1/4 - Jujutsu Kaisen', 'Figura de edición limitada. Representa la expansión de dominio con materiales traslúcidos premium.', 'gojo_satoru.png', 8, 575.00, 4, 'activo', 1, NULL),
(50, 'Nezuko Kamado - Demon Slayer (Limited)', 'Resina de gran formato con base temática de la batalla en el Distrito del Entretenimiento.', 'nezuko_resina.png', 8, 899.00, 2, 'activo', 1, NULL),
(51, 'Freezer 4ta Forma - Premium Statue', 'Escala masiva. El tirano galáctico con acabados metalizados y base de diorama volcánico.', 'freezer_final.png', 8, 2399.00, 1, 'activo', 1, NULL),
(52, 'Figura Umbreon Life-Size - Bandai Spirits', 'Réplica a tamaño real del Pokémon nocturno. Material de vinilo de alta densidad y acabado mate.', 'umbreon_lifesize.png', 8, 450.00, 5, 'activo', 1, NULL),
(53, 'Rayquaza Mega Evolution Crystal Statue', 'Figura de cristal tallado con luz LED interna. Solo disponible en los Pokémon Center de Japón.', 'rayquaza_crystal.png', 8, 320.00, 10, 'activo', 1, NULL),
(54, 'Charizard G-Max Diorama - Scale World', 'Representación masiva de Charizard Gigamax. Una de las figuras más grandes de la línea Scale World.', 'charizard_gmax.png', 8, 550.00, 3, 'activo', 1, NULL),
(55, 'Mewtwo & Mew 1/8 - Kotobukiya ArtFX', 'Estatua clásica que muestra el duelo eterno. Acabados dinámicos y gran fidelidad al diseño original.', 'mewtwo_mew.png', 8, 210.00, 8, 'activo', 1, NULL),
(56, 'Behelit Skull 1/4 - Berserk (With Light)', 'Del maestro Kentaro Miura. El cráneo con el Behelit, incluye iluminación ambiental roja.', 'behelit_berserk.png', 8, 410.00, 6, 'activo', 1, NULL),
(57, 'Motoko Kusanagi 1/4 - Ghost in the Shell', 'Estatua definitiva de la Mayor. Estética ciberpunk pura con cables de silicona realistas.', 'motoko_ghost.png', 8, 820.90, 2, 'activo', 1, NULL),
(58, 'Astro Boy Mechanical Clear - Real Size', 'Figura de 135cm que muestra los componentes internos del robot más famoso del anime.', 'astroboy_mechanical.png', 8, 25000.00, 1, 'activo', 1, NULL),
(59, 'Gundam RX-78-2 Gold Version', 'Edición conmemorativa bañada en oro. El Mobile Suit original en formato de lujo extremo.', 'gundam_gold.png', 8, 12500.00, 1, 'activo', 1, NULL),
(60, 'Ellie & Joel - The Last of Us Part II Statue', 'Estatua de resina premium de 60cm. Hiperrealismo en rostros y texturas de ropa.', 'tlou_statue.png', 8, 1759.00, 3, 'activo', 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('usuario','admin') DEFAULT 'usuario'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Registro central de usuarios y sus roles de acceso';

--
-- Volcado de datos para la tabla `usuario`
--
-- admin -> admin123, mario 1234
INSERT INTO `usuario` (`id_usuario`, `nombre`, `email`, `password`, `rol`) VALUES
(1, 'Administrador', 'admin@tienda.com', '$2y$10$0kA8FKlhTHivTn53ZMq0ieNx/eebl89hNUOBYCZpsRmiLVXTGhHDK', 'admin'),
(2, 'Mario', 'mariogalvezfuentes10@gmail.com', '$2y$10$8W3nLq7F4M.uH.E9pG9u9eD8S8S8S8S8S8S8S8S8S8S8S8S8S8S8S', 'usuario');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`id_admin`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`id_carrito`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `carrito_producto`
--
ALTER TABLE `carrito_producto`
  ADD PRIMARY KEY (`id_carrito`,`id_producto`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`),
  ADD KEY `fk_categoria_padre` (`id_padre`);

--
-- Indices de la tabla `compra`
--
ALTER TABLE `compra`
  ADD PRIMARY KEY (`id_compra`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_compra` (`id_compra`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `libros`
--
ALTER TABLE `libros`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `id_vendedor` (`id_vendedor`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administrador`
--
ALTER TABLE `administrador`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `carrito`
--
ALTER TABLE `carrito`
  MODIFY `id_carrito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `compra`
--
ALTER TABLE `compra`
  MODIFY `id_compra` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `libros`
--
ALTER TABLE `libros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD CONSTRAINT `administrador_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD CONSTRAINT `carrito_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `carrito_producto`
--
ALTER TABLE `carrito_producto`
  ADD CONSTRAINT `carrito_producto_ibfk_1` FOREIGN KEY (`id_carrito`) REFERENCES `carrito` (`id_carrito`),
  ADD CONSTRAINT `carrito_producto_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`);

--
-- Filtros para la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD CONSTRAINT `fk_categoria_padre` FOREIGN KEY (`id_padre`) REFERENCES `categoria` (`id_categoria`);

--
-- Filtros para la tabla `compra`
--
ALTER TABLE `compra`
  ADD CONSTRAINT `compra_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  ADD CONSTRAINT `detalle_compra_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compra` (`id_compra`),
  ADD CONSTRAINT `detalle_compra_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`);

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`id_vendedor`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `producto_ibfk_2` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`);
COMMIT;