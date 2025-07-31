<?php

namespace App\Modeles\Utilisateurs;

use App\Config\Constante;
use App\Core\Exceptions\UtilisateurException;

error_reporting(E_ALL);
ini_set('display_errors', 1);

use App\Config;
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
    private string $img_profil;
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
        $this->img_profil = Constante::base_url() . '/data/Profile/Images/' . $img_profil;
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
/* include __DIR__ . "/../include/init.php";
require_once __DIR__ . "/../../config/config.php";
 */

use App\Config\SessionManager;
use App\Lib\Utils;

$instance = SessionManager::getInstance();
$_sessionManager = $instance->getSession();
//var_dump($instance);
//echo 'salam';
//var_dump($_sessionManager);
class UtilisateurRepository
{
    /**
     * Elle sert à accéder à la connexion partagée dans chaque instance de UtilisateurRepository.
     * @var 
     */
    public ?Database $database = null;

    /**
     * Pour utiliser une connexion partagée (singleton) dans UtilisateurRepository, 
     * Il faut déclarer une propriété statique pour la base de données et l’utiliser dans le constructeur.
     * @var 
     */
    //private static ?Database $sharedDatabase = null; // Ajoute cette ligne
    //private ?\PDO $pdo = null;


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
        /* $stmt = $conn->prepare();
        $stmt->execute([$login]);
         */
        return $stmt->fetchColumn() > 0;
    }



    /**
     * Permmet d'inserer un utilisateur dans la base de donnees lorsqu'on clique sur le formulaire de login
     * @param array $postData : table renvoyé par le formulaire
     * @return void
     */
    public function logIn(array $postData): bool
    {

        //echo 'hi';
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


            /* if ($this->pdo === null) {
            $this->pdo = Database::getInstance()->getConnection();
             */ //$this->pdo = self::$sharedDatabase->getConnection();
        }
        /* $database = new Database();
            $pdo = $database->getConnection();
             */
        $image = 'default_profile_photo.jpg';
        $login = self::genererLoginUnique($firstname, $lastname, $this->database->getConnection());

        $sql = "INSERT INTO utilisateur(nom, prenom, login, 
                password, img_profil, email, telephone, role, 
                is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        /* $resultStatus = $this->database->executeSqlPrepareStatement($sql, [
                $lastname,
                $firstname,
                $login,
                password_hash($password, PASSWORD_DEFAULT),
                $image,
                $email,
                $telephone,
                'visiteur',
                false
            ]); */

        $db = Database::getInstance();
        $resultStatus = $db->executeSqlPrepareStatement($sql,  [
            $lastname,
            $firstname,
            $login,
            password_hash($password, PASSWORD_DEFAULT),
            $image,
            $email,
            $telephone,
            'visiteur',
            false
        ]);
        echo "Nombre de lignes affectées : " . $resultStatus->rowCount();

        $lastid = $this->database->getConnection()->lastInsertId();
        //$this->connection->lastInsertId();

        if ($resultStatus  && $resultStatus->rowCount() > 0) {
            echo 'ID insere : ' . $lastid;
            $this->initialiserSessionUtilisateur($email);
            echo "Vos informations de connection ont été enregistré avec succès";
            return true;
        } else {
            var_dump($resultStatus);
            throw new \App\Core\Exceptions\UtilisateurException("L'enregistrement dans la base de données a echoué");
        }
    }

    function validerLogin($email, $password)
    {
        /* global $_session; */
        $sql = "SELECT id_utilisateur, role, password, email FROM utilisateur WHERE email = :email";
        $statmt = $this->database->executeSqlPrepareStatement($sql, [":email" => $email]);
        /* $pdo = $this->getPDO();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([":email" => $email]);
         */
        $result = $statmt->fetch(\PDO::FETCH_ASSOC);

        if (!$result || !password_verify($password, $result["password"])) {
            throw new UtilisateurException('Le mot de passe entré par l\' utilisateur est incorrect');
            //return false; // Échec de la connexion
        }

        echo $email;
        // Récupération des informations
        $role = $result['role'];
        $id = $result['id_utilisateur'];
        $emailResult = $result['email'];

        // Mise à jour du statut actif
        $sql_verify = "UPDATE utilisateur SET is_active = 1 WHERE email = :email";
        /* $stmt_update = $pdo->prepare($sql_verify);
        $stmt_update->execute([":email" => $email]);
 */

        $this->database->executeSqlPrepareStatement($sql_verify, [":email" => $email]);

        $sql_ver = "select is_active from utilisateur where email = :email";
        $result = $this->database->executeSqlPrepareStatement($sql_ver, [':email' => $email]);
        var_dump($result->fetch());

        global $_sessionManager;
        $_sessionManager->regeneratedSessionUtilisateur();
        /* $_session->destroy();
        $_session = (SessionManager::getInstance())->initSessionUtilisateur();
         */
        $this->initialiserSessionUtilisateur($email);
        /* $_session->set('utilisateur', ['role' => $role]);
        $_session->set('utilisateur', ['email' =>  $email]);
        $_session->set('utilisateur', ['id' => $id]);
 */
        // Redirection en fonction du rôle
        global $_utilisateur;
        switch ($role) {
            case 'administrateur':
                $_utilisateur['role'] = 'administrateur';
                $_sessionManager->get('utilisateur')['role'] = 'administrateur';
                //$_utilisateur->set('role', 'administrateur');
                header('Location: ' . Constante::base_url() . '/src/templates/dashboard.php');
                exit();

            case 'visiteur':
                $_sessionManager->get('utilisateur')['role'] = 'visiteur';
                //$_utilisateur->set('role', 'visiteur');
                header('Location: ' . Constante::base_url() . '/src/templates/menu.php');
                exit();

            case 'client':
                header('Location: ' . Constante::base_url() . '/src/templates/dashboard.php');
                exit();

            default:
                header('Location: ' . Constante::base_url() . '/src/templates/dashboard.php');
                exit();
        }
    }
    public function signUp(array $postData)
    {
        //echo 'hi';
        global $_sessionManager;
        //var_dump($_sessionManager);
        if (
            isset($postData["password"]) && trim($postData["password"]) != ""
            && isset($postData["email"]) && trim($postData["email"]) != ""
        ) {
            $password = trim($postData["password"]);
            $email = trim($postData["email"]);

            
            if (self::validerSignup($email, $password)) {
                $message = "Vos informations de connection sont corrects";
                
            } else {
                $message =  "Vos informations de connection ne sont pas corrects";
            }
            //echo $message;
            /* $_session->set('MESSAGE', $message);
            SecureSession::getMessage($message);
 */
            /* if ($this->session->role()) {
                # code...
            } */
            //var_dump($this->session);
            //$this->redirectByRole();
            /* if ($this->session === 'visiteur') {
                (new Utils())->redirect(BASE_URL . '/index.php', 301);
            } else if ($this->session === 'admin') {
                (new Utils())->redirect(BASE_URL . '/src/templates/dashboard.php', 301);
            } */
        }
    }
    /* $this->session->set('utilisateur', [
            'id'         => $utilisateur['id_utilisateur']
 */
    public function redirectByRole(): void
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
    }

    function validerSignup($email, $password)
    {
        global $_sessionManager;
        $sql = "SELECT id_utilisateur, role, password, email FROM utilisateur WHERE email = :email";

        $stmt = $this->database->executeSqlPrepareStatement($sql, [
            ":email" => $email
        ]);
        //echo 'password: ' . $password;
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        //echo 'dbpassword: ' . $result['password'];
        if (!$result || !password_verify($password, $result['password'])) {
            throw new UtilisateurException('le mot de passe n\'est pas valide');
        }
        $role = $result['role'];
        $id = $result['id_utilisateur'];
        $dbEmail = $result['email'];

        //active le compte
        $sql_verify = "UPDATE utilisateur SET is_active = 1 WHERE email = :email";
        $stmt = $this->database->executeSqlPrepareStatement($sql_verify, [
            ":email" => $dbEmail
        ]);
        //var_dump( $stmt->fetch());

        /* try {
            $_sessionManager->regeneratedSessionUtilisateur();
            $this->initialiserSessionUtilisateur($email);
            $_sessionManager->role($role);
        } catch (\RuntimeException $exception) {
            echo 'Utilisateur introuvable avec l\'email' . htmlspecialchars($dbEmail);
            $exception->getTrace();
        } */

        return true;
    }

    public function initialiserSessionUtilisateur(string $email): void
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

        /* $roleID = self::role($utilisateur['role']);
        $_sessionManager = new SecureSession($roleID);
         */ // Stocker toutes les données nécessaires sous une seule clé

        $instance = SessionManager::getInstance();
        $_sessionManager = $instance->getSession();

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
    }

    public function reinitialiserSessionUtilisateur(string $email)
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
         */
        $utilisateur = $stmt->fetch();

        if (!$utilisateur) {
            throw new \RuntimeException("Utilisateur introuvable avec l'email : $email");
        }

        /* $roleID = self::role($utilisateur['role']);
        $_sessionManager = new SecureSession($roleID);
         */ // Stocker toutes les données nécessaires sous une seule clé
        $_sessionManager = $_sessionManager->regeneratedSessionUtilisateur();
        /* $_sessionManager = $_sessionManager->
        $_sessionManager->destroy();
        $_sessionManager = new SecureSession(); */
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
    }

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
        //$this->database->debugBaseInfo();

        /* $stmt = $this->database->getConnection()->query("SELECT DATABASE() as db");
        $db = $stmt->fetch()['db'];
        echo "Base connectée dans l'appli : " . $db;
         */
        return $stmt->fetchAll();
    }
}
