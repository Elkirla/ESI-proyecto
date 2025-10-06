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
    descripcion TEXT,
    usuario_id INT UNIQUE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE horas_trabajadas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    fecha DATE NOT NULL,
    horas INT NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    UNIQUE (usuario_id, fecha)
);

CREATE TABLE justificativos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    fecha DATE NOT NULL,
    motivo TEXT NOT NULL,
    archivo_url VARCHAR(255),
    estado ENUM('pendiente', 'aprobado', 'rechazado') DEFAULT 'pendiente',
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE pagos_compensatorios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    fecha DATE NOT NULL,
    archivo_url VARCHAR(255),
    estado ENUM('pendiente', 'aprobado', 'rechazado') DEFAULT 'pendiente',
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

CREATE TABLE Pagos_Deudas(
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    correo VARCHAR(100) NOT NULL,
    mes VARCHAR(20) NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (correo) REFERENCES usuarios(email)
);

CREATE TABLE Horas_deuda(
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    horas INT NOT NULL,
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

CREATE TABLE configuracion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clave VARCHAR(50) UNIQUE NOT NULL,
    valor VARCHAR(255) NOT NULL
);

INSERT INTO configuracion (clave, valor) VALUES ('fecha_limite_pago', '10');

INSERT INTO configuracion (clave, valor) VALUES ('mensualidad', '30000');



/*
docker exec -it esi-proyecto-db-1 mysql -u usuariodb -ppassword cooperativa -e "SELECT * FROM usuarios;"

docker exec -it esi-proyecto-db-1 mysql -u usuariodb -ppassword cooperativa -e "UPDATE usuarios SET estado='activo' WHERE email='correo@gmail.com';"

*/