<?php
namespace App\Controllers;

use App\Models\Proyecto;
use App\Models\Proyectista;
use App\Models\LineaInvestigacion;

class ProyectoController {
    private $modelo;
    private $proyectista;
    private $lineaInvestigacion;
    private const ESTADOS = ['Activo', 'Finalizado', 'Suspendido', 'Aprobado', 'Rechazado'];
    private const TRAYECTOS = ['Trayecto I', 'Trayecto II', 'Trayecto III', 'Trayecto IV'];

    public function __construct() {
        $this->modelo = new Proyecto();
        $this->proyectista = new Proyectista();
        $this->lineaInvestigacion = new LineaInvestigacion();
    }

    public function index() {
        $proyectos = $this->modelo->obtenerTodos();
        include __DIR__ . '/../Views/proyecto/listar.php';
    }

    public function crear() {
        $old = $_SESSION['old'] ?? [];
        unset($_SESSION['old']);
        $lineas = $this->lineaInvestigacion->obtenerTodos();
        include __DIR__ . '/../Views/proyecto/crear.php';
    }

    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'titulo' => trim($_POST['titulo'] ?? ''),
                'objetivoGeneral' => trim($_POST['objetivoGeneral'] ?? ''),
                'descripcion' => trim($_POST['descripcion'] ?? ''),
                'idLineaInvestigacion' => intval($_POST['idLineaInvestigacion'] ?? 0),
                'trayecto' => trim($_POST['trayecto'] ?? ''),
                'tutor' => trim($_POST['tutor'] ?? ''),
                'docenteFormador' => trim($_POST['docenteFormador'] ?? ''),
                'comunidad' => trim($_POST['comunidad'] ?? ''),
                'fechaInicio' => trim($_POST['fechaInicio'] ?? ''),
                'fechaFin' => trim($_POST['fechaFin'] ?? ''),
                'estado' => trim($_POST['estado'] ?? 'Activo')
            ];

            $integrantesRaw = $_POST['integrantes'] ?? [];
            $integrantes = [];
            foreach ($integrantesRaw as $integranteRow) {
                $cedula = trim($integranteRow['cedula'] ?? '');
                $nombre = trim($integranteRow['nombre'] ?? '');
                $apellido = trim($integranteRow['apellido'] ?? '');
                $correo = trim($integranteRow['correo'] ?? '');
                $telefono = trim($integranteRow['telefono'] ?? '');

                if ($cedula === '' && $nombre === '' && $apellido === '' && $correo === '' && $telefono === '') {
                    continue;
                }

                if ($cedula === '' || $nombre === '' || $apellido === '' || $correo === '') {
                    $_SESSION['error'] = 'Cada integrante requiere cédula, nombre, apellido y correo.';
                    $_SESSION['old'] = array_merge($datos, ['integrantes' => $integrantesRaw]);
                    header('Location: ?url=proyecto/crear');
                    exit;
                }

                $integrantes[] = [
                    'cedula' => $cedula,
                    'nombre' => $nombre,
                    'apellido' => $apellido,
                    'correo' => $correo,
                    'telefono' => $telefono,
                ];
            }

            if ($datos['titulo'] === '' || $datos['objetivoGeneral'] === '' || $datos['comunidad'] === '' || $datos['fechaInicio'] === '' || $datos['trayecto'] === '' || $datos['idLineaInvestigacion'] <= 0) {
                $_SESSION['error'] = 'Título, objetivo general, comunidad, fecha de inicio, trayecto y línea de investigación son obligatorios.';
                $_SESSION['old'] = array_merge($datos, ['integrantes' => $integrantesRaw]);
                header('Location: ?url=proyecto/crear');
                exit;
            }

            if (!in_array($datos['estado'], self::ESTADOS, true) || !in_array($datos['trayecto'], self::TRAYECTOS, true)) {
                $_SESSION['error'] = 'Estado o trayecto inválido.';
                $_SESSION['old'] = array_merge($datos, ['integrantes' => $integrantesRaw]);
                header('Location: ?url=proyecto/crear');
                exit;
            }

            if (!$this->lineaInvestigacion->obtenerPorId($datos['idLineaInvestigacion'])) {
                $_SESSION['error'] = 'Línea de investigación inválida.';
                $_SESSION['old'] = array_merge($datos, ['integrantes' => $integrantesRaw]);
                header('Location: ?url=proyecto/crear');
                exit;
            }

            $proyectoId = $this->modelo->guardar($datos);
            if ($proyectoId) {
                foreach ($integrantes as $integrante) {
                    $integrante['idProyecto'] = $proyectoId;
                    $this->proyectista->guardar($integrante);
                }

                $_SESSION['success'] = 'Proyecto creado correctamente.';
                header('Location: ?url=proyecto/index');
                exit;
            }

            $_SESSION['error'] = 'No se pudo guardar el proyecto. Intenta nuevamente.';
            $_SESSION['old'] = array_merge($datos, ['integrantes' => $integrantesRaw]);
            header('Location: ?url=proyecto/crear');
            exit;
        }

        header('Location: ?url=proyecto/index');
        exit;
    }

    public function editar($id = null) {
        $id = intval($id);
        $proyecto = $this->modelo->obtenerPorId($id);

        if (!$proyecto) {
            $_SESSION['error'] = 'Proyecto no encontrado.';
            header('Location: ?url=proyecto/index');
            exit;
        }

        $old = $_SESSION['old'] ?? null;
        unset($_SESSION['old']);
        $lineas = $this->lineaInvestigacion->obtenerTodos();
        $integrantesActuales = $this->proyectista->obtenerPorProyecto($id);
        include __DIR__ . '/../Views/proyecto/editar.php';
    }

    public function integrantes($idProyecto = null) {
        $idProyecto = intval($idProyecto);
        $proyecto = $this->modelo->obtenerPorId($idProyecto);

        if (!$proyecto) {
            $_SESSION['error'] = 'Proyecto no encontrado.';
            header('Location: ?url=proyecto/index');
            exit;
        }

        $integrantes = $this->proyectista->obtenerPorProyecto($idProyecto);
        include __DIR__ . '/../Views/proyecto/integrantes.php';
    }

    public function crearIntegrante($idProyecto = null) {
        $idProyecto = intval($idProyecto);
        $proyecto = $this->modelo->obtenerPorId($idProyecto);

        if (!$proyecto) {
            $_SESSION['error'] = 'Proyecto no encontrado.';
            header('Location: ?url=proyecto/index');
            exit;
        }

        $old = $_SESSION['old'] ?? [];
        unset($_SESSION['old']);
        include __DIR__ . '/../Views/proyecto/crear_integrante.php';
    }

    public function guardarIntegrante($idProyecto = null) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idProyecto = intval($idProyecto);
            $proyecto = $this->modelo->obtenerPorId($idProyecto);

            if (!$proyecto) {
                $_SESSION['error'] = 'Proyecto no encontrado.';
                header('Location: ?url=proyecto/index');
                exit;
            }

            $datos = [
                'cedula' => trim($_POST['cedula'] ?? ''),
                'nombre' => trim($_POST['nombre'] ?? ''),
                'apellido' => trim($_POST['apellido'] ?? ''),
                'correo' => trim($_POST['correo'] ?? ''),
                'telefono' => trim($_POST['telefono'] ?? ''),
                'idProyecto' => $idProyecto,
            ];

            if ($datos['cedula'] === '' || $datos['nombre'] === '' || $datos['apellido'] === '' || $datos['correo'] === '') {
                $_SESSION['error'] = 'Cédula, nombre, apellido y correo son obligatorios.';
                $_SESSION['old'] = $datos;
                header('Location: ?url=proyecto/crearIntegrante/' . $idProyecto);
                exit;
            }

            $guardado = $this->proyectista->guardar($datos);
            if ($guardado) {
                $_SESSION['success'] = 'Integrante agregado correctamente.';
                header('Location: ?url=proyecto/integrantes/' . $idProyecto);
                exit;
            }

            $_SESSION['error'] = 'No se pudo guardar el integrante. Intenta nuevamente.';
            $_SESSION['old'] = $datos;
            header('Location: ?url=proyecto/crearIntegrante/' . $idProyecto);
            exit;
        }

        header('Location: ?url=proyecto/index');
        exit;
    }

    public function editarIntegrante($idProyecto = null, $idProyectista = null) {
        $idProyecto = intval($idProyecto);
        $idProyectista = intval($idProyectista);
        $proyecto = $this->modelo->obtenerPorId($idProyecto);
        $integrante = $this->proyectista->obtenerPorId($idProyectista);

        if (!$proyecto || !$integrante || $integrante['idProyecto'] != $idProyecto) {
            $_SESSION['error'] = 'Integrante o proyecto no encontrado.';
            header('Location: ?url=proyecto/index');
            exit;
        }

        $old = $_SESSION['old'] ?? null;
        unset($_SESSION['old']);
        include __DIR__ . '/../Views/proyecto/editar_integrante.php';
    }

    public function actualizarIntegrante($idProyecto = null, $idProyectista = null) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idProyecto = intval($idProyecto);
            $idProyectista = intval($idProyectista);
            $proyecto = $this->modelo->obtenerPorId($idProyecto);
            $integrante = $this->proyectista->obtenerPorId($idProyectista);

            if (!$proyecto || !$integrante || $integrante['idProyecto'] != $idProyecto) {
                $_SESSION['error'] = 'Integrante o proyecto no encontrado.';
                header('Location: ?url=proyecto/index');
                exit;
            }

            $datos = [
                'cedula' => trim($_POST['cedula'] ?? ''),
                'nombre' => trim($_POST['nombre'] ?? ''),
                'apellido' => trim($_POST['apellido'] ?? ''),
                'correo' => trim($_POST['correo'] ?? ''),
                'telefono' => trim($_POST['telefono'] ?? ''),
            ];

            if ($datos['cedula'] === '' || $datos['nombre'] === '' || $datos['apellido'] === '' || $datos['correo'] === '') {
                $_SESSION['error'] = 'Cédula, nombre, apellido y correo son obligatorios.';
                $_SESSION['old'] = $datos;
                header('Location: ?url=proyecto/editarIntegrante/' . $idProyecto . '/' . $idProyectista);
                exit;
            }

            $guardado = $this->proyectista->actualizar($idProyectista, $datos);
            if ($guardado) {
                $_SESSION['success'] = 'Integrante actualizado correctamente.';
                header('Location: ?url=proyecto/integrantes/' . $idProyecto);
                exit;
            }

            $_SESSION['error'] = 'No se pudo actualizar el integrante. Intenta nuevamente.';
            $_SESSION['old'] = $datos;
            header('Location: ?url=proyecto/editarIntegrante/' . $idProyecto . '/' . $idProyectista);
            exit;
        }

        header('Location: ?url=proyecto/index');
        exit;
    }

    public function eliminarIntegrante($idProyecto = null, $idProyectista = null) {
        $idProyecto = intval($idProyecto);
        $idProyectista = intval($idProyectista);
        $proyecto = $this->modelo->obtenerPorId($idProyecto);
        $integrante = $this->proyectista->obtenerPorId($idProyectista);

        if (!$proyecto || !$integrante || $integrante['idProyecto'] != $idProyecto) {
            $_SESSION['error'] = 'Integrante o proyecto no encontrado.';
            header('Location: ?url=proyecto/index');
            exit;
        }

        $eliminado = $this->proyectista->eliminar($idProyectista);
        if ($eliminado) {
            $_SESSION['success'] = 'Integrante eliminado correctamente.';
        } else {
            $_SESSION['error'] = 'No se pudo eliminar el integrante.';
        }

        header('Location: ?url=proyecto/integrantes/' . $idProyecto);
        exit;
    }

    public function actualizar($id = null) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($id);
            $datos = [
                'titulo' => trim($_POST['titulo'] ?? ''),
                'objetivoGeneral' => trim($_POST['objetivoGeneral'] ?? ''),
                'descripcion' => trim($_POST['descripcion'] ?? ''),
                'idLineaInvestigacion' => intval($_POST['idLineaInvestigacion'] ?? 0),
                'trayecto' => trim($_POST['trayecto'] ?? ''),
                'tutor' => trim($_POST['tutor'] ?? ''),
                'docenteFormador' => trim($_POST['docenteFormador'] ?? ''),
                'comunidad' => trim($_POST['comunidad'] ?? ''),
                'fechaInicio' => trim($_POST['fechaInicio'] ?? ''),
                'fechaFin' => trim($_POST['fechaFin'] ?? ''),
                'estado' => trim($_POST['estado'] ?? 'Activo')
            ];

            $integrantesRaw = $_POST['integrantes'] ?? [];
            $integrantes = [];
            foreach ($integrantesRaw as $integranteRow) {
                $cedula = trim($integranteRow['cedula'] ?? '');
                $nombre = trim($integranteRow['nombre'] ?? '');
                $apellido = trim($integranteRow['apellido'] ?? '');
                $correo = trim($integranteRow['correo'] ?? '');
                $telefono = trim($integranteRow['telefono'] ?? '');

                if ($cedula === '' && $nombre === '' && $apellido === '' && $correo === '' && $telefono === '') {
                    continue;
                }

                if ($cedula === '' || $nombre === '' || $apellido === '' || $correo === '') {
                    $_SESSION['error'] = 'Cada integrante requiere cédula, nombre, apellido y correo.';
                    $_SESSION['old'] = array_merge($datos, ['integrantes' => $integrantesRaw]);
                    header('Location: ?url=proyecto/editar/' . $id);
                    exit;
                }

                $integrantes[] = [
                    'cedula' => $cedula,
                    'nombre' => $nombre,
                    'apellido' => $apellido,
                    'correo' => $correo,
                    'telefono' => $telefono,
                ];
            }

            if ($datos['titulo'] === '' || $datos['objetivoGeneral'] === '' || $datos['comunidad'] === '' || $datos['fechaInicio'] === '' || $datos['trayecto'] === '' || $datos['idLineaInvestigacion'] <= 0) {
                $_SESSION['error'] = 'Título, objetivo general, comunidad, fecha de inicio, trayecto y línea de investigación son obligatorios.';
                $_SESSION['old'] = array_merge($datos, ['integrantes' => $integrantesRaw]);
                header('Location: ?url=proyecto/editar/' . $id);
                exit;
            }

            if (!in_array($datos['estado'], self::ESTADOS, true) || !in_array($datos['trayecto'], self::TRAYECTOS, true)) {
                $_SESSION['error'] = 'Estado o trayecto inválido.';
                $_SESSION['old'] = array_merge($datos, ['integrantes' => $integrantesRaw]);
                header('Location: ?url=proyecto/editar/' . $id);
                exit;
            }

            if (!$this->lineaInvestigacion->obtenerPorId($datos['idLineaInvestigacion'])) {
                $_SESSION['error'] = 'Línea de investigación inválida.';
                $_SESSION['old'] = array_merge($datos, ['integrantes' => $integrantesRaw]);
                header('Location: ?url=proyecto/editar/' . $id);
                exit;
            }

            $actualizado = $this->modelo->actualizar($id, $datos);
            if ($actualizado) {
                foreach ($integrantes as $integrante) {
                    $integrante['idProyecto'] = $id;
                    $this->proyectista->guardar($integrante);
                }

                $_SESSION['success'] = 'Proyecto actualizado correctamente.';
                header('Location: ?url=proyecto/index');
                exit;
            }

            $_SESSION['error'] = 'No se pudo actualizar el proyecto. Intenta nuevamente.';
            $_SESSION['old'] = array_merge($datos, ['integrantes' => $integrantesRaw]);
            header('Location: ?url=proyecto/editar/' . $id);
            exit;
        }

        header('Location: ?url=proyecto/index');
        exit;
    }

    public function eliminar($id = null) {
        $id = intval($id);

        if ($id <= 0) {
            $_SESSION['error'] = 'ID de proyecto inválido.';
            header('Location: ?url=proyecto/index');
            exit;
        }

        $eliminado = $this->modelo->eliminar($id);
        if ($eliminado) {
            $_SESSION['success'] = 'Proyecto eliminado correctamente.';
        } else {
            $_SESSION['error'] = 'No se pudo eliminar el proyecto.';
        }

        header('Location: ?url=proyecto/index');
        exit;
    }

    // ==========================================
    //       MÓDULO DE COMUNIDADES (CRUD)
    // ==========================================

    // 1. Listar Comunidades (La Tabla)
    public function comunidades_index() {
        require_once __DIR__ . '/../Models/ComunidadModel.php';
        $comunidadModelo = new \App\Models\ComunidadModel();
        $comunidades = $comunidadModelo->obtenerComunidades();
        
        include __DIR__ . '/../Views/layouts/header.php';
        include __DIR__ . '/../Views/layouts/sidebar.php';
        include __DIR__ . '/../Views/comunidades/comunidades.php';
        include __DIR__ . '/../Views/layouts/footer.php';
    }

    // 2. Mostrar el Formulario en pantalla
    public function comunidades_crear() {
        include __DIR__ . '/../Views/layouts/header.php';
        include __DIR__ . '/../Views/layouts/sidebar.php';
        include __DIR__ . '/../Views/comunidades/insertar.php';
        include __DIR__ . '/../Views/layouts/footer.php';
    }

    // 3. Procesar el envío del Formulario (POST)
    public function comunidades_guardar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $direccion = trim($_POST['direccion'] ?? '');
            $tipo_comunidad = trim($_POST['tipo_comunidad'] ?? 'Urbana');

            if ($nombre !== '' && $direccion !== '') {
                require_once __DIR__ . '/../Models/ComunidadModel.php';
                $comunidadModelo = new \App\Models\ComunidadModel();
                
                $resultado = $comunidadModelo->insertarComunidad($nombre, $direccion, $tipo_comunidad);
                if ($resultado) {
                    $_SESSION['success'] = 'Comunidad guardada correctamente.';
                    header("Location: ?url=proyecto/comunidades_index");
                    exit();
                }
            }
            $_SESSION['error'] = 'El nombre y la dirección son obligatorios.';
        }
        header("Location: ?url=proyecto/comunidades_crear");
        exit();
    }

    // 4. Editar Comunidad
    public function comunidades_editar($id = null) {
        if ($id === null) {
            $id = $_GET['id'] ?? 0;
        }
        $id = intval($id);
        
        require_once __DIR__ . '/../Models/ComunidadModel.php';
        $comunidadModelo = new \App\Models\ComunidadModel();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $direccion = trim($_POST['direccion'] ?? '');
            $tipo_comunidad = trim($_POST['tipo_comunidad'] ?? 'Urbana');

            if ($id > 0 && $nombre !== '' && $direccion !== '') {
                $comunidadModelo->actualizarComunidad($id, $nombre, $direccion, $tipo_comunidad);
                $_SESSION['success'] = 'Comunidad actualizada correctamente.';
                header("Location: ?url=proyecto/comunidades_index");
                exit();
            }
            $_SESSION['error'] = 'Datos inválidos para actualizar.';
        }

        $comunidad = $comunidadModelo->obtenerComunidadPorId($id);
        if (!$comunidad) {
            $_SESSION['error'] = 'Comunidad no encontrada.';
            header("Location: ?url=proyecto/comunidades_index");
            exit();
        }

        include __DIR__ . '/../Views/layouts/header.php';
        include __DIR__ . '/../Views/layouts/sidebar.php';
        include __DIR__ . '/../Views/comunidades/editar.php';
        include __DIR__ . '/../Views/layouts/footer.php';
    }

    // 5. Eliminar Comunidad
    public function comunidades_eliminar($id = null) {
        if ($id === null) {
            $id = $_GET['id'] ?? 0;
        }
        $id = intval($id);
        
        if ($id > 0) {
            require_once __DIR__ . '/../Models/ComunidadModel.php';
            $comunidadModelo = new \App\Models\ComunidadModel();
            
            if ($comunidadModelo->eliminarComunidad($id)) {
                $_SESSION['success'] = 'Comunidad eliminada correctamente.';
            } else {
                $_SESSION['error'] = 'No se pudo eliminar la comunidad.';
            }
        }
        header("Location: ?url=proyecto/comunidades_index");
        exit();
    }
}