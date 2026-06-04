<?php
namespace App\Config;

use PDO;
use PDOException;

class Database {
    private const HOST = 'localhost';
    private const DB_NAME = 'psi_mlk';
    private const USER = 'root';
    private const PASSWORD = '';
    private const CHARSET = 'utf8mb4';

    public static function getConnection(): PDO {
        $dsnWithoutDb = sprintf('mysql:host=%s;charset=%s', self::HOST, self::CHARSET);

        try {
            $pdo = new PDO($dsnWithoutDb, self::USER, self::PASSWORD, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);

            $pdo->exec(sprintf(
                'CREATE DATABASE IF NOT EXISTS `%s` CHARACTER SET %s COLLATE %s_unicode_ci',
                self::DB_NAME,
                self::CHARSET,
                self::CHARSET
            ));

            $dsnWithDb = sprintf('mysql:host=%s;dbname=%s;charset=%s', self::HOST, self::DB_NAME, self::CHARSET);
            $connection = new PDO($dsnWithDb, self::USER, self::PASSWORD, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);

            $connection->exec(
                "CREATE TABLE IF NOT EXISTS linea_investigacion (
                    idLineaInvestigacion INT AUTO_INCREMENT PRIMARY KEY,
                    nombre VARCHAR(255) NOT NULL,
                    descripcion TEXT NULL,
                    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=" . self::CHARSET
            );

            $lineasCount = $connection->query("SELECT COUNT(*) FROM linea_investigacion")->fetchColumn();
            if ($lineasCount == 0) {
                $connection->exec(
                    "INSERT INTO linea_investigacion (nombre, descripcion) VALUES
                        ('Desarrollo de Software Sociotecnológico', 'Proyectos orientados al diseño e implementación de soluciones de software para comunidades.'),
                        ('Gestión y Participación Comunitaria', 'Estudios y propuestas que fortalecen la interacción entre universidad y comunidad.'),
                        ('Innovación y Tecnología Social', 'Investigación sobre tecnologías apropiadas para contextos sociales.')"
                );
            }

            $connection->exec(
                "CREATE TABLE IF NOT EXISTS proyecto (
                    idProyecto INT AUTO_INCREMENT PRIMARY KEY,
                    titulo VARCHAR(255) NOT NULL,
                    objetivoGeneral TEXT NOT NULL,
                    descripcion TEXT NULL,
                    idLineaInvestigacion INT NULL,
                    trayecto VARCHAR(100) NULL,
                    tutor VARCHAR(255) NULL,
                    docenteFormador VARCHAR(255) NULL,
                    comunidad VARCHAR(255) NULL,
                    fechaInicio DATE NULL,
                    fechaFin DATE NULL,
                    estado VARCHAR(50) NOT NULL DEFAULT 'Activo',
                    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=" . self::CHARSET
            );

            $connection->exec(
                "CREATE TABLE IF NOT EXISTS proyectista (
                    idProyectista INT AUTO_INCREMENT PRIMARY KEY,
                    idProyecto INT NOT NULL,
                    cedula VARCHAR(50) NOT NULL,
                    nombre VARCHAR(255) NOT NULL,
                    apellido VARCHAR(255) NOT NULL,
                    correo VARCHAR(255) NOT NULL,
                    telefono VARCHAR(100) NULL,
                    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (idProyecto) REFERENCES proyecto(idProyecto) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=" . self::CHARSET
            );

            $currentColumns = $connection->query("SHOW COLUMNS FROM proyecto")->fetchAll(PDO::FETCH_COLUMN);
            $columnsToAdd = [
                'idLineaInvestigacion INT NULL',
                'trayecto VARCHAR(100) NULL',
                'tutor VARCHAR(255) NULL',
                'docenteFormador VARCHAR(255) NULL',
                'comunidad VARCHAR(255) NULL',
            ];

            foreach ($columnsToAdd as $columnDefinition) {
                if (preg_match('/^([a-zA-Z0-9_]+)/', $columnDefinition, $matches)) {
                    $columnName = $matches[1];
                    if (!in_array($columnName, $currentColumns, true)) {
                        $connection->exec("ALTER TABLE proyecto ADD COLUMN " . $columnDefinition);
                        $currentColumns[] = $columnName;
                    }
                }
            }

            if (in_array('lineaInvestigacion', $currentColumns, true)) {
                $connection->exec('ALTER TABLE proyecto DROP COLUMN lineaInvestigacion');
            }

            if (in_array('periodoAcademico', $currentColumns, true)) {
                $connection->exec('ALTER TABLE proyecto DROP COLUMN periodoAcademico');
            }

            $fkExists = false;
            $stmt = $connection->prepare(
                "SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
                 WHERE TABLE_SCHEMA = :db AND TABLE_NAME = 'proyecto' AND COLUMN_NAME = 'idLineaInvestigacion' AND REFERENCED_TABLE_NAME = 'linea_investigacion'"
            );
            $stmt->bindValue(':db', self::DB_NAME, PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->fetch()) {
                $fkExists = true;
            }

            if (!$fkExists && in_array('idLineaInvestigacion', $currentColumns, true)) {
                $connection->exec(
                    'ALTER TABLE proyecto ADD CONSTRAINT fk_proyecto_linea_investigacion FOREIGN KEY (idLineaInvestigacion) REFERENCES linea_investigacion(idLineaInvestigacion) ON DELETE SET NULL'
                );
            }

            return $connection;
        } catch (PDOException $e) {
            die('Error de conexión: ' . $e->getMessage());
        }
    }
}
