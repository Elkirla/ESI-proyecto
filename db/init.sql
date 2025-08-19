CREATE DATABASE buceo;
USE buceo;
CREATE TABLE inmersiones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lugar VARCHAR(100),
    fecha DATE
);
INSERT INTO inmersiones (lugar, fecha) VALUES ('Isla de Lobos', '2025-01-15');