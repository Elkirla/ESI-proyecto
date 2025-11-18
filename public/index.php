<?php
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

spl_autoload_register(function($class){
$paths = [
    __DIR__ . '/../app/Controladores/',
    __DIR__ . '/../app/Modelos/',
    __DIR__ . '/../app/Entidades/',
    __DIR__ . '/../app/Config/',
    __DIR__ . '/../Utils/',
];

    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Rutas
require_once __DIR__ . '/../app/Config/routes.php';


// Obtener la URL completa
$uri = $_SERVER['REQUEST_URI'];

// Eliminar query string si existe
$uri = strtok($uri, '?');

// Obtener script y carpeta actual
$scriptName = $_SERVER['SCRIPT_NAME'];
$scriptDir = dirname($scriptName);

// Quitar /index.php o la carpeta raíz para obtener solo la ruta útil
if (strpos($uri, $scriptName) === 0) {
    $uri = substr($uri, strlen($scriptName));
} elseif (strpos($uri, $scriptDir) === 0) {
    $uri = substr($uri, strlen($scriptDir));
}

// Normalizar quitando /public si aparece
$uri = str_replace('/public', '', $uri);

// Asegurar formato `/ruta`
$uri = '/' . trim($uri, '/');

// Buscar ruta en el mapa
$method = $_SERVER['REQUEST_METHOD'];

if (isset($routes[$method][$uri])) {
    $controllerAction = $routes[$method][$uri];
    list($controllerName, $methodName) = explode('@', $controllerAction);

    if (class_exists($controllerName)) {
        $controller = new $controllerName();
        if (method_exists($controller, $methodName)) {
            $controller->$methodName();
        } else {
            http_response_code(500);
            echo "Método $methodName no encontrado en $controllerName";
        }
    } else {
        http_response_code(500);
        echo "Controlador $controllerName no encontrado";
    }
} else {
    http_response_code(404);
    include __DIR__ . '/../app/vistas/404.php';

}
