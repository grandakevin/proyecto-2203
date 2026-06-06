<?php

class ComunidadController {
    private $modelo;
    private $conexion;

    // El constructor recibe la conexión para poder instanciar el modelo de comunidades
    public function __construct($conexion) {
        $this->conexion = $conexion;
        // Cargamos el modelo automáticamente al instanciar el controlador
        require_once __DIR__ . '/../Models/ComunidadModel.php';
        $this->modelo = new ComunidadModel($this->conexion);
    }

    // 1. Mostrar la lista de comunidades (Vista Principal)
    public function index() {
        // Buscamos todas las comunidades en la Base de Datos
        $comunidades = $this->modelo->obtenerComunidades();
        
        // Cargamos las vistas que componen la interfaz modular
        require_once __DIR__ . '/../Views/layouts/header.php';
        require_once __DIR__ . '/../Views/layouts/sidebar.php';
        require_once __DIR__ . '/../Views/comunidades/comunidades.php'; // Tu vista principal
        require_once __DIR__ . '/../Views/layouts/footer.php';
    }

    // 2. Procesar el formulario de registro (Crear)
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitización de los datos de entrada
            $nombre = trim($_POST['nombre']);
            $direccion = trim($_POST['direccion']);
            $tipo_comunidad = trim($_POST['tipo_comunidad']);

            // Validación simple de campos requeridos
            if (!empty($nombre) && !empty($direccion)) {
                $resultado = $this->modelo->insertarComunidad($nombre, $direccion, $tipo_comunidad);
                if ($resultado) {
                    // Redirección limpia para evitar duplicar registros al recargar
                    header("Location: /proyecto/public/index.php?url=comunidades/index");
                    exit();
                }
            }
        }
        
        // Si no es POST o falla, mostramos el formulario de inserción
        require_once __DIR__ . '/../Views/layouts/header.php';
        require_once __DIR__ . '/../Views/layouts/sidebar.php';
        require_once __DIR__ . '/../Views/comunidades/insertar.php';
        require_once __DIR__ . '/../Views/layouts/footer.php';
    }

    // 3. Procesar la edición de una comunidad (Actualizar)
    public function editar() {
        // Obtenemos el ID de la comunidad desde la URL usando GET
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre']);
            $direccion = trim($_POST['direccion']);
            $tipo_comunidad = trim($_POST['tipo_comunidad']);

            if ($id > 0 && !empty($nombre) && !empty($direccion)) {
                $this->modelo->actualizarComunidad($id, $nombre, $direccion, $tipo_comunidad);
                header("Location: /proyecto/public/index.php?url=comunidades/index");
                exit();
            }
        }

        // Buscamos los datos actuales de la comunidad para rellenar los inputs del formulario
        $comunidad = $this->modelo->obtenerComunidadPorId($id);
        
        if (!$comunidad) {
            header("Location: /proyecto/public/index.php?url=comunidades/index");
            exit();
        }

        require_once __DIR__ . '/../Views/layouts/header.php';
        require_once __DIR__ . '/../Views/layouts/sidebar.php';
        require_once __DIR__ . '/../Views/comunidades/editar.php';
        require_once __DIR__ . '/../Views/layouts/footer.php';
    }

    // 4. Eliminar una comunidad (Borrar)
    public function eliminar() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if ($id > 0) {
            $this->modelo->eliminarComunidad($id);
        }
        
        header("Location: /proyecto/public/index.php?url=comunidades/index");
        exit();
    }
}