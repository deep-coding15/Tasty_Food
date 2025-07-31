<?php

namespace App\Core;

/**
 * This class is for connect to a database using PDO. 
 */
class Database
{
    private ?\PDO $connection = null;
    private static ?Database $instance = null;
    private function __construct()
    {
        self::dbConnect();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * This function is a private function can connect to a database. The default values are : 
     * @param string $host = 'localhost'
     * @param string $dbname = 'restaurant_tasty_food'
     * @param string $username = 'root'
     * @param string $password = ''
     * @return void
     */
    private function dbConnect(
        string $host = '127.0.0.1',
        string $dbname = 'restaurant_tasty_food',
        int $port = 3306,
        string $username = 'root',
        string $password = ''
    ) {
        try {
            if ($this->connection === null) {
                $this->connection = new \PDO(
                    "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8",
                    $username,
                    $password,
                    [
                        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
                    ]
                );
            }
        } catch (\PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }

        return $this->connection;
    }

    /**
     * THis function can connect to a database. The default values are : 
     * @param string $host = 'localhost'
     * @param string $dbname = 'restaurant_tasty_food'
     * @param string $username = 'root'
     * @param string $password = ''
     * @return \PDO
     */
    public function getConnection(): \PDO
    {
        if ($this->connection === null) {
            throw new \ErrorException("La connexion est nulle.");
        }
        return $this->connection;
    }

    public function setConnection(\PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * This function execute an sql statement and its parameters in form of an array and return the statement
     * It uses the prepare statement and the execute function to do his work
     */
    public function executeSqlPrepareStatement(string $sql, array $params = [])
    {
        if ($this->connection === null) {
            $this->dbConnect();
            echo 'connection nulle';
        }
        //echo 'connection non nulle';
        $statement = $this->getConnection()->prepare($sql);
        //$this->getConnection()->prepare($sql);
        (isset($params)) ? $statement->execute($params) : $statement->execute();
        return $statement;
    }
    
    /**
     * Cette méthode exécute une requête SQL simple (sans paramètres) sur la base de données à l’aide de PDO::query(). Elle établit une connexion si elle n’est pas encore active, puis retourne le résultat de la requête.
     * @param string $sql la requete sans parametres dynamiques a executer
     * @return bool|\PDOStatement Un objet PDOStatement contenant le résultat de la requête, utilisable avec fetch(), fetchAll(), etc.
     */
    public function executeSqlQueryStatement(string $sql){
        if ($this->connection === null) {
            $this->dbConnect();
            echo 'connection nulle';
        }
        //echo 'connection non nulle';
        $statement = $this->getConnection()->query($sql); //execution directe
        //$this->getConnection()->prepare($sql);
        $statement->execute();
        return $statement;
    }

    public function debugBaseInfo()
    {
        $stmt = $this->executeSqlPrepareStatement("SELECT DATABASE() as db, USER() as user, NOW() as time");
        $info = $stmt->fetch();
        var_dump($info);

        $port = $this->getConnection()->getAttribute(\PDO::ATTR_CONNECTION_STATUS);
        echo "Connexion PDO : " . $port;

        $stmt = $this->getConnection()->query("SHOW DATABASES");
        $dbs = $stmt->fetchAll(\PDO::FETCH_COLUMN);
        echo '<pre>';
        var_dump($dbs);
        echo '</pre>';

        $stmt = $this->getConnection()->query("SHOW TABLES");
        $tables = $stmt->fetchAll(mode: \PDO::FETCH_COLUMN);
        echo '<pre>';
        var_dump($tables);
        echo '</pre>';
    }
}
