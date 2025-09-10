<?php

spl_autoload_register(function($class){
    $paths = [
        __DIR__ . '/Controladores/',
        __DIR__ . '/Modelos/',
        __DIR__ . '/Entidades/',
        __DIR__ . '/Config/',
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
require_once __DIR__ . '/Config/routes.php';

// Obtener la URL solicitada
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Normalizar quitando /public si aparece
$uri = str_replace('/public', '', $uri);

// Buscar ruta en el mapa
$method = $_SERVER['REQUEST_METHOD'];

if (isset($routes[$method][$uri])) {
    $controllerAction = $routes[$method][$uri];
    list($controllerName, $methodName) = explode('@', $controllerAction);

    // Instanciar y llamar al método
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
    include __DIR__ . '/Vistas/404.php';
}
