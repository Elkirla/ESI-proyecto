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
    public function CedulaUruguaya($ci) {
    // Quitar puntos o guiones
    $ci = preg_replace('/\D/', '', $ci);

    // Debe tener 8 dígitos
    if (strlen($ci) != 8) return false;

    $digits = str_split($ci);
    $verificador = array_pop($digits); // último digito

    $multiplicadores = [2, 9, 8, 7, 6, 3, 4];
    $suma = 0;

    foreach ($digits as $i => $d) {
        $suma += $d * $multiplicadores[$i];
    }

    $resultado = (10 - ($suma % 10)) % 10;

    return $resultado == $verificador;
}

}
 