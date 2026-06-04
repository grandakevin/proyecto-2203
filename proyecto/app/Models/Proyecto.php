<?php
namespace App\Models;

use App\Config\Database;
use PDO;
use PDOException;

class Proyecto {
    private $db;

    public $idProyecto;
    public $titulo;
    public $objetivoGeneral;
    public $descripcion;
    public $idLineaInvestigacion;
    public $trayecto;
    public $tutor;
    public $docenteFormador;
    public $comunidad;
    public $fechaInicio;
    public $fechaFin;
    public $estado;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function obtenerTodos() {
        $query = "SELECT p.*, li.nombre AS lineaInvestigacion, COUNT(pr.idProyectista) AS cantidadIntegrantes
                  FROM proyecto p
                  LEFT JOIN linea_investigacion li ON li.idLineaInvestigacion = p.idLineaInvestigacion
                  LEFT JOIN proyectista pr ON pr.idProyecto = p.idProyecto
                  GROUP BY p.idProyecto
                  ORDER BY p.idProyecto DESC";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $query = "SELECT p.*, li.nombre AS lineaInvestigacion, COUNT(pr.idProyectista) AS cantidadIntegrantes
                  FROM proyecto p
                  LEFT JOIN linea_investigacion li ON li.idLineaInvestigacion = p.idLineaInvestigacion
                  LEFT JOIN proyectista pr ON pr.idProyecto = p.idProyecto
                  WHERE p.idProyecto = :id
                  GROUP BY p.idProyecto
                  LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function guardar($datos) {
        $query = "INSERT INTO proyecto (titulo, objetivoGeneral, descripcion, idLineaInvestigacion, trayecto, tutor, docenteFormador, comunidad, fechaInicio, fechaFin, estado)
                  VALUES (:titulo, :objetivoGeneral, :descripcion, :idLineaInvestigacion, :trayecto, :tutor, :docenteFormador, :comunidad, :fechaInicio, :fechaFin, :estado)";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':titulo', $datos['titulo']);
        $stmt->bindParam(':objetivoGeneral', $datos['objetivoGeneral']);
        $stmt->bindParam(':descripcion', $datos['descripcion']);
        $stmt->bindParam(':idLineaInvestigacion', $datos['idLineaInvestigacion'], PDO::PARAM_INT);
        $stmt->bindParam(':trayecto', $datos['trayecto']);
        $stmt->bindParam(':tutor', $datos['tutor']);
        $stmt->bindParam(':docenteFormador', $datos['docenteFormador']);
        $stmt->bindParam(':comunidad', $datos['comunidad']);
        $stmt->bindParam(':fechaInicio', $datos['fechaInicio']);
        $stmt->bindParam(':fechaFin', $datos['fechaFin']);
        $stmt->bindParam(':estado', $datos['estado']);

        if ($stmt->execute()) {
            return (int) $this->db->lastInsertId();
        }

        return false;
    }

    public function actualizar($id, $datos) {
        $query = "UPDATE proyecto SET titulo = :titulo, objetivoGeneral = :objetivoGeneral, descripcion = :descripcion,
                  idLineaInvestigacion = :idLineaInvestigacion, trayecto = :trayecto, tutor = :tutor,
                  docenteFormador = :docenteFormador, comunidad = :comunidad,
                  fechaInicio = :fechaInicio, fechaFin = :fechaFin, estado = :estado
                  WHERE idProyecto = :id";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':titulo', $datos['titulo']);
        $stmt->bindParam(':objetivoGeneral', $datos['objetivoGeneral']);
        $stmt->bindParam(':descripcion', $datos['descripcion']);
        $stmt->bindParam(':idLineaInvestigacion', $datos['idLineaInvestigacion'], PDO::PARAM_INT);
        $stmt->bindParam(':trayecto', $datos['trayecto']);
        $stmt->bindParam(':tutor', $datos['tutor']);
        $stmt->bindParam(':docenteFormador', $datos['docenteFormador']);
        $stmt->bindParam(':comunidad', $datos['comunidad']);
        $stmt->bindParam(':fechaInicio', $datos['fechaInicio']);
        $stmt->bindParam(':fechaFin', $datos['fechaFin']);
        $stmt->bindParam(':estado', $datos['estado']);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function eliminar($id) {
        $query = "DELETE FROM proyecto WHERE idProyecto = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
