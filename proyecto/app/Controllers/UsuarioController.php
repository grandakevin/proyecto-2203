<?php
namespace App\Controllers;

use App\Models\Usuario;

class UsuarioController {
    
    // Muestra la vista de gestión de usuarios
    public function index() {
        // Aquí incluirás la vista de usuarios cuando la tengamos mapeada
        echo "Cargando pantalla de Gestión de Usuarios (CUS-06)";
    }

    // Procesa el formulario de +crear()
    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $modeloUsuario = new Usuario();
            
            $datos = [
                'cedula'   => $_POST['cedula'] ?? '',
                'nombre'   => $_POST['nombre'] ?? '',
                'apellido' => $_POST['apellido'] ?? '',
                'correo'   => $_POST['correo'] ?? '',
                'clave'    => $_POST['clave'] ?? ''
            ];

            // Validación básica
            if (!empty($datos['correo']) && !empty($datos['clave'])) {
                $modeloUsuario->crear($datos);
                // Al guardar, redirecciona de vuelta a la lista de usuarios
                header('Location: /proyecto/public/usuarios');
                exit;
            }
        }
    }
}