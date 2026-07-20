-- Migraciones para Nivel 2
USE finanzas_personales;

-- 1. Crear tabla de cuentas
CREATE TABLE IF NOT EXISTS cuentas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    saldo DECIMAL(10, 2) DEFAULT 0.00,
    tipo ENUM('Efectivo', 'Banco', 'Tarjeta') NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_cuenta_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- 2. Crear cuenta por defecto para usuarios existentes
INSERT INTO cuentas (usuario_id, nombre, saldo, tipo)
SELECT id, 'Billetera Principal', 0.00, 'Efectivo' FROM usuarios;

-- 3. Añadir cuenta_id, es_recurrente y frecuencia a transacciones
ALTER TABLE transacciones 
ADD COLUMN cuenta_id INT NULL AFTER categoria_id,
ADD COLUMN es_recurrente BOOLEAN DEFAULT FALSE,
ADD COLUMN frecuencia ENUM('mensual', 'anual') NULL;

-- 4. Asignar cuenta por defecto a transacciones existentes
UPDATE transacciones t
JOIN cuentas c ON t.usuario_id = c.usuario_id
SET t.cuenta_id = c.id;

-- 5. Hacer cuenta_id NOT NULL y añadir Foreign Key
ALTER TABLE transacciones MODIFY COLUMN cuenta_id INT NOT NULL;
ALTER TABLE transacciones ADD CONSTRAINT fk_transaccion_cuenta FOREIGN KEY (cuenta_id) REFERENCES cuentas(id) ON DELETE CASCADE;

-- 6. Crear tabla de presupuestos
CREATE TABLE IF NOT EXISTS presupuestos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    categoria_id INT NOT NULL,
    monto_limite DECIMAL(10, 2) NOT NULL,
    mes INT NOT NULL,
    anio INT NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_presupuesto_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_presupuesto_categoria FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE CASCADE,
    UNIQUE KEY uk_presupuesto_mes_categoria (usuario_id, categoria_id, mes, anio)
);
