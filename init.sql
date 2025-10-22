CREATE DATABASE IF NOT EXISTS cooperativa;
USE cooperativa;

CREATE USER IF NOT EXISTS 'usuariodb'@'%' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON cooperativa.* TO 'usuariodb'@'%';
FLUSH PRIVILEGES;

CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL COMMENT 'Nombre del rol: usuario, administrador'
); 

INSERT INTO roles (nombre) VALUES ('usuario');
INSERT INTO roles (nombre) VALUES ('administrador');

CREATE TABLE entrega (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL COMMENT 'Estado de la fecha en la que se entrego el pago'
); 
INSERT INTO entrega (nombre) VALUES ('Atrasado');
INSERT INTO entrega (nombre) VALUES ('En hora');

CREATE TABLE configuracion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clave VARCHAR(50) UNIQUE NOT NULL,
    valor VARCHAR(255) NOT NULL
);

INSERT INTO configuracion (clave, valor) VALUES 
('fecha_limite_pago', '10'),
('mensualidad', '30000'),
('horas_semanales', '21'),
('valor_semanal', '700'),
('cuota_semanal', '21');
 
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rol_id INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    telefono VARCHAR(20),
    ci VARCHAR(20) UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    estado ENUM('pendiente', 'activo', 'rechazado') DEFAULT 'pendiente',
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (rol_id) REFERENCES roles(id)
);
 
CREATE TABLE unidades_habitacionales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) UNIQUE NOT NULL,
    estado ENUM('Cimientos','Estructura','Terminaciones','Finalizada') NOT NULL
);

INSERT INTO unidades_habitacionales (codigo, estado) VALUES 
('U-001', 'Cimientos' ),
('U-002', 'Estructura' ),
('U-003', 'Terminaciones' );

CREATE TABLE usuarios_unidades (
    usuario_id INT NOT NULL,
    unidad_id INT NOT NULL,
    fecha_asignacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (usuario_id, unidad_id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (unidad_id) REFERENCES unidades_habitacionales(id) ON DELETE CASCADE
);

CREATE TABLE horas_trabajadas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    fecha DATE NOT NULL,
    horas DECIMAL(5,2) NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    UNIQUE (usuario_id, fecha)
);

CREATE TABLE justificativos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    fecha DATE NOT NULL,
    fecha_final DATE DEFAULT NULL,
    motivo TEXT NOT NULL,
    archivo_url VARCHAR(255),
    estado ENUM('pendiente', 'aprobado', 'rechazado') DEFAULT 'pendiente',
    horas_equivalentes DECIMAL(5,2) DEFAULT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE pagos_compensatorios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    fecha DATE NOT NULL,
    archivo_url VARCHAR(255),
    estado ENUM('pendiente', 'aprobado', 'rechazado') DEFAULT 'pendiente',
    horas DECIMAL(5,2) DEFAULT 0,
    fecha_inicio DATE DEFAULT NULL,
    fecha_fin DATE DEFAULT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE pagos_mensuales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    mes VARCHAR(20) NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    fecha DATE NOT NULL,
    archivo_url VARCHAR(255),
    estado ENUM('pendiente', 'aprobado', 'rechazado') DEFAULT 'pendiente',
    entrega ENUM('en_hora', 'atrasado') NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE Deudas_Mensuales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    correo VARCHAR(100) NOT NULL,
    mes VARCHAR(20) NOT NULL,  
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    adeudado TINYINT(1) DEFAULT 0,
    tiene_pago TINYINT(1) DEFAULT 0,
    procesado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_usuario_mes (usuario_id, mes),  
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (correo) REFERENCES usuarios(email)
);

-- Tabla resumen de deudas (única por usuario)
CREATE TABLE Pagos_Deudas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATE NOT NULL,
    usuario_id INT NOT NULL UNIQUE,
    correo VARCHAR(100) NOT NULL,
    meses INT NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    primer_mes_pendiente DATE DEFAULT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (correo) REFERENCES usuarios(email)
);

CREATE TABLE Semana_deudas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    horas_trabajadas DECIMAL(5,2) NOT NULL,
    horas_faltantes DECIMAL(5,2) NOT NULL,
    horas_justificadas DECIMAL(5,2) DEFAULT 0,
    horas_compensadas DECIMAL(5,2) DEFAULT 0,
    motivo_justificacion TEXT DEFAULT NULL,
    pago_compensatorio_id INT DEFAULT NULL,
    procesado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_usuario_semana (usuario_id, fecha_inicio),
    UNIQUE (usuario_id, fecha_inicio, fecha_fin),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (pago_compensatorio_id) REFERENCES pagos_compensatorios(id)
);

ALTER TABLE Semana_deudas
ADD UNIQUE KEY uk_usuario_fecha_inicio (usuario_id, fecha_inicio);

CREATE TABLE Horas_deuda (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL UNIQUE,  
    horas_acumuladas DECIMAL(8,2) NOT NULL DEFAULT 0,
    horas_deuda_total DECIMAL(8,2) DEFAULT 0,
    fecha_ultimo_calculo DATE DEFAULT NULL,
    primera_semana_pendiente DATE DEFAULT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE Notificaciones(
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    mensaje TEXT NOT NULL,
    leido BOOLEAN DEFAULT FALSE,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

/*

docker exec -it esi-proyecto-db-1 mysql -u usuariodb -ppassword cooperativa -e "UPDATE usuarios SET estado='activo' WHERE email='correo@gmail.com';"

*/