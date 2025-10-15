<?php
class Uploads {

    private $uploadsDir;
    private $allowedTypes;
    private $maxSize;

    public function __construct(
        $uploadsDir = '/var/www/html/public/uploads/',
        $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'],
        $maxSize = 5242880 // 5MB
    ) {
        $this->uploadsDir = $uploadsDir;
        $this->allowedTypes = $allowedTypes;
        $this->maxSize = $maxSize;

        // Asegurar directorio
$uploadDir = '/var/www/html/public/uploads/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0775, true);
    chown($uploadDir, 'www-data');
    chgrp($uploadDir, 'www-data');
}
        if (!is_writable($this->uploadsDir)) {
            throw new Exception("El directorio de subidas no es escribible.");
        }

    }

    public function subirArchivo($fileInputName) {
        if (!isset($_FILES[$fileInputName])) {
            throw new Exception("No se recibió el archivo.");
        }

        $file = $_FILES[$fileInputName];

        // Validar errores
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception($this->getUploadErrorMessage($file['error']));
        }

        // Validar tipo MIME
        $fileType = mime_content_type($file['tmp_name']);
        if (!in_array($fileType, $this->allowedTypes)) {
            throw new Exception("Tipo de archivo no permitido. Use JPEG, PNG o PDF.");
        }

        // Validar tamaño
        if ($file['size'] > $this->maxSize) {
            throw new Exception("El archivo es demasiado grande. Máximo permitido: " . ($this->maxSize / 1024 / 1024) . " MB.");
        }

        // Generar nombre seguro
        $nombreSeguro = uniqid() . "_" . preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($file['name']));
        $destino = $this->uploadsDir . $nombreSeguro;

        // Mover archivo
        if (!move_uploaded_file($file['tmp_name'], $destino)) {
            throw new Exception("Error al guardar el archivo en el servidor.");
        }

        // Retornar ruta relativa para la BD
        return "/uploads/" . $nombreSeguro;
    }

    private function getUploadErrorMessage($errorCode) {
        $errors = [
            UPLOAD_ERR_INI_SIZE => 'El archivo excede el tamaño máximo permitido por el servidor.',
            UPLOAD_ERR_FORM_SIZE => 'El archivo excede el tamaño máximo permitido por el formulario.',
            UPLOAD_ERR_PARTIAL => 'El archivo se subió parcialmente.',
            UPLOAD_ERR_NO_FILE => 'No se seleccionó ningún archivo.',
            UPLOAD_ERR_NO_TMP_DIR => 'No hay carpeta temporal en el servidor.',
            UPLOAD_ERR_CANT_WRITE => 'No se pudo escribir el archivo en el disco.',
            UPLOAD_ERR_EXTENSION => 'Una extensión de PHP detuvo la subida del archivo.'
        ];
        return $errors[$errorCode] ?? 'Error desconocido al subir archivo.';
    }
}
