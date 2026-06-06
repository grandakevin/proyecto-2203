<?php
namespace App\Models;

use PDO;
use Exception;

class ComunidadModel {
    private $db;

    public function __construct() {
        // Intentamos conectar usando los mismos parámetros estándar de PDO de tu proyecto
        try {
            // Ajusta el dbname, usuario y contraseña si los cambiaste en tu nueva PC
            $this->db = new PDO(
                "mysql:host=localhost;dbname=psi_mlk;charset=utf8mb4", 
                "root", 
                "", 
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (Exception $e) {
            // Si falla la conexión global, intentamos jalar la del index por si acaso
            global $conexion;
            if (isset($conexion)) {
                $this->db = $conexion;
            } else {
                die("Error de conexión a la base de datos en ComunidadModel: " . $e->getMessage());
            }
        }
    }

    // 1. READ: Obtener todas las comunidades registradas
    public function obtenerComunidades() {
        $query = "SELECT * FROM comunidades ORDER BY id DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. CREATE: Insertar una nueva comunidad
    public function insertarComunidad($nombre, $direccion, $tipo_comunidad) {
        $query = "INSERT INTO comunidades (nombre, direccion, tipo_comunidad) 
                  VALUES (:nombre, :direccion, :tipo_comunidad)";
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':tipo_comunidad', $tipo_comunidad);
        
        return $stmt->execute();
    }

    // 3. READ SINGLE: Obtener una sola comunidad por su ID
    public function obtenerComunidadPorId($id) {
        $query = "SELECT * FROM comunidades WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 4. UPDATE: Actualizar los datos de una comunidad existente
    public function actualizarComunidad($id, $nombre, $direccion, $tipo_comunidad) {
        $query = "UPDATE comunidades 
                  SET nombre = :nombre, direccion = :direccion, tipo_comunidad = :tipo_comunidad 
                  WHERE id = :id";
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':tipo_comunidad', $tipo_comunidad);
        
        return $stmt->execute();
    }

    // 5. DELETE: Eliminar una comunidad
    public function eliminarComunidad($id) {
        $query = "DELETE FROM comunidades WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}