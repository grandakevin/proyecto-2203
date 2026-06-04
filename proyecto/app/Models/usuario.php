<?php
namespace App\Models;

use App\Config\Database;
use PDO;
use PDOException;

class Usuario {
    private $db;

    // Atributos exactos de tu Diagrama de Clases
    public $idUsuario;
    public $cedula;
    public $nombre;
    public $apellido;
    public $correo;
    public $clave;
    public $estado; // enum(Activo, Inactivo)

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // Operación +consultar(): Usuario de tu diagrama (Útil para el Login)
    public function consultarPorCorreo($correo) {
        $query = "SELECT * FROM usuario WHERE correo = :correo LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':correo', $correo);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Operación +crear(): void de tu diagrama
    public function crear($datos) {
        $query = "INSERT INTO usuario (cedula, nombre, apellido, correo, clave, estado) 
                  VALUES (:cedula, :nombre, :apellido, :correo, :clave, 'Activo')";
        $stmt = $this->db->prepare($query);
        
        // Encriptamos la clave por seguridad antes de guardarla
        $claveHash = password_hash($datos['clave'], PASSWORD_BCRYPT);

        $stmt->bindParam(':cedula', $datos['cedula']);
        $stmt->bindParam(':nombre', $datos['nombre']);
        $stmt->bindParam(':apellido', $datos['apellido']);
        $stmt->bindParam(':correo', $datos['correo']);
        $stmt->bindParam(':clave', $claveHash);
        
        return $stmt->execute();
    }
}