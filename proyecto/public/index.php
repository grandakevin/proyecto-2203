<?php
// public/index.php

session_start();

// 1. Cargar el Autoloader de Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Forzar salida en UTF-8 en todas las respuestas HTML
header('Content-Type: text/html; charset=utf-8');

// 2. Capturar la URL
$url = isset($_GET['url']) ? explode('/', rtrim($_GET['url'], '/')) : ['Home', 'index'];

$controllerName = "App\\Controllers\\" . ucfirst($url[0]) . "Controller";
$methodName = isset($url[1]) ? $url[1] : 'index';
$params = array_slice($url, 2);

// 3. Enrutador básico con parámetros opcionales
if (class_exists($controllerName)) {
    $controller = new $controllerName();
    if (method_exists($controller, $methodName)) {
        $controller->$methodName(...$params);
    } else {
        echo "Error 404: El método '$methodName' no existe.";
    }
} else {
    echo "Error 404: El controlador '$controllerName' no existe.";
}
