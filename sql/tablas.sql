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

-- 2. Usuario Administrador 
INSERT INTO Usuario (id_usuario, nombre, email, password, rol) 
VALUES (1, 'Administrador', 'admin@tienda.com', 'admin123', 'admin');

-- 3. Vincular a la tabla Administrador
INSERT INTO Administrador (id_usuario) VALUES (1);

-- 4. Producto de prueba
INSERT INTO Producto (nombre, descripcion, id_categoria, precio, stock, estado, id_vendedor) 
VALUES ('Baraja Pokémon', 'Pack de inicio', 1, 19.99, 10, 'activo', 1);
