<?php
namespace App\Models;

use App\Config\Database;
use PDO;
use PDOException;

class Proyectista {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function obtenerPorProyecto($idProyecto) {
        $query = "SELECT * FROM proyectista WHERE idProyecto = :idProyecto ORDER BY idProyectista DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':idProyecto', $idProyecto, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarPorProyecto($idProyecto) {
        $query = "SELECT COUNT(*) FROM proyectista WHERE idProyecto = :idProyecto";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':idProyecto', $idProyecto, PDO::PARAM_INT);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function obtenerPorId($idProyectista) {
        $query = "SELECT * FROM proyectista WHERE idProyectista = :idProyectista LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':idProyectista', $idProyectista, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function guardar($datos) {
        $query = "INSERT INTO proyectista (idProyecto, cedula, nombre, apellido, correo, telefono)
                  VALUES (:idProyecto, :cedula, :nombre, :apellido, :correo, :telefono)";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':idProyecto', $datos['idProyecto'], PDO::PARAM_INT);
        $stmt->bindParam(':cedula', $datos['cedula']);
        $stmt->bindParam(':nombre', $datos['nombre']);
        $stmt->bindParam(':apellido', $datos['apellido']);
        $stmt->bindParam(':correo', $datos['correo']);
        $stmt->bindParam(':telefono', $datos['telefono']);

        return $stmt->execute();
    }

    public function actualizar($idProyectista, $datos) {
        $query = "UPDATE proyectista SET cedula = :cedula, nombre = :nombre, apellido = :apellido,
                  correo = :correo, telefono = :telefono WHERE idProyectista = :idProyectista";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':cedula', $datos['cedula']);
        $stmt->bindParam(':nombre', $datos['nombre']);
        $stmt->bindParam(':apellido', $datos['apellido']);
        $stmt->bindParam(':correo', $datos['correo']);
        $stmt->bindParam(':telefono', $datos['telefono']);
        $stmt->bindParam(':idProyectista', $idProyectista, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function eliminar($idProyectista) {
        $query = "DELETE FROM proyectista WHERE idProyectista = :idProyectista";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':idProyectista', $idProyectista, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
