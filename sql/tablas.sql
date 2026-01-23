-- Crear la base de datos
DROP DATABASE IF EXISTS TiendaColeccionismo; -- Para pruebas, eliminar si ya existe
CREATE DATABASE IF NOT EXISTS TiendaColeccionismo;
USE TiendaColeccionismo;

-- Tabla Usuario
CREATE TABLE Usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE, 
    password VARCHAR(255) NOT NULL, 
    rol ENUM('usuario', 'admin') DEFAULT 'usuario'
) COMMENT='Registro central de usuarios y sus roles de acceso';

-- Tabla Categoria (CAMBIO CLAVE: Sin UNIQUE en nombre_categoria)
CREATE TABLE Categoria (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre_categoria VARCHAR(50) NOT NULL,
    tipo_libro ENUM('Manga','Comic','Novela') DEFAULT NULL
) COMMENT='Clasificación de productos (Permite sub-tipos de Libros)';

-- Tabla Producto
CREATE TABLE Producto (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    id_categoria INT,
    precio DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    id_vendedor INT,
    FOREIGN KEY (id_vendedor) REFERENCES Usuario(id_usuario),
    FOREIGN KEY (id_categoria) REFERENCES Categoria(id_categoria)
) COMMENT='Inventario de artículos vinculados a una categoría y un vendedor';

-- Tabla Compra
CREATE TABLE Compra (
    id_compra INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    fecha_compra DATE NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    estado_pago ENUM('pendiente', 'pagado', 'cancelado') DEFAULT 'pendiente',
    FOREIGN KEY (id_usuario) REFERENCES Usuario(id_usuario)
) COMMENT='Histórico de pedidos realizados por los usuarios';

-- Tabla Detalle_compra
CREATE TABLE Detalle_compra (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_compra INT,
    id_producto INT,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_compra) REFERENCES Compra(id_compra),
    FOREIGN KEY (id_producto) REFERENCES Producto(id_producto)
) COMMENT='Desglose de productos incluidos en cada compra';

-- Tabla Carrito
CREATE TABLE Carrito (
    id_carrito INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    total DECIMAL(10,2) DEFAULT 0,
    FOREIGN KEY (id_usuario) REFERENCES Usuario(id_usuario)
) COMMENT='Contenedor temporal de productos seleccionados por el usuario';

-- Tabla Carrito_Producto
CREATE TABLE Carrito_Producto (
    id_carrito INT,
    id_producto INT,
    cantidad INT DEFAULT 1,
    PRIMARY KEY (id_carrito, id_producto),
    FOREIGN KEY (id_carrito) REFERENCES Carrito(id_carrito),
    FOREIGN KEY (id_producto) REFERENCES Producto(id_producto)
) COMMENT='Relación de muchos a muchos entre carritos y productos';

-- Tabla Administrador
CREATE TABLE Administrador (
    id_admin INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    FOREIGN KEY (id_usuario) REFERENCES Usuario(id_usuario)
) COMMENT='Relación adicional para identificar usuarios con privilegios administrativos';

-- ==========================================================
-- INSERCIÓN DE DATOS INICIALES
-- ==========================================================

-- 1. Categorías detalladas
INSERT INTO Categoria (id_categoria, nombre_categoria, tipo_libro) VALUES 
(1, 'Cartas', NULL),
(2, 'Figuras', NULL),
(3, 'Libros', 'Manga'),
(4, 'Libros', 'Comic'),
(5, 'Libros', 'Novela');

-- 2. Usuario Administrador // pass = admin123
INSERT INTO Usuario (nombre, email, password, rol) 
VALUES ('Administrador', 'admin@tienda.com', '$2y$10$0kA8FKlhTHivTn53ZMq0ieNx/eebl89hNUOBYCZpsRmiLVXTGhHDK', 'admin');

-- 3. Vincular a la tabla Administrador
INSERT INTO Administrador (id_usuario) VALUES (1);

-- 4. Producto de prueba
INSERT INTO Producto (nombre, descripcion, id_categoria, precio, stock, estado, id_vendedor) 
VALUES ('Baraja Pokémon', 'Pack de inicio', 1, 19.99, 10, 'activo', 1);
-- 1. Más CARTAS (id_categoria = 1)
INSERT INTO Producto (nombre, descripcion, id_categoria, precio, stock, estado, id_vendedor) VALUES 
('Sobre de Mejora Pokémon', 'Sobre de 10 cartas de la última expansión.', 1, 4.50, 50, 'activo', 1),
('Caja de Entrenador Élite', 'Set completo con dados, fundas y sobres.', 1, 49.90, 5, 'activo', 1),
('Dragón Blanco de Ojos Azules', 'Carta legendaria de Yu-Gi-Oh! en excelente estado.', 1, 35.00, 2, 'activo', 1),
('Mazo de Inicio Magic MTG', 'Ideal para aprender a jugar.', 1, 15.00, 10, 'activo', 1),
('Funda Protectora (100u)', 'Paquete de fundas transparentes para cartas.', 1, 6.99, 100, 'activo', 1);

-- 2. FIGURAS (id_categoria = 2)
INSERT INTO Producto (nombre, descripcion, id_categoria, precio, stock, estado, id_vendedor) VALUES 
('Figura Goku Super Saiyan', 'Figura articulada de 15cm.', 2, 29.99, 8, 'activo', 1),
('Estatua Batman', 'Resina de alta calidad, edición limitada.', 2, 85.00, 3, 'activo', 1),
('Funko Pop! Iron Man', 'Figura de vinilo con base.', 2, 14.95, 20, 'activo', 1),
('Figura Luffy One Piece', 'Escala 1/10 con accesorios.', 2, 32.50, 6, 'activo', 1),
('Réplica Espada de Link', 'Mini miniatura de metal para exposición.', 2, 19.00, 15, 'activo', 1);

-- 3. LIBROS / MANGA (id_categoria = 3)
INSERT INTO Producto (nombre, descripcion, id_categoria, precio, stock, estado, id_vendedor) VALUES 
('One Piece Tomo 1', 'El inicio de la gran aventura pirata.', 3, 7.95, 25, 'activo', 1),
('Naruto Tomo 1', 'Primer volumen del ninja de la hoja.', 3, 7.95, 15, 'activo', 1),
('Death Note Black Edition', 'Tomo especial con páginas de mayor calidad.', 3, 14.50, 10, 'activo', 1),
('My Hero Academia Vol 1', 'Historia de superhéroes moderna.', 3, 8.00, 12, 'activo', 1),
('Berserk Maximum 1', 'Edición de lujo en gran formato.', 3, 15.00, 5, 'activo', 1);

-- 4. LIBROS / COMIC (id_categoria = 4)
INSERT INTO Producto (nombre, descripcion, id_categoria, precio, stock, estado, id_vendedor) VALUES 
('The Amazing Spider-Man', 'Grapa especial conmemorativa.', 4, 3.50, 30, 'activo', 1),
('Batman: Año Uno', 'Novela gráfica de Frank Miller.', 4, 18.00, 8, 'activo', 1),
('Watchmen', 'Edición integral de la obra maestra de Moore.', 4, 32.00, 4, 'activo', 1),
('Saga Vol 1', 'Aclamado cómic de ciencia ficción.', 4, 14.95, 6, 'activo', 1),
('X-Men: Días del futuro pasado', 'Clásico de Marvel tapa dura.', 4, 22.00, 3, 'activo', 1);

-- 5. LIBROS / NOVELA (id_categoria = 5)
INSERT INTO Producto (nombre, descripcion, id_categoria, precio, stock, estado, id_vendedor) VALUES 
('El Hobbit', 'Novela de J.R.R. Tolkien, edición de bolsillo.', 5, 12.00, 20, 'activo', 1),
('Dune', 'Clásico imprescindible de la ciencia ficción.', 5, 19.95, 15, 'activo', 1),
('Fundación', 'Obra de Isaac Asimov sobre el futuro de la humanidad.', 5, 10.50, 10, 'activo', 1),
('El Nombre del Viento', 'Fantasía moderna de Patrick Rothfuss.', 5, 21.00, 7, 'activo', 1),
('Crónicas de la Dragonlance', 'Tomo 1: El Retorno de los Dragones.', 5, 16.00, 9, 'activo', 1);