<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class LineaInvestigacion {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function obtenerTodos() {
        $query = "SELECT idLineaInvestigacion, nombre, descripcion FROM linea_investigacion ORDER BY nombre";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $query = "SELECT idLineaInvestigacion, nombre, descripcion FROM linea_investigacion WHERE idLineaInvestigacion = :idLineaInvestigacion LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':idLineaInvestigacion', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
