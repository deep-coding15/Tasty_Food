<?php
namespace App\Modeles;

use App\Config\Constante;
use App\Config\ConstanteServer;
use App\Core\Database;
use DateTime;
use InvalidArgumentException;
use PDO;
use RuntimeException;

class Menu
{
    private int $id_menu;
    private string $nom_menu;
    private string $description;
    private float $prix_total;
    private bool $actif;
    private DateTime $created_at;
    private DateTime $updated_at;
    private ?DateTime $deleted_at;


    public function __construct(int $id_menu, string $nom_menu, string $description, float $prix_total,
        bool $actif = true, ?DateTime $created_at = null, ?DateTime $updated_at = null, ?DateTime $deleted_at = null)  {
        
        $dateTime = new DateTime();
        //$date = $dateTime->format('Y-m-d H:i:s');

        $this->id_menu = $id_menu;
        $this->nom_menu = $nom_menu;
        $this->description = $description;
        $this->prix_total = $prix_total;
        $this->actif = $actif;
        $this->created_at = $created_at ?? $dateTime;
        $this->updated_at = $updated_at ?? $dateTime;
        $this->deleted_at = $deleted_at ?? null;
    }
    public function getIdMenu(): int
    {
        return $this->id_menu;
    }

    public function setIdMenu(int $id_menu): void
    {
        $this->id_menu = $id_menu;
    }

    public function getNomMenu(): string
    {
        return $this->nom_menu;
    }

    public function setNomMenu(string $nom_menu): void
    {
        $this->nom_menu = $nom_menu;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getPrixTotal(): float
    {
        return $this->prix_total;
    }

    public function setPrixTotal(float $prix_total): void
    {
        $this->prix_total = $prix_total;
    }

    public function isActif(): bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): void
    {
        $this->actif = $actif;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTime $created_at): void
    {
        $this->created_at = $created_at;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTime $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    public function getDeletedAt(): ?\DateTime
    {
        return $this->deleted_at;
    }

    public function setDeletedAt(?\DateTime $deleted_at): void
    {
        $this->deleted_at = $deleted_at;
    }

    public function __tostring(){
        return "Menu: {$this->nom_menu}\n" . 
            "Id: {$this->id_menu}\n" . 
            "Description: {$this->description}\n" . 
            "Prix Total: {$this->prix_total}\n" . 
            "Actif: {$this->actif}\n" .
            "Created at: {$this->created_at->format('Y-m-d H:i:s')} \n" .
            "Updated at: {$this->updated_at->format('Y-m-d H:i:s')} \n" .
            "Deleted at: {$this->deleted_at->format('Y-m-d H:i:s')} \n"
        ;
    }
}

class MenuRepository{
    public ?Database $database = null;
    public function getDatabase(): Database|null{
        return $this->database;
    }

    public function __construct(){
        if($this->database === null){
            $this->database = Database::getInstance();
        }
        $this->database = Database::getInstance();
    }

    public function getMenu(int $identifier){
        $sql = "SELECT * FROM menus WHERE id_menu = :id";

        $statement = $this->database->executeSqlPrepareStatement($sql, [':id' => $identifier]);
        $data = $statement->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return self::fetchData($data);
        }

        return null;
    }
    public function getMenus()  {
        $sql = "SELECT * FROM menus WHERE 1";

        $statement = $this->database->executeSqlQueryStatement($sql);

        $menus = [];
        while ($data = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $menu = self::fetchData($data);

            $menus[] = $menu;
        }

        return $menus;
    }

    private function getPrixTotalByPrixPlatArray(array $prix_plats)  {
        $somme = 0;
        foreach ($prix_plats as $prix_plat) {
            $somme += $prix_plat;
        }
        return $somme;
    }
    public function getPlatsByMenuId($identifier): array  {
        //$sql = "SELECT id_plat FROM plats NATURAL JOIN menu_plats NATURAL JOIN menus WHERE id_menu = :id_menu";
        $sql = "SELECT p.id_plat, p.prix_plats
            FROM plats p 
            JOIN menu_plats mp ON mp.id_plat = p.id_plat
            JOIN menus m ON mp.id_menu = m.id_menu
            WHERE m.id_menu = :id_menu
        ";
        
        $statement = $this->database->executeSqlPrepareStatement($sql, [':id_menu' => $identifier]);
        $id_plats = $statement->fetchAll(PDO::FETCH_ASSOC);
        //var_dump($id_plats);
        $platsRepository = new PlatRepository();
        $plats = [];
        $prixPlats = [];
        foreach ($id_plats as $id_plat) {
            /* echo 'id_plat : ';
            var_dump($id_plat);
             */$plat = $platsRepository->getPlat($id_plat['id_plat']);
            $plats[] = $plat;
            $prixPlats[] = $id_plat['prix_plats'];
        }
        /* echo 'plats dans getplatsbymenuid';
        var_dump($plats);
         */
        $prixTotal = $this->getPrixTotalByPrixPlatArray($prixPlats);
        return [$plats, $prixTotal];
    }

    private function fetchData(array $data){
        $id = !empty($data['id_menu']) ? $data['id_menu'] : '';
        $nom_menu = !empty($data['nom_menu']) ? $data['nom_menu'] : 'Unnamed menu';
        $description = !empty($data['description']) ? $data['description'] : '';
        $prix_total = !empty($data['prix_total']) ? $data['prix_total'] : '';
        $actif = !empty($data['actif']) ? $data['actif'] : '';
        $created_at = !empty($data['created_at']) ? $data['created_at'] : '';
        $updated_at = !empty($data['update_at']) ? $data['update_at'] : '';
        $deleted_at = !empty($data['deleted_at']) ? $data['deleted_at'] : '';
        
        $menu = new Menu(
            (int)$id,
            $nom_menu,
            $description,
            $prix_total,
            $actif,
            new DateTime($created_at),
            new DateTime($updated_at),
            new DateTime($deleted_at)
        );
        return $menu;
    }

    /**
     * Fonction qui sera appele dans la partie administrateur pour pourvoir ajouter un menu dans la vue
     * @param array $data
     * @param array $files
     * @throws \InvalidArgumentException si le prix n'est pas numeric ou si le prix est negatif
     * @throws \RuntimeException
     * @return bool
     */
    public function ajouter_menu(array $data, array $files = []){
        if (
            isset($data['nom_menu'], $data['description'], $files['image_menu'], $data['prix_total'], $data['actif']) &&
            trim($data['nom_menu']) !== "" &&
            trim($data['description']) !== "" &&
            $files['image_menu']['error'] === UPLOAD_ERR_OK &&
            trim($data['prix_total']) !== "" &&
            trim($data['actif']) !== ""
        ) {
            $nomMenu = trim($data["nom_menu"]);
            $filenameImg = basename($files['image_menu']['name']);
            $description = trim($data["description"]);
            $prixTotalMenu = trim($data["prix_total"]);
            $actif = trim($data["actif"]);

            // Vérifie que le prix est bien un nombre
            if (!is_numeric($prixTotalMenu) || $prixTotalMenu < 0) {
                throw new InvalidArgumentException('Le prix doit être un nombre positif'); // ou throw une exception si tu préfères
            }

            // Dossier de destination
            $destinationDir = __DIR__ . '/../../data/Menu/Images/';
            if (!is_dir($destinationDir)) {
                mkdir($destinationDir, 0755, true); // Crée le dossier s'il n'existe pas
            }

            // Déplacement du fichier
            $tmpPath = $files['image_plat']['tmp_name'];
            $finalPath = $destinationDir . $filenameImg;

            if (!move_uploaded_file($tmpPath, $finalPath)) {
                throw new RuntimeException("Erreur lors de l'enregistrement de l'image au serveur.");
            }

            // URL stockée en BDD
            $imageUrl = ConstanteServer::base_path_app_data() . '/Menu/Images/' . $filenameImg;

            $sql = "INSERT INTO menus (nom_plat, description, img_plats, prix_plats, type_plats) VALUES 
                    (?, ?, ?, ?, ?)";

            //var_dump($type_plat);
            $stmt = $this->database->executeSqlPrepareStatement($sql, [
                $nomMenu,
                $description,
                $filenameImg,
                $prixTotalMenu,
                $actif,
            ]);
            return $stmt->rowCount() > 0; // true si la mise à jour a modifié une ligne
        }
        return false;
    }
    public function modifier_menu(int $id_menu, array $data, array $files = [])
    {
        if (
            isset($data['nom_menu'], $data['description'], $files['image_menu'], $data['prix_total'], $data['actif']) &&
            trim($data['nom_menu']) !== "" &&
            trim($data['description']) !== "" &&
            $files['image_menu']['error'] === UPLOAD_ERR_OK &&
            trim($data['prix_total']) !== "" &&
            trim($data['actif']) !== ""
        ) {
            $nom_menu = trim($data["nom_menu"]);
            $filenameImg = basename($files['image_menu']['name']);
            $description = trim($data["description"]);
            $prix_total = trim($data["prix_total"]);
            $actif = trim($data["actif"]);

            // Vérifie que le prix est bien un nombre
            if (!is_numeric($prix_total)) {
                throw new InvalidArgumentException('Le prix doit être un nombre'); // ou throw une exception si tu préfères
            }

            //  Dossier de destination
            $destinationDir = __DIR__ . '/../../data/Plats/Images/';
            if (!is_dir($destinationDir)) {
                mkdir($destinationDir, 0755, true); // Crée le dossier s'il n'existe pas
            }

            //  Déplacement du fichier
            $tmpPath = $files['image_plat']['tmp_name'];
            $finalPath = $destinationDir . $filenameImg;

            if (!move_uploaded_file($tmpPath, $finalPath)) {
                throw new RuntimeException("Erreur lors de l'enregistrement de l'image.");
            }

            //  URL stockée en BDD
            $imageUrl = Constante::base_url() . '/app/data/Menu/Images/' . $filenameImg;

            $sql = "UPDATE menus 
                    SET nom_plat = ?, description = ?, img_plats = ?, prix_plats = ?, type_plats = ?, updated_at = ?
                    WHERE id_menu = ?";

            //var_dump($type_plat);
            $stmt = $this->database->executeSqlPrepareStatement($sql, [
                $nom_menu,
                $description,
                $filenameImg,
                $prix_total,
                $actif,
                (new DateTime())->format('Y-m-d H:i:s'),
                $id_menu // Assure-toi que $id est défini avant
            ]);
            return $stmt->rowCount() > 0; // true si la mise à jour a modifié une ligne
        }
        return false;
    }

    public function supprimer_menu(int $id)
    {
        $sql = "DELETE FROM menus WHERE id_menu = ?";
        $stmt = $this->database->executeSqlPrepareStatement($sql, [$id]);
        return $stmt->rowCount() > 0;
    }
    public function searhPlatByNom(string $name_plat)
    {
        $all_menu = self::getMenus();
        $menus = [];

        foreach ($all_menu as $index => $menu) {
            //insensible a la casse
            if (stripos($menu->getNomMenu(), $name_plat) !== false) {
                $menus[] = $menu;
            }
        }
        return $menus;
    }
}