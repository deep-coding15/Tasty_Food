<?php

namespace App\Core;

use PDO;
use PDOException;
use App\Config\SuperConstante;
use App\Core\Database;

abstract class Modele
{
    protected PDO $pdoDatabase;
    protected Database $database;

    public function __construct()
    {
        $this->pdoDatabase = $this->getConnexion();
    }

    /**
     * Crée et retourne la connexion PDO
     */
    protected function getConnexion(): PDO
    {
        try {
            return new PDO(
                'mysql:host=' . SuperConstante::DB_HOST . ';dbname=' . SuperConstante::DB_NAME . ';charset=utf8',
                SuperConstante::DB_USER,
                SuperConstante::DB_PASSWORD,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException $e) {
            throw new \RuntimeException("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    /**
     * Prépare et exécute une requête SQL
     */
    protected function executeSqlPrepareStatement(string $sql, array $params = []): \PDOStatement
    {
        $stmt = $this->pdoDatabase->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Récupère un enregistrement par ID
     */
    public function findByX(string $table, string $type): ?array
    {
        $sql = "SELECT * FROM $table WHERE $type = :type";
        $stmt = $this->executeSqlPrepareStatement($sql, 
            [
                        ':type' => $type,
                    ]
                );
        return $stmt->fetch() ?: null;
    }

    /**
     * Récupère tous les enregistrements d’une table
     */
    public function findAll(string $table): array
    {
        $sql = "SELECT * FROM $table";
        $stmt = $this->pdoDatabase->query($sql);
        return $stmt->fetchAll();
    }
}