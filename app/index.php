<?php 

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

    // Cargar archivo del controlador
    require_once __DIR__ . "/Controladores/{$controllerName}.php";

    // Instanciar y llamar al método
    $controller = new $controllerName();
    $controller->$methodName();
} else {
    http_response_code(404);
    echo "Página no encontrada";
}
