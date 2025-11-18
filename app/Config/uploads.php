<?php
class Uploads {

    private $uploadsDir;
    private $allowedTypes;
    private $maxSize;

    public function __construct(
        $relativePath = 'uploads/', // Carpeta dentro de public/
        $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'],
        $maxSize = 5242880 // 5MB
    ) {
        // Ruta absoluta correcta: siempre dentro de public/
        $this->uploadsDir = rtrim($_SERVER['DOCUMENT_ROOT'] . '/' . $relativePath, '/') . '/';
        $this->allowedTypes = $allowedTypes;
        $this->maxSize = $maxSize;

        // Crear carpeta si no existe
        if (!is_dir($this->uploadsDir)) {
            mkdir($this->uploadsDir, 0777, true);
        }

        if (!is_writable($this->uploadsDir)) {
            throw new Exception("El directorio '{$this->uploadsDir}' no es escribible.");
        }
    }

    public function subirArchivo($fileInputName) {
        if (!isset($_FILES[$fileInputName])) {
            throw new Exception("No se recibió el archivo.");
        }

        $file = $_FILES[$fileInputName];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception($this->getUploadErrorMessage($file['error']));
        }

        $fileType = mime_content_type($file['tmp_name']);
        if (!in_array($fileType, $this->allowedTypes)) {
            throw new Exception("Tipo de archivo no permitido (JPEG, PNG o PDF).");
        }

        if ($file['size'] > $this->maxSize) {
            throw new Exception("Archivo demasiado grande. Máximo 5MB.");
        }

        $nombreSeguro = uniqid() . "_" . preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($file['name']));
        $destino = $this->uploadsDir . $nombreSeguro;

        if (!move_uploaded_file($file['tmp_name'], $destino)) {
            throw new Exception("No se pudo guardar el archivo.");
        }

        // Ruta para la BD (desde la raíz pública)
        return "/uploads/" . $nombreSeguro;
    }

    private function getUploadErrorMessage($errorCode) {
        $errors = [
            UPLOAD_ERR_INI_SIZE => 'Archivo excede el tamaño permitido por el servidor.',
            UPLOAD_ERR_FORM_SIZE => 'Archivo excede el tamaño permitido por el formulario.',
            UPLOAD_ERR_PARTIAL => 'Archivo subido parcialmente.',
            UPLOAD_ERR_NO_FILE => 'No se seleccionó ningún archivo.',
            UPLOAD_ERR_NO_TMP_DIR => 'Falta carpeta temporal en el servidor.',
            UPLOAD_ERR_CANT_WRITE => 'Error al escribir en el disco.',
            UPLOAD_ERR_EXTENSION => 'Extensión PHP detuvo la subida.'
        ];
        return $errors[$errorCode] ?? 'Error desconocido al subir archivo.';
    }
}
