<?php

namespace App\Modeles;

use App\Config\Constante;

class Plat
{
    private int $id_plat;
    private string $nom_plat;
    private string $img_plat;
    private string $description;
    private \DateTime $created_at;
    private \DateTime $updated_at;
    private \DateTime $deleted_at;
    private string $type_plats;
    private float $prix_plat;

    private static int $nb_plats = 0;

    /**
     * Summary of __construct
     * @param string $nom_plat
     * @param string $img_plat contient le chemin ou se trouve l'image du plat. Il est stocke dans le dossier upload qui se trouve dans le dossier data
     * @param string $type_menu : entree, plat de resistance, accompagnements, desserts
     * @param float $prix_plat
     */
    public function __construct(int $id_plat, string $nom_plat, string $description, string $img_plats, \DateTime $created_at, \DateTime $updated_at, \DateTime $deleted_at, string $type_plats, Float $prix_plats)
    {
        $this->id_plat = $id_plat;
        $this->nom_plat = $nom_plat;
        $this->description = $description;
        $this->img_plat = Constante::base_url() . '/app/data/Plats/Images/' . $img_plats;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->deleted_at = $deleted_at;
        $this->type_plats = $type_plats;
        $this->prix_plat = $prix_plats;
        self::$nb_plats++;
    }

    /*  // 🧹 Destructeur (facultatif)
    public function __destruct() {
        self::$nb_plats--;
        // Exemple : log ou nettoyage
        echo "Le plat '{$this->nom_plat}' a été supprimé.";
    } */

    //Setters && Getters
    // id_plat
    public function getIdPlat(): int
    {
        return $this->id_plat;
    }

    public function setIdPlat(int $id_plat): void
    {
        $this->id_plat = $id_plat;
    }

    // nom_plat
    public function getNomPlat(): string
    {
        return $this->nom_plat;
    }

    public function setNomPlat(string $nom_plat): void
    {
        $this->nom_plat = $nom_plat;
    }

    // img_plat
    public function getImgPlat(): string
    {
        return $this->img_plat;
    }

    public function setImgPlat(string $img_plat): void
    {
        $this->img_plat = $img_plat;
    }

    public function getRealImgPlat()
    {
        return Constante::base_url() . '/app/data/Plats/Images/' . $this->img_plat;
    }

    // description
    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    // created_at
    public function getCreatedAt(): \DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTime $created_at): void
    {
        $this->created_at = $created_at;
    }

    // updated_at
    public function getUpdatedAt(): \DateTime
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTime $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    // deleted_at
    public function getDeletedAt(): \DateTime
    {
        return $this->deleted_at;
    }

    public function setDeletedAt(\DateTime $deleted_at): void
    {
        $this->deleted_at = $deleted_at;
    }

    // type_plats
    public function getTypePlat(): string
    {
        return $this->type_plats;
    }

    public function setTypePlats(string $type_plats): void
    {
        $this->type_plats = $type_plats;
    }

    // prix_plat
    public function getPrixPlat(): float
    {
        return $this->prix_plat;
    }

    public function setPrixPlat(float $prix_plat): void
    {
        $this->prix_plat = $prix_plat;
    }

    public static function getNombrePlats()
    {
        return self::$nb_plats;
    }

    public function __toString()
    {
        return "Plat: {$this->nom_plat}\n" .
            "Id: {$this->id_plat}\n" .
            "Type: {$this->type_plats}\n" .
            "Prix: {$this->prix_plat} €\n" .
            "Description: {$this->description}\n" .
            "Image: {$this->img_plat}\n" .
            "Created at: {$this->created_at->format('Y-m-d H:i:s')} \n" .
            "Updated at: {$this->updated_at->format('Y-m-d H:i:s')} \n" .
            "Deleted at: {$this->deleted_at->format('Y-m-d H:i:s')} \n";
    }
}

/* enum Statut {
    case en_attente;
    case en_cours;
    case livree;
    case annulee;
}


 */
class Commandes
{
    private int $id_commande;
    private int $id_client;
    private \DateTime $date_commande;
    //private Statut $statut; 
    private float $montant_total;
}

class PanierPlats
{
    private int $id_commande;
    private int $id_client;
    private array $plats;
}

//namespace App\Modeles;
use App\Core\Database;
use App\Lib\ImagesApi;
use App\Core\Exceptions\NullException;
use InvalidArgumentException;
use RuntimeException;
//use App\Config\Constante;
class PlatRepository
{
    /**
     * Elle sert à accéder à la connexion partagée dans chaque instance de PlatRepository.
     * @var 
     */
    public ?Database $database = null;
    /**
     * Pour utiliser une connexion partagée (singleton) dans PlatRepository, 
     * Il faut déclarer une propriété statique pour la base de données et l’utiliser dans le constructeur.
     * @var 
     */
    /* private static ?Database $sharedDatabase = null; // Ajoute cette ligne
 */
    public function getDatabase()
    {
        return $this->database;
    }
    public function __construct()
    {
        if ($this->database === null) {
            //$this->pdo = Database::getInstance()->getConnection();
            $this->database = Database::getInstance();
            //$this->pdo = self::$sharedDatabase->getConnection();
        }
        //$this->session = $_session;
        $this->database = Database::getInstance();
    }

    public function setImagesByAPI()
    {
        $imagesApi = ImagesApi::getInstance();
        $imagesApi->getImagesPlat();
    }

    public function setImageByAPIById($id)
    {
        $imagesApi = ImagesApi::getInstance();
        $imagesApi->getImagePlatById($id);
    }

    public function getPlat(int $identifier): Plat|null
    {
        $sql = "SELECT id_plat, nom_plat, description, img_plats, created_at, updated_at, deleted_at, prix_plats, type_plats 
                FROM plats 
                WHERE id_plat = ?";

        /* $pdo = $this->database->getConnection();
        $statement = $pdo->prepare($sql);
        $statement->execute([
            $identifier
        ]); */
        $statement = $this->database->executeSqlPrepareStatement($sql, [$identifier]);

        $data = $statement->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::fetchData($data);
        }

        return null;
    }

    /**
     * Return an array of all plats in the database
     * @return Plat[]
     */
    public function getPlats(): array
    {

        $sql = "SELECT id_plat, nom_plat, description, img_plats, created_at, updated_at, deleted_at, prix_plats, type_plats 
                FROM plats";

        $statement = $this->database->executeSqlPrepareStatement($sql);

        $plats = [];
        while (($data = $statement->fetch(\PDO::FETCH_ASSOC))) {
            $plat = self::fetchData($data);

            $plats[] = $plat;
        }
        $this->setImagesByAPI();
        return $plats;
    }

    /**
     * This funtion return the number of the plats in the database
     */
    public function getNombrePlats()
    {
        $sql = "SELECT COUNT(*) AS nb_articles FROM plats";
        $query = $this->database->executeSqlPrepareStatement($sql);
        $result = $query->fetch();
        return (int) $result['nb_articles'];
    }

    public function getNombrePlatsArray(array $plats)
    {
        foreach ($plats as $plat) {
            if (!$plat instanceof Plat) {
                //Ce back slash '\' indique a PHP de chercher la fonction dans l'espace de nom (namespace) global.
                throw new InvalidArgumentException("Tous les éléments doivent être des instances de Plat.");
            }
        }
        if ($plats === null) { //is_null($plats)
            throw new NullException("Le tableau de plats est null");
        }
        if (!is_array($plats))
            throw new InvalidArgumentException("La valeur passé en paramètre doit être un tableau");
        return count($plats);
    }

    public function getNombrePages(int $nb_total_plats, int $nb_plats_par_page): int
    {
        return ceil($nb_total_plats / $nb_plats_par_page);
    }

    /**
     * This function gives a $limit plat from a $offset from the most recent to the most distant
     * @param int $offset : c'est l'element à partir duquel on commence à lister
     * @param int $limit : c'est le nombre de plats a prendre
     */
    public function getPlatsParPage(int $limit, int $offset)
    {
        // On injecte les entiers *directement* car PDO ne gère pas bien LIMIT ? OFFSET ?
        $sql = "SELECT id_plat, nom_plat, description, img_plats, created_at, updated_at, deleted_at, prix_plats, type_plats
                FROM plats
                ORDER BY updated_at DESC
                LIMIT $limit OFFSET $offset";
        $query = $this->database->executeSqlPrepareStatement($sql);
        $plats = [];
        while (($row = $query->fetch(\PDO::FETCH_ASSOC))) {
            $plat = self::fetchData($row);
            $plats[] = $plat;
        }
        $this->setImagesByAPI();
        return $plats;
    }

    /* private function executeSqlPrepareStatement(string $sql, array $params = []){
        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);
        return $statement;
    } */

    /**
     * This function return an array of plats that has been update nearly
     */
    function getPlatsByUpdatedDate()
    {
        $sql = "SELECT id_plat, nom_plat, description, img_plats, created_at, updated_at, deleted_at, prix_plats, type_plats 
                FROM plats ORDER BY updated_at DESC";
        $statement = $this->database->executeSqlPrepareStatement($sql);
        //self::executeSqlPrepareStatement($sql);
        $plats = [];
        while (($row = $statement->fetch(\PDO::FETCH_ASSOC))) {
            $plat = self::fetchData($row);
            $plats[] = $plat;
        }
        $this->setImagesByAPI();
        return $plats;
    }

    private function getTypePlat(int $id_type_plat)
    {
        $sql = 'SELECT type_plat_nom from type_plat where type_plat_id = :id';
        $statement = $this->database->executeSqlPrepareStatement($sql, [':id' => $id_type_plat]);
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        return $result['type_plat_nom'];
    }

    /**
     * This function return an array of plats that it sorted by the id of the type of the plat
     * 1 - Accompagnements
     * 2 - Desserts
     * 3 - Entrees
     * 4 - Plat de resistance
     * 5 - Boissons
     * 6 - Default
     */
    function getPlatsByType(int $type_id)
    {
        $sql = "SELECT * FROM plats p JOIN type_plat tp ON  p.type_plats = tp.type_plat_id WHERE tp.type_plat_id = :type_id";

        $statement = $this->database->executeSqlPrepareStatement($sql, [":type_id" => $type_id]);

        $plats = [];
        while (($data = $statement->fetch(\PDO::FETCH_ASSOC))) {
            $plat = self::fetchData($data);
            $plats[] = $plat;
        }
        return $plats;
    }

    /**
     * This function return an array of plats that it sorted by the name of the type of the plat
     * 1 - Accompagnements
     * 2 - Desserts
     * 3 - Entrees
     * 4 - Plat de résistance
     * 5 - Boissons
     * 6 - Default
     */
    function getPlatsByTypeName(string $type_name)
    {
        $sql = "SELECT * FROM plats p JOIN type_plat tp ON  p.type_plats = tp.type_plat_id WHERE tp.type_plat_nom = :type_nom";

        $statement = $this->database->executeSqlPrepareStatement($sql, [":type_nom" => trim($type_name)]);

        $plats = [];
        while (($data = $statement->fetch(\PDO::FETCH_ASSOC))) {
            $plat = self::fetchData($data);
            $plats[] = $plat;
        }
        return $plats;
    }


    /**
     * This function is to fetch a single data from the result of the database query
     * @param array $data
     * @return Plat
     */
    private function fetchData(array $data)
    {
        //$description = ($data["description"]) ? $data["description"] : '';
        $nom_plat = !empty($data['nom_plat']) ? $data['nom_plat'] : 'Unnamed Plat';
        $description = !empty($data["description"]) ? $data["description"] : '';
        $img_plats = !empty($data["img_plats"]) ? $data["img_plats"] : 'default.jpg';

        // Gestion des dates nulles ou vides
        $created_at = !empty($data['created_at']) ? new \DateTime($data['created_at']) : new \DateTime();
        $updated_at = !empty($data['updated_at']) ? new \DateTime($data['updated_at']) : new \DateTime();
        $deleted_at = !empty($data['deleted_at']) ? new \DateTime($data['deleted_at']) : new \DateTime();

        $type_plats = !empty($data['type_plats']) ? $data['type_plats'] : '';
        $prix_plats = isset($data['prix_plats']) ? (float)$data['prix_plats'] : 0.0;


        $plat = new Plat(
            (int)$data['id_plat'],
            $data['nom_plat'],
            $description,
            $img_plats,
            $created_at,
            $updated_at,
            $deleted_at,
            self::getTypePlat($type_plats),
            $prix_plats,
        );
        return $plat;
    }

    public function ajouter_plat(array $data, array $files = [])
    {
        if (
            isset($data['name_plat'], $data['description'], $files['image_plat'], $data['prix_plat'], $data['type_plat']) &&
            trim($data['name_plat']) !== "" &&
            trim($data['description']) !== "" &&
            $files['image_plat']['error'] === UPLOAD_ERR_OK &&
            trim($data['prix_plat']) !== "" &&
            trim($data['type_plat']) !== ""
        ) {
            $nomPlat = trim($data["name_plat"]);
            $filenameImg = basename($files['image_plat']['name']);
            $description = trim($data["description"]);
            $prixPlat = trim($data["prix_plat"]);
            $type_plat = trim($data["type_plat"]);

            // Vérifie que le prix est bien un nombre
            if (!is_numeric($prixPlat)) {
                throw new InvalidArgumentException('Le prix doit être un nombre'); // ou throw une exception si tu préfères
            }

            // ✅ Dossier de destination
            $destinationDir = __DIR__ . '/../../data/Plats/Images/';
            if (!is_dir($destinationDir)) {
                mkdir($destinationDir, 0755, true); // Crée le dossier s'il n'existe pas
            }

            // ✅ Déplacement du fichier
            $tmpPath = $files['image_plat']['tmp_name'];
            $finalPath = $destinationDir . $filenameImg;

            if (!move_uploaded_file($tmpPath, $finalPath)) {
                throw new RuntimeException("Erreur lors de l'enregistrement de l'image.");
            }

            // ✅ URL stockée en BDD
            $imageUrl = Constante::base_url() . '/app/data/Plats/Images/' . $filenameImg;

            $sql = "INSERT INTO plats (nom_plat, description, img_plats, prix_plats, type_plats) VALUES 
                    (?, ?, ?, ?, ?)";

            //var_dump($type_plat);
            $stmt = $this->database->executeSqlPrepareStatement($sql, [
                $nomPlat,
                $description,
                $filenameImg,
                $prixPlat,
                $type_plat,
            ]);
            return $stmt->rowCount() > 0; // true si la mise à jour a modifié une ligne
        }
        return false;
    }


    //essaye de faire modifier une recette
    public function modifier_plat(int $id_plat, array $data, array $files = [])
    {
        if (
            isset($data['name_plat'], $data['description'], $files['image_plat'], $data['prix_plat'], $data['type_plat']) &&
            trim($data['name_plat']) !== "" &&
            trim($data['description']) !== "" &&
            $files['image_plat']['error'] === UPLOAD_ERR_OK &&
            trim($data['prix_plat']) !== "" &&
            trim($data['type_plat']) !== ""
        ) {
            $nomPlat = trim($data["name_plat"]);
            $filenameImg = basename($files['image_plat']['name']);
            $description = trim($data["description"]);
            $prixPlat = trim($data["prix_plat"]);
            $type_plat = trim($data["type_plat"]);

            // Vérifie que le prix est bien un nombre
            if (!is_numeric($prixPlat)) {
                throw new InvalidArgumentException('Le prix doit être un nombre'); // ou throw une exception si tu préfères
            }

            // ✅ Dossier de destination
            $destinationDir = __DIR__ . '/../../data/Plats/Images/';
            if (!is_dir($destinationDir)) {
                mkdir($destinationDir, 0755, true); // Crée le dossier s'il n'existe pas
            }

            // ✅ Déplacement du fichier
            $tmpPath = $files['image_plat']['tmp_name'];
            $finalPath = $destinationDir . $filenameImg;

            if (!move_uploaded_file($tmpPath, $finalPath)) {
                throw new RuntimeException("Erreur lors de l'enregistrement de l'image.");
            }

            // ✅ URL stockée en BDD
            $imageUrl = Constante::base_url() . '/app/data/Plats/Images/' . $filenameImg;

            $sql = "UPDATE platS 
                    SET nom_plat = ?, description = ?, img_plats = ?, prix_plats = ?, type_plats = ?, updated_at = ?
                    WHERE id_plat = ?";

            //var_dump($type_plat);
            $stmt = $this->database->executeSqlPrepareStatement($sql, [
                $nomPlat,
                $description,
                $filenameImg,
                $prixPlat,
                $type_plat,
                (new \DateTime())->format('Y-m-d H:i:s'),
                $id_plat // Assure-toi que $id est défini avant
            ]);
            return $stmt->rowCount() > 0; // true si la mise à jour a modifié une ligne
        }
        return false;
    }

    public function supprimer_plat(int $id)
    {
        $sql = "DELETE FROM plats WHERE id_plat = ?";
        $stmt = $this->database->executeSqlPrepareStatement($sql, [$id]);
        return $stmt->rowCount() > 0;
    }

    public function searhPlatByNom(string $name_plat)
    {
        $all_plats = self::getPlats();
        $plats = [];

        foreach ($all_plats as $index => $plat) {
            //insensible a la casse
            if (stripos($plat->getNomPlat(), $name_plat) !== false) {
                $plats[] = $plat;
            }
        }
        return $plats;
    }
}
