SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- 1. PREPARACIÓN DE LA BASE DE DATOS
CREATE DATABASE IF NOT EXISTS TiendaColeccionismo;
USE TiendaColeccionismo;

-- 2. LIMPIEZA DE TABLAS EXISTENTES
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `detalle_compra`, `compra`, `carrito_producto`, `carrito`, `producto`, `administrador`, `categoria`, `usuario`, `libros`;
SET FOREIGN_KEY_CHECKS = 1;

-- --------------------------------------------------------
-- 3. CREACIÓN DE ESTRUCTURAS
-- --------------------------------------------------------

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('usuario','admin') DEFAULT 'usuario',
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `administrador` (
  `id_admin` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_admin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_categoria` varchar(50) NOT NULL,
  `tipo_libro` enum('Manga','Comic','Novela') DEFAULT NULL,
  `id_padre` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `producto` (
  `id_producto` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT 'default.jpg',
  `id_categoria` int(11) DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `estado` enum('activo','inactivo') DEFAULT 'activo',
  `id_vendedor` int(11) DEFAULT NULL,
  `imagen_especial` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `carrito` (
  `id_carrito` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT 0.00,
  PRIMARY KEY (`id_carrito`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `carrito_producto` (
  `id_carrito` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT 1,
  PRIMARY KEY (`id_carrito`,`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- 4. VOLCADO DE DATOS (USUARIOS Y CATEGORÍAS)
-- --------------------------------------------------------

-- Passwords: admin123 y 1234
INSERT INTO `usuario` (`id_usuario`, `nombre`, `email`, `password`, `rol`) VALUES
(1, 'Administrador', 'admin@tienda.com', '$2y$10$0kA8FKlhTHivTn53ZMq0ieNx/eebl89hNUOBYCZpsRmiLVXTGhHDK', 'admin'),
(2, 'Mario', 'mariogalvezfuentes10@gmail.com', '$2y$10$8W3nLq7F4M.uH.E9pG9u9eD8S8S8S8S8S8S8S8S8S8S8S8S8S8S8S', 'usuario');

INSERT INTO `administrador` (`id_usuario`) VALUES (1);

INSERT INTO `categoria` (`id_categoria`, `nombre_categoria`, `tipo_libro`, `id_padre`) VALUES
(1, 'Cartas', NULL, NULL),
(3, 'Libros', 'Manga', NULL),
(4, 'Libros', 'Comic', NULL),
(5, 'Libros', 'Novela', NULL),
(6, 'Cartas Pokémon', NULL, 1),
(7, 'Cartas Magic', NULL, 1),
(8, 'Figuras', NULL, NULL);

-- --------------------------------------------------------
-- 5. VOLCADO DE PRODUCTOS (COLECCIÓN COMPLETA)
-- --------------------------------------------------------

INSERT INTO `producto` (`id_producto`, `nombre`, `descripcion`, `imagen`, `id_categoria`, `precio`, `stock`, `estado`, `id_vendedor`, `imagen_especial`) VALUES
(1, 'Baraja Pokémon', 'Pack de inicio', 'baraja.jpg', 6, 19.99, 10, 'activo', 1, NULL),
(2, 'Pikachu Illustrator', 'La carta más legendaria. 1998.', 'pikachu.jpg', 6, 5250000.00, 1, 'activo', 1, NULL),
(3, 'Charizard 1st Edition Shadowless', 'Icono máximo de los 90.', 'charizard.jpg', 6, 420000.00, 1, 'activo', 1, NULL),
(4, 'Lugia 1st Edition Neo Genesis', 'Joya de plata holográfica.', 'lugia.jpg', 6, 144000.00, 1, 'activo', 1, NULL),
(5, 'Rayquaza Gold Star', 'Forma variocolor (shiny).', 'rayquaza.jpg', 6, 45000.00, 1, 'activo', 1, NULL),
(6, 'Umbreon Gold Star', 'Pop Series 5 muy deseada.', 'umbreon.jpg', 6, 70000.00, 1, 'activo', 1, NULL),
(7, 'Tropical Wind (Hawaii 2001)', 'Exclusiva de Hawaii.', 'Tropical.png', 6, 65000.00, 1, 'activo', 1, NULL),
(8, 'Mario Pikachu', 'Crossover épico Japón.', 'MarioPikachu.jpg', 6, 15000.00, 2, 'activo', 1, NULL),
(9, 'Espeon Gold Star', 'Arte minimalista difícil de conseguir.', 'Espeon.jpg', 6, 40000.00, 1, 'activo', 1, NULL),
(10, 'Gengar Skyridge Holo', 'Ilustración atmosférica y técnica.', 'Gengar.jpg', 6, 5000.00, 3, 'activo', 1, NULL),
(11, 'Umbreon VMAX Alt Art', 'Conocida como Moonbreon.', 'umbreonvamx.jpg', 6, 950.00, 1, 'activo', 1, NULL),
(12, 'Giratina V (Alt Art)', 'Arte abstracto del Mundo Distorsión.', 'giratina.jpg', 6, 450.00, 1, 'activo', 1, NULL),
(13, 'Rayquaza VMAX (Alt Art)', 'Guardián de los cielos detallado.', 'rayquazavmax.jpg', 6, 400.00, 1, 'activo', 1, NULL),
(14, 'Charizard ex (Shiny SIR)', 'Fuego negro y diamantes.', 'charizardex.jpg', 6, 250.00, 2, 'activo', 1, 'charizardex3d.png'),
(15, 'Mew ex (Bubblegum SIR)', 'Mew flotando entre burbujas.', 'mew.jpg', 6, 120.00, 3, 'activo', 1, 'mew3d.png'),
(16, 'Black Lotus - Alpha Edition', 'Santo grial de Magic.', 'black_lotus.png', 7, 550000.00, 1, 'activo', 1, NULL),
(17, 'Ancestral Recall - Alpha Edition', 'Robar tres cartas por un maná.', 'ancestral_recall.jpg', 7, 38000.00, 1, 'activo', 1, NULL),
(18, 'Time Walk - Alpha Edition', 'Turno extra absoluto.', 'time_walk.jpg', 7, 32000.00, 1, 'activo', 1, NULL),
(19, 'Mox Sapphire - Alpha Edition', 'Maná azul gratis.', 'mox_sapphire.jpg', 7, 45000.00, 1, 'activo', 1, NULL),
(20, 'Mox Jet - Alpha Edition', 'Genera maná negro.', 'mox_jet.jpg', 7, 28000.00, 1, 'activo', 1, NULL),
(21, 'Mox Ruby - Alpha Edition', 'Maná rojo gratuito.', 'mox_ruby.jpg', 7, 25000.00, 1, 'activo', 1, NULL),
(22, 'Mox Emerald - Alpha Edition', 'Maná verde para aceleración.', 'mox_emerald.jpg', 7, 22000.00, 1, 'activo', 1, NULL),
(23, 'Mox Pearl - Alpha Edition', 'Mox de maná blanco.', 'mox_pearl.jpg', 7, 21000.00, 1, 'activo', 1, NULL),
(24, 'Timetwister - Alpha Edition', 'Reinicia el juego para todos.', 'timetwister.webp', 7, 24000.00, 1, 'activo', 1, NULL),
(25, 'The One Ring (Serialized 001/001)', 'Única copia en el mundo.', 'the_one_ring_serialized.jpg', 7, 2000000.00, 1, 'activo', 1, NULL),
(26, 'Booster Box Alpha (Sellada)', 'Caja original de 1993.', 'bb_alpha.png', 7, 220000.00, 1, 'activo', 1, NULL),
(27, 'Booster Box Beta (Sellada)', 'Segunda impresión original.', 'bb_beta.png', 7, 140000.00, 1, 'activo', 1, NULL),
(46, 'Estatua Lucy Deluxe', 'Cyberpunk Edgerunners 1/4.', 'lucy_edgerunners.png', 8, 1970.90, 2, 'activo', 1, NULL),
(47, 'Bardock Ultra HQS 1/4', 'Resina imponente con LED.', 'bardock_tsume.png', 8, 2699.99, 1, 'activo', 1, NULL),
(48, 'Luffy Bijutsu 1/4', 'Captura la esencia de Wano.', 'luffy_onepiece.png', 8, 869.00, 3, 'activo', 1, NULL),
(49, 'Gojo Satoru 1/4', 'Expansión de dominio premium.', 'gojo_satoru.png', 8, 575.00, 4, 'activo', 1, NULL),
(50, 'Nezuko Kamado Limited', 'Base temática de batalla.', 'nezuko_resina.png', 8, 899.00, 2, 'activo', 1, NULL),
(51, 'Freezer 4ta Forma', 'Acabados metalizados escala masiva.', 'freezer_final.png', 8, 2399.00, 1, 'activo', 1, NULL),
(52, 'Figura Umbreon Life-Size', 'Réplica a tamaño real.', 'umbreon_lifesize.png', 8, 450.00, 5, 'activo', 1, NULL),
(53, 'Rayquaza Crystal Statue', 'Cristal tallado con luz LED.', 'rayquaza_crystal.png', 8, 320.00, 10, 'activo', 1, NULL),
(54, 'Charizard G-Max Diorama', 'Escala masiva Gigamax.', 'charizard_gmax.png', 8, 550.00, 3, 'activo', 1, NULL),
(55, 'Mewtwo & Mew ArtFX', 'Duelo eterno dinámico.', 'mewtwo_mew.png', 8, 210.00, 8, 'activo', 1, NULL),
(56, 'Behelit Skull 1/4', 'Berserk con luz roja.', 'behelit_berserk.png', 8, 410.00, 6, 'activo', 1, NULL),
(57, 'Motoko Kusanagi 1/4', 'Estética ciberpunk pura.', 'motoko_ghost.png', 8, 820.90, 2, 'activo', 1, NULL),
(58, 'Astro Boy Mechanical Clear', '135cm de altura real.', 'astroboy_mechanical.png', 8, 25000.00, 1, 'activo', 1, NULL),
(59, 'Gundam RX-78-2 Gold', 'Bañada en oro de lujo.', 'gundam_gold.png', 8, 12500.00, 1, 'activo', 1, NULL),
(60, 'Ellie & Joel TLoU II', 'Hiperrealismo 60cm.', 'tlou_statue.png', 8, 1759.00, 3, 'activo', 1, NULL);

-- --------------------------------------------------------
-- 6. RESTRICCIONES Y FINALIZACIÓN
-- --------------------------------------------------------

INSERT INTO `carrito` (`id_usuario`, `total`) VALUES (2, 0.00);

ALTER TABLE `administrador` ADD CONSTRAINT `admin_user_fk` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);
ALTER TABLE `categoria` ADD CONSTRAINT `fk_cat_padre` FOREIGN KEY (`id_padre`) REFERENCES `categoria` (`id_categoria`);
ALTER TABLE `producto` ADD CONSTRAINT `prod_vend_fk` FOREIGN KEY (`id_vendedor`) REFERENCES `usuario` (`id_usuario`);
ALTER TABLE `producto` ADD CONSTRAINT `prod_cat_fk` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`);
ALTER TABLE `carrito` ADD CONSTRAINT `car_user_fk` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

COMMIT;