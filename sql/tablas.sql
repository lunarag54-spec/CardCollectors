-- Crear la base de datos
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

-- Tabla Categoria
CREATE TABLE Categoria (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre_categoria ENUM('Cartas', 'Figuras', 'Libros') NOT NULL UNIQUE,
    tipo_libro ENUM('Manga','Comic','Novela') DEFAULT NULL
) COMMENT='Clasificación fija de productos según el diseño de la tienda';

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