<?php

namespace App\Modeles\Utilisateurs;

use App\Config\Constante;
use App\Core\Exceptions\UtilisateurException;
use App\Core\SecureSession;

error_reporting(E_ALL);
ini_set('display_errors', 1);

use App\Config;
use App\Config\ConstanteServer;
use App\Core\Database;

class Utilisateur
{
    private int $id;
    private string $firstname;
    private string $lastname;
    private string $email;
    private string $login;
    private string $password;
    private bool $is_active;
    private string $img_profil = '/default_profile_photo.jpg';
    private int $telephone;
    private \DateTime $created_at;
    private \DateTime $updated_at;
    private static int $nb_person = 0;

    public function __construct(int $id, string $firstname, string $lastname, string $email, string $login, string $password, bool $is_active, string $img_profil, int $telephone, \DateTime $created_at, \DateTime $updated_at)
    {
        $this->id = $id;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->login = $login;
        $this->password = $password;
        $this->is_active = $is_active;
        $this->img_profil = ConstanteServer::base_url_img_profil() . $img_profil;
        //$this->img_profil = $img_profil;
        $this->telephone = $telephone;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        self::$nb_person++;
    }
    public function getId(): int
    {
        return $this->id;
    }
    public function getFirstname(): string
    {
        return $this->firstname;
    }
    public function getLastname(): string
    {
        return $this->lastname;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getLogin(): string
    {
        return $this->login;
    }
    public function getPassword(): string
    {
        return $this->password;
    }
    public function is_active(): bool
    {
        return $this->is_active;
    }
    public function getImgProfil(): string
    {
        return $this->img_profil;
    }
    public function getTelephone(): string
    {
        return $this->telephone;
    }
    public function getCreatedAt(): \DateTime
    {
        return $this->created_at;
    }
    public function getUpdatedAt(): \DateTime
    {
        return $this->updated_at;
    }
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }
    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
    public function setIsActive(bool $is_active): void
    {
        $this->is_active = $is_active;
    }
    public function setImgProfil(string $img_profil): void
    {
        $this->img_profil = $img_profil;
    }
    public function setTelephone(int $telephone): void
    {
        $this->telephone = $telephone;
    }
    public function setCreatedAt(\DateTime $created_at): void
    {
        $this->created_at = $created_at;
    }
    public function setUpdatedAt(\DateTime $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    /**
     * This functions return a complete path of the profile image for a specific person
     * @param string $img_profil the name of a file
     * @return string
     */
    public function getRealImgProfile(string $img_profil): string
    {
        return Constante::base_url() . '/data/Profile/Images/' . $img_profil;
    }
}

use App\Config\SessionManager;
use App\Core\Exceptions\TypeUserException;
use App\Lib\Utils;
        
class UtilisateurRepository
{
    /**
     * Elle sert à accéder à la connexion partagée dans chaque instance de UtilisateurRepository.
     * @var 
     */
    private ?Database $database = null;
    private ?SessionManager $sessionManager = null;
    
    public function getDatabase(): Database|null
    {
        return $this->database;
    }

    public function getSessionManager(): SessionManager|null
    {
        return $this->sessionManager;
    }
    public function __construct()
    {
        if ($this->database === null) {
            //$this->pdo = Database::getInstance()->getConnection();
            $this->database = Database::getInstance();
            $this->sessionManager = SessionManager::getInstance();
        }
        //$this->session = $_session;
        $this->sessionManager->initSession();
        $this->database = Database::getInstance();
    }
    function genererLogin($prenom, $nom)
    {
        // Nettoyage de l’entrée : suppression des espaces, accents, etc.
        $prenom = strtolower(self::supprimerCaracteresSpeciaux($prenom));
        $nom = strtolower(self::supprimerCaracteresSpeciaux($nom));

        // Création du login : initiale du prénom + nom
        $login = substr($prenom, 0, 1) . $nom;

        return $login;
    }
    function supprimerCaracteresSpeciaux($texte)
    {
        $texte = iconv('UTF-8', 'ASCII//TRANSLIT', $texte); // Enlève les accents
        $texte = preg_replace('/[^a-zA-Z0-9]/', '', $texte); // Enlève caractères spéciaux
        return $texte;
    }
    function genererLoginUnique($prenom, $nom, $conn)
    {
        $baseLogin = self::genererLogin($prenom, $nom);
        $login = $baseLogin;
        $i = 1;

        // Vérifie dans la base si le login existe déjà
        while (self::loginExiste($login)) {
            $login = $baseLogin . $i;
            $i++;
        }

        return $login;
    }

    function loginExiste($login)
    {
        $sql = "SELECT COUNT(*) FROM utilisateur WHERE login = ?";
        $stmt = $this->database->executeSqlPrepareStatement($sql, [$login]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Permmet d'inserer un utilisateur dans la base de donnees lorsqu'on clique sur le formulaire de login
     * @param array $postData : table renvoyé par le formulaire
     * @return void
     */
    public function logIn(array $postData): bool
    {
        if (
            isset($postData["firstname"]) && trim($postData["firstname"]) != ""
            && isset($postData["lastname"]) && trim($postData['lastname']) != ""
            && isset($postData["password"]) && trim($postData["password"]) != ""
            && isset($postData["email"]) && trim($postData["email"]) != ""
            && isset($postData["telephone"]) && trim($postData["telephone"]) != ""
        ) {
            $firstname = trim($postData['firstname'] ?? '');
            $lastname = trim($postData['lastname'] ?? '');
            $email = trim($postData['email'] ?? '');
            $password = $postData['password'] ?? '';
            $telephone = trim($postData['telephone'] ?? '');
        }
        $image = 'default_profile_photo.jpg';
        $login = self::genererLoginUnique($firstname, $lastname, $this->database->getConnection());

        $sql = "INSERT INTO utilisateur(nom, prenom, login, 
                password, img_profil, email, telephone, role, 
                is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $db = Database::getInstance();
        $role = 'visiteur';
        $user = [
            $lastname,
            $firstname,
            $login,
            password_hash($password, PASSWORD_DEFAULT),
            $image,
            $email,
            $telephone,
            $role,
            false
        ];

        $resultStatus = $db->executeSqlPrepareStatement($sql,  $user);
        echo "Nombre de lignes affectées : " . $resultStatus->rowCount();

        $lastid = $this->database->getConnection()->lastInsertId();
        //$this->connection->lastInsertId();

        if ($resultStatus  && $resultStatus->rowCount() > 0) {
            echo 'ID insere : ' . $lastid;
            $this->sessionManager->regeneratedSessionUtilisateurByUsersArray($user, $role);
            echo "Vos informations de connection ont été enregistré avec succès";
            return true;
        } else {
            var_dump($resultStatus);
            throw new UtilisateurException("L'enregistrement dans la base de données a echoué");
        }
    }

    function validerLogin($email, $password)
    {
        //requete pour recuperer l'utilisateur par email
        $sql = "SELECT * FROM utilisateur WHERE email = :email";
        $statmt = $this->database->executeSqlPrepareStatement($sql, [":email" => $email]);
        $user = $statmt->fetch(\PDO::FETCH_ASSOC);

        //verification du mot de passe
        if (!$user || !password_verify($password, $user["password"])) {
            throw new UtilisateurException("Le mot de passe ou l'email est incorrect.");
        }

        // Mise à jour du statut actif
        $sqlUpdate = "UPDATE utilisateur SET is_active = 1 WHERE email = :email";
        $this->database->executeSqlPrepareStatement($sqlUpdate, [":email" => $email]);

        //initialisation de la session utilisateur
        $this->sessionManager->regeneratedSessionUtilisateurByUsersArray($user, $user['role']);
       
        //redirection selon le role
        $this->redirectByRole($user);
        exit();
    }

    private function redirectByRole(array $user){
        $basePublic = Constante::base_url_public();
        $baseClient = Constante::base_url_vues_client();
        $baseAdmin = Constante::base_url_vues_admin();
        switch($user['role']){
            case 'visiteur':
                (new Utils)->redirect($baseClient . "/accueil.php");
                break;
            case 'client':
                (new Utils)->redirect($baseClient . "/menu.php");
                break;
            case 'administrateur':
                (new Utils)->redirect($baseAdmin . "/tableau_de_bord.php");
                break;
            default:
                (new Utils)->redirect($baseClient . "/accueil.php");
                break;
        }
    }

    public function signUp(array $postData): bool
    {
        //var_dump($_sessionManager);
        if (
            isset($postData["password"]) && trim($postData["password"]) != ""
            && isset($postData["email"]) && trim($postData["email"]) != ""
        ) {
            $password = trim($postData["password"]);
            $email = trim($postData["email"]);


            try{
                $this->validerSignup($email, $password);
                $message = "Vos informations de connection sont corrects. Votre compte est maintenant actif";
            }
            catch(UtilisateurException $exception){
                $message = "Erreur : " . $exception->getMessage();
            }

            
            //init session message
            $this->sessionManager->initSessionMessage( $message);
            
            SecureSession::showMessage($message); 
            return true;           
        }
        return false;
    }
    
    /* public function redirectByRole(): void
    {
        global $_sessionManager;
        $roleRoutes = [
            'visiteur' => Constante::base_url() . '/index.php',
            'administrateur'    => Constante::base_url() . '/src/templates/dashboard.php',
        ];

        $user = $_sessionManager->get('utilisateur');
        $role = $user['role'] ?? 'visiteur';

        echo $role;
        echo "Redirection vers : " . $roleRoutes[$role];
        die();
        if (isset($roleRoutes[$role])) {
            (new Utils())->redirect($roleRoutes[$role], 301);
        } else {
            (new Utils())->redirect(Constante::base_url() . '/index.php', 301);
        }
    } */

    function validerSignup(string $email, string $password)
    {
        $_sessionManager = SessionManager::getInstance();
        // Vérifie que les paramètres ne sont pas vides
        if (empty($email) || empty($password)) {
            throw new UtilisateurException("Email ou mot de passe manquant.");
        }

        $sql = "SELECT * FROM utilisateur WHERE email = :email";

        $stmt = $this->database->executeSqlPrepareStatement($sql, [
            ":email" => $email
        ]);
        
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if (!$user) {
            throw new UtilisateurException("Aucun utilisateur trouvé avec cet email.");
        }

        if (!password_verify($password, $user['password'])) {
            throw new UtilisateurException('le mot de passe n\'est pas valide');
        }
        
        // Active le compte uniquement s’il n'est pas déjà actif
        if ((int)$user['is_active'] === 0) {
            $sqlVerify = "UPDATE utilisateur SET is_active = 1 WHERE email = :email";
            $this->database->executeSqlPrepareStatement($sqlVerify, [":email" => $email]);
        }
        echo 'user in valider signup';
        var_dump($user);
        $_sessionManager->initSessionUtilisateur($user['role'], $user);
        
        return true;
    }

    /* public function initialiserSessionUtilisateur(string $email): void
    {
        $sql = "SELECT 
                id_utilisateur, 
                nom, 
                prenom, 
                login, 
                email, 
                is_active, 
                img_profil, 
                telephone, 
                role 
            FROM utilisateur 
            WHERE email = ?";

        $pdoStatement = $this->getDatabase()->executeSqlPrepareStatement($sql, [$email]);
        $utilisateur = $pdoStatement->fetch();

        if (!$utilisateur) {
            throw new \RuntimeException("Utilisateur introuvable avec l'email : $email");
        }

        $session = new SecureSession();
        $session->set('utilisateur', [
            'id'         => $utilisateur['id_utilisateur'],
            'nom'        => $utilisateur['nom'],
            'prenom'     => $utilisateur['prenom'],
            'login'      => $utilisateur['login'],
            'email'      => $utilisateur['email'],
            'is_active'  => (bool) $utilisateur['is_active'],
            'img_profil' => $utilisateur['img_profil'],
            'telephone'  => $utilisateur['telephone'],
            'role'       => $utilisateur['role'],
        ]);
        $this->sessionManager->regeneratedSessionUtilisateur($utilisateur['role'], $session);
    } */

    /* public function reinitialiserSessionUtilisateur(string $email)
    {
        global $_sessionManager;
        $sql = "SELECT 
                id_utilisateur, 
                nom, 
                prenom, 
                login, 
                email, 
                is_active, 
                img_profil, 
                telephone, 
                role 
            FROM utilisateur 
            WHERE email = ?";

        $stmt = $this->database->executeSqlPrepareStatement($sql, [$email]);
        /* $database = new Database();
        $pdo = $database->executeSqlPrepareStatement($sql, [$email]);
         *
        $utilisateur = $stmt->fetch();

        if (!$utilisateur) {
            throw new \RuntimeException("Utilisateur introuvable avec l'email : $email");
        }

        $_sessionManager = $_sessionManager->regeneratedSessionUtilisateur();
        
        $_sessionManager->set('utilisateur', [
            'id'         => $utilisateur['id_utilisateur'],
            'nom'        => $utilisateur['nom'],
            'prenom'     => $utilisateur['prenom'],
            'login'      => $utilisateur['login'],
            'email'      => $utilisateur['email'],
            'is_active'  => (bool) $utilisateur['is_active'],
            'img_profil' => $utilisateur['img_profil'],
            'telephone'  => $utilisateur['telephone'],
            'role'       => $utilisateur['role'],
        ]);
    } */

    /**
     * Return a number that represent the type of the user of the application
     * @param string $role = [
     *  - visiteur = 0
     *  - client = 1
     *  - personnel = 2
     *  - administrateur = 3
     * ]
     * @return int
     */
    private function role(string $role): int
    {
        switch ($role) {
            case 'visiteur':
                return 0;
            case 'client':
                return 1;
            case 'personnel':
                return 2;
            case 'administrateur':
                return 3;
            default:
                return 0;
        }
    }

    public function getUtilisateurs()
    {
        $sql = "Select * from utilisateur where 1";
        $stmt = $this->database->executeSqlPrepareStatement($sql);

        return $stmt->fetchAll();
    }

    
    /**
     *  * Summary of getUtilisateurBy_X
     * @param string $type = [
     * - 'id_utilisateur'
     * - 'nom'
     * - 'prenom' 
     * - 'email'
     * - 'login' 
     * - 'role' 
     * - 'is_active'
     * - 'telephone'
     * ]
     * @param string $value : the value of the specific type
     * @throws \InvalidArgumentException
     * @return array contains an array of users that match the value for a specific type
     * 
     * $users = $repo->getUtilisateurBy_X('email', 'john', 10, 20);
     * => 10 utilisateurs dont l'email contient 'john', à partir du 21e résultat
     */
    public function getUtilisateurBy_X(string $type, string $value, int $limit = 20, int $offset = 0): array
    {
        // Liste blanche des colonnes autorisées
        $colonnesAutorisees = ['id_utilisateur', 'nom', 'prenom', 'email', 'login', 'role', 'is_active', 'telephone'];

        if (!in_array($type, $colonnesAutorisees, true)) {
            throw new TypeUserException("Colonne invalide : $type");
        }

        // Colonnes autorisées pour une recherche partielle
        $colonnesAvecLike = ['nom', 'prenom', 'email'];

        // Construction dynamique de la requête
        if (in_array($type, $colonnesAvecLike, true)) {
            $sql = "SELECT * FROM utilisateur WHERE $type LIKE :value";
            $value = '%' . $value . '%';
        } else {
            $sql = "SELECT * FROM utilisateur WHERE $type = :value";
        }

        // Ajout de la clause LIMIT / OFFSET
        $sql .= " LIMIT :limit OFFSET :offset";

        // Préparation et exécution
        $stmt = $this->database->executeSqlPrepareStatement($sql, [
            ':value' => $value,
            ':limit' => $limit,
            ':offset' => $offset
        ]);
        /* prepare($sql);
    $stmt->bindValue(':value', $value, \PDO::PARAM_STR);
    $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
    $stmt->execute(); */

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
