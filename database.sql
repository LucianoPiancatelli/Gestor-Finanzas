-- Creamos la base de datos con soporte completo para caracteres especiales y emojis (utf8mb4)
CREATE DATABASE IF NOT EXISTS finanzas_personales CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE finanzas_personales;

-- Tabla de Usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de Categorías (Sirve tanto para gastos como para ingresos)
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    tipo ENUM('ingreso', 'gasto') NOT NULL
);

-- Tabla de Transacciones
CREATE TABLE transacciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    categoria_id INT NOT NULL,
    monto DECIMAL(10, 2) NOT NULL,
    descripcion VARCHAR(255),
    fecha DATE NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_categoria FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE RESTRICT
);

-- Inserts de prueba
INSERT INTO categorias (nombre, tipo) VALUES 
('Salario', 'ingreso'),
('Ventas / Freelance', 'ingreso'),
('Alquiler / Hipoteca', 'gasto'),
('Comida y Supermercado', 'gasto'),
('Transporte', 'gasto'),
('Entretenimiento', 'gasto'),
('Salud', 'gasto');
