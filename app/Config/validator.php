<?php
class validator {
    public function Contraseña($password) {
        $errors = [];

        if (strlen($password) < 8) $errors[] = "Debe tener al menos 8 caracteres.";
        if (!preg_match('/[A-Z]/', $password)) $errors[] = "Debe tener al menos una mayúscula.";
        if (!preg_match('/[a-z]/', $password)) $errors[] = "Debe tener al menos una minúscula.";
        if (!preg_match('/[0-9]/', $password)) $errors[] = "Debe tener al menos un número.";
        if (!preg_match('/[\W]/', $password)) $errors[] = "Debe tener al menos un símbolo.";

        return $errors;
    }
    public function Email($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}
?>