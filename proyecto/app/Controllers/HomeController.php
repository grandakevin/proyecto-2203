<?php
namespace App\Controllers;

use App\Models\Proyecto;

class HomeController {
    public function index() {
        $proyectoModel = new Proyecto();
        $proyectos = $proyectoModel->obtenerTodos();

        $totalProyectos = count($proyectos);
        $totalActivos = count(array_filter($proyectos, function ($proyecto) {
            return $proyecto['estado'] === 'Activo';
        }));
        $totalComunidades = count(array_unique(array_filter(array_map(function ($proyecto) {
            return trim($proyecto['comunidad']);
        }, $proyectos))));
        $totalProyectosConIntegrantes = count(array_filter($proyectos, function ($proyecto) {
            return intval($proyecto['cantidadIntegrantes'] ?? 0) > 0;
        }));
        $totalEvaluaciones = 0;
        $proyectosRecientes = array_slice($proyectos, 0, 5);

        include __DIR__ . '/../Views/dashboard.php';
    }
}