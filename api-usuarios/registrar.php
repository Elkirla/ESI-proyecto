<?php
// Datos de conexión
$host = "127.0.0.1";
$usuario = "root";
$contrasena = "tortadesapallo";
$base_de_datos = "cooperativa_vivienda";
$puerto = 3306;

// Conexión
$conn = new mysqli($host, $usuario, $contrasena, $base_de_datos, $puerto);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Recibir datos del formulario
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$nombre_completo = $nombre . ' ' . $apellido;
$ci = $_POST['ci'];
$fecha_nacimiento = $_POST['fecha_nacimiento'];
$genero = $_POST['genero'];
$telefono = $_POST['telefono'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$terminos = isset($_POST['terminos']);

// Validación básica
if ($password !== $confirm_password) {
    die("Las contraseñas no coinciden.");
}

if (!$terminos) {
    die("Debes aceptar los términos y condiciones >:(");
}

// Encriptar contraseña
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// rol_id: Asumimos 1 para usuario normal
$rol_id = 1;

// Insertar usuario
$sql = "INSERT INTO usuarios (rol_id, nombre_completo, email, telefono, ci, password_hash)
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isssss", $rol_id, $nombre_completo, $email, $telefono, $ci, $password_hash);

if ($stmt->execute()) {
    echo "Usuario registrado correctamente.";
} else {
    echo "Error: algo malio sal :(" . $stmt->error;
}

$stmt->close();
$conn->close();
?>
