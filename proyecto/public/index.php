<?php
// public/index.php

session_start();

// 1. Cargar el Autoloader de Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Forzar salida en UTF-8 en todas las respuestas HTML
header('Content-Type: text/html; charset=utf-8');

// 2. Capturar y limpiar la URL
$urlStr = $_GET['url'] ?? 'Home/index';

// Si viene con formato mixto (ej. proyecto/comunidades_editar&id=3 o similar), lo limpiamos
$urlStr = str_replace(['?', '&'], '/', $urlStr);

$url = explode('/', rtrim($urlStr, '/'));

$controllerName = "App\\Controllers\\" . ucfirst($url[0]) . "Controller";
$methodName = isset($url[1]) ? $url[1] : 'index';

// 3. Capturar parámetros extras de la URL (Como el ID)
$params = array_slice($url, 2);

// Si los parámetros por barra están vacíos, pero hay algo en $_GET['id'], lo usamos como primer parámetro
if (empty($params) && isset($_GET['id'])) {
    $params[] = $_GET['id'];
}

// 4. Enrutador Inteligente a prueba de fallos
if (class_exists($controllerName)) {
    $controller = new $controllerName();
    if (method_exists($controller, $methodName)) {
        // Ejecutamos el método pasándole los parámetros limpios (como el ID)
        $controller->$methodName(...$params);
    } else {
        echo "Error 404: El método '$methodName' no existe en el controlador '$controllerName'.";
    }
} else {
    echo "Error 404: El controlador '$controllerName' no existe.";
}