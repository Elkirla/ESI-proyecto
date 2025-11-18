<?php
class validator {

    private $errores = [];
 
    public function getErrores() {
        return $this->errores;
    }
 
public function validarUsuarioCambios($usuario, $personal) {
    $this->validarNombre($usuario->getNombre());
    $this->validarApellido($usuario->getApellido());
    $this->validarTelefono($usuario->getTelefono());
    $this->validarCI($usuario->getCi(), $usuario->getId(), $personal);
}
public function validarTexto($texto, $min = 1, $max = 255) {
    if (!isset($texto)) return false;

    $texto = trim($texto);

    if (strlen($texto) < $min || strlen($texto) > $max) {
        return false;
    } 
    if (!preg_match('/^[a-zA-Z0-9\-]+$/', $texto)) {
        return false;
    }

    return true;
}

public function validarEstadoUnidad($estado) {
    $estadosValidos = ["Cimientos", "Estructura", "Terminaciones", "Finalizada"];
    return in_array($estado, $estadosValidos);
}
public function validarID($id) {
    return filter_var($id, FILTER_VALIDATE_INT) && $id > 0;
}

public function validarCampoRequerido($valor) {
    return isset($valor) && trim($valor) !== "";
}
public function validarLongitudExacta($str, $len) {
    return strlen(trim($str)) === $len;
}


public function EsNumeroEnteroPositivo($valor) {
    return preg_match('/^[0-9]+$/', $valor) && intval($valor) > 0;
}
public function EsDiaDelMes($valor) {
    return preg_match('/^[0-9]+$/', $valor) && intval($valor) >= 1 && intval($valor) <= 31;
}

private function validarNombre($nombre) {
    if (empty($nombre)) {
        $this->errores["nombre"] = "El nombre es obligatorio";
        return;
    }

    if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $nombre)) {
        $this->errores["nombre"] = "El nombre solo puede contener letras";
        return;
    }

    if (strlen($nombre) < 3 || strlen($nombre) > 30) {
        $this->errores["nombre"] = "Debe tener entre 3 y 30 caracteres";
    }
}

private function validarApellido($apellido) {
    if (empty($apellido)) {
        $this->errores["apellido"] = "El apellido es obligatorio";
        return;
    }

    if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $apellido)) {
        $this->errores["apellido"] = "El apellido solo puede contener letras";
        return;
    }

    if (strlen($apellido) < 3 || strlen($apellido) > 30) {
        $this->errores["apellido"] = "Debe tener entre 3 y 30 caracteres";
    }
}

 
private function validarTelefono($telefono) {
    if (empty($telefono)) {
        $this->errores["telefono"] = "El teléfono es obligatorio";
        return;
    }

    // Si empieza con + lo quitamos para validar
    if ($telefono[0] === '+') {
        $telefono = substr($telefono, 1);
    }

    // Validar que el resto sean dígitos
    if (!ctype_digit($telefono)) {
        $this->errores["telefono"] = "El teléfono solo puede contener números (excepto un + al inicio)";
        return;
    } 
}


 
private function validarCI($ci, $idUsuario = null, $personal = false) { 

    require_once __DIR__ . '/../Modelos/UsuarioModelo.php';
    $modelo = new UsuarioModelo();

    // Caso personal: permitir misma CI sin error
    if ($personal) {

        // Si el usuario cambió su CI, verificar que no esté usada por OTRO
        if (!empty($ci) && $modelo->ExisteCIParaOtroUsuario($ci, $idUsuario)) {
            $this->errores["ci"] = "La CI ya está registrada por otro usuario";
            return;
        }

    } else {
        // Caso admin modificando otro usuario
        if (!empty($ci) && $idUsuario !== null && $modelo->ExisteCIParaOtroUsuario($ci, $idUsuario)) {
            $this->errores["ci"] = "La CI ya está registrada por otro usuario";
            return;
        }
    }

    // Validación numérica
    if (!ctype_digit($ci)) {
        $this->errores["ci"] = "La CI solo puede contener números";
        return;
    }  

    // Validación de algoritmo uruguayo
    
    if (!$this->CedulaUruguaya($ci)) {
        $this->errores["ci"] = "La CI ingresada no es válida";
    }
        
}


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
        $ci = preg_replace('/\D/', '', $ci);
        if (strlen($ci) != 8) return false;

        $digits = str_split($ci);
        $verificador = array_pop($digits);

        $multiplicadores = [2, 9, 8, 7, 6, 3, 4];
        $suma = 0;

        foreach ($digits as $i => $d) {
            $suma += $d * $multiplicadores[$i];
        }

        $resultado = (10 - ($suma % 10)) % 10;

        return $resultado == $verificador;
    }

}
