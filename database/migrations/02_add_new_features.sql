-- 02_add_new_features.sql

-- 1. Modificar tabla usuarios
ALTER TABLE usuarios ADD COLUMN two_factor_secret VARCHAR(255) DEFAULT NULL;
ALTER TABLE usuarios ADD COLUMN two_factor_enabled BOOLEAN DEFAULT FALSE;

-- 2. Crear tabla savings_goals (Objetivos de Ahorro)
CREATE TABLE IF NOT EXISTS savings_goals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    monto_objetivo DECIMAL(15,2) NOT NULL,
    monto_actual DECIMAL(15,2) DEFAULT 0.00,
    fecha_limite DATE NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- 3. Crear tabla subscriptions (Suscripciones y Gastos Recurrentes)
CREATE TABLE IF NOT EXISTS subscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    cuenta_id INT NOT NULL,
    categoria_id INT NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    monto DECIMAL(15,2) NOT NULL,
    frecuencia ENUM('mensual', 'anual') DEFAULT 'mensual',
    proximo_cobro DATE NOT NULL,
    activo BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (cuenta_id) REFERENCES cuentas(id) ON DELETE CASCADE,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE CASCADE
);

-- 4. Crear tabla investments (Inversiones)
CREATE TABLE IF NOT EXISTS investments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    tipo VARCHAR(50) NOT NULL, -- ej. 'plazo_fijo', 'cripto', 'fondo'
    monto_invertido DECIMAL(15,2) NOT NULL,
    valor_actual DECIMAL(15,2) NOT NULL,
    fecha_inicio DATE NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- 5. Crear tabla balance_history (Historial de Patrimonio Neto)
CREATE TABLE IF NOT EXISTS balance_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    fecha DATE NOT NULL,
    patrimonio_neto DECIMAL(15,2) NOT NULL,
    UNIQUE KEY (usuario_id, fecha),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);
