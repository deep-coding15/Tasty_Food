<?php
namespace App\Core;
use App\Modeles\Utilisateurs\Role;
/* require_once __DIR__ . '/Role.php'; */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/***
 *  - ID : id du user
 *  - LAST_NAME
 *  - FIRST_NAME
 * 
 *  - LOGIN
 *  - PASSWORD
 *  - EMAIL
 *  - IS_ACTIVE
 * 
 *  - IMG_PROFIL
 *  - PHONE 
 *  - ROLE : 
 *  - CREATED_AT
 *  - UPDATED_AT
 *  
 *  - CREATE : 
 *  - LAST_ACTIVITY :
 *  - MESSAGE :
 */
class SecureSession
{
    //private String $role;
    private static ?SecureSession $instance = null;

    //Durée d'inactivité maximale avant expiration (expiration)
    private int $timeOutLastActivity = 0;
    private int $timeOutCreated = 0;

    /**
     * Stocke la date/heure de la dernière activité (clic, chargement de page, etc.)
     * Sert à expirer automatiquement la session après inactivité
     *  @var int
     */
    private const TIMEOUT_LAST_ACTIVITY_NUMBER_OF_MINUTES = 15;

    /**
     * Stocke la date/heure de création de la session.
     * Sert à savoir quand l’ID de session a été généré pour éventuellement le régénérer périodiquement
     * @var int
     */
    private const TIMEOUT_CREATED_NUMBER_OF_MINUTES = 10;
    private const MINUTES = 60;

    /**
     * @param int positionsInRole 
     *  0 - visiteur
     *  1 - client
     *  2 - personnel
     *  3 - administrateur
     */

    private array $session;

    private function __construct(
        int $timeOutLastActivity = self::TIMEOUT_LAST_ACTIVITY_NUMBER_OF_MINUTES,
        int $timeOutCreated = self::TIMEOUT_CREATED_NUMBER_OF_MINUTES
    ) {

        //$this->role = Role::getRoleByIndex($positionsInRole) ?? 'visiteur';

        //convertit les minutes en secondes
        $this->timeOutLastActivity = $timeOutLastActivity * self::MINUTES;
        $this->timeOutCreated = $timeOutCreated * self::MINUTES;

        //Démarre une session sécurisée avec des options strictes
        /* if (session_status() === PHP_SESSION_NONE) {
            session_start([
                // Empêche l'accès au cookie de session via JavaScript (protège contre les attaques XSS)
                'cookie_httponly' => true,

                // Refuse toute session avec un identifiant invalide ou deviné (protège contre les attaques de session fixation)
                'use_strict_mode' => true,

                // Envoie le cookie de session uniquement via HTTPS si le site est en HTTPS (protège contre l'interception du cookie)
                'cookie_secure' => isset($_SERVER['HTTPS']),
            ]);
        } */
        $this->connectSession();
        $this->handleSessionSecurity();
    }

    public static function getInstance() : SecureSession {
        if(self::$instance === null){
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function connectSession()
    {
        //Démarre une session sécurisée avec des options strictes
        if (session_status() === PHP_SESSION_NONE) {
            session_start([
                // Empêche l'accès au cookie de session via JavaScript (protège contre les attaques XSS)
                'cookie_httponly' => true,

                // Refuse toute session avec un identifiant invalide ou deviné (protège contre les attaques de session fixation)
                'use_strict_mode' => true,

                // Envoie le cookie de session uniquement via HTTPS si le site est en HTTPS (protège contre l'interception du cookie)
                'cookie_secure' => isset($_SERVER['HTTPS']),
            ]);
        }
    }

    private function handleSessionSecurity(): void
    {
        //1. Vérifie l'inactivité (timeout)
        if (null !== ($this->get('LAST_ACTIVITY')) && (time() - $this->get('LAST_ACTIVITY')) > $this->timeOutLastActivity) {
            //Si l'utilisateur est inactif trop longtemps, on detruit la session
            $this->destroy();
            //header("Location: homepage.php?expired=1");//Redirige k'utilisateur vers la page de connexion
            //exit;
        } else {
            //Sinon, on met à jour le temps de derniere connexion
            $this->set('LAST_ACTIVITY', time());
        }

        //2. Regenere l'identifiant de session de façon périodique (ici toutes les 5 minutes)
        if (null == ($this->get('CREATED'))) {
            $this->set('CREATED', time());
        } else if ((time() - $this->get('CREATED')) > $this->timeOutCreated) {
            session_regenerate_id(true); //Change l'ID de session pour eviter le vol de session
            $this->set('CREATED', time()); //Réinitialise le temps de création
        }

        //3. Vérification de connexion
        /* if (!isset($_SESSION['user_id'])) {
            header("Location: login.php");
            exit;
        } */
        //$_SESSION['LOGIN'] = "";
        //self::role($this->role);
    }

    public function role($role)
    {
        switch ($role) {
            case 'client':
                $this->set('utilisateur', ['role' => $role]);
                break;
            case 'personnel':
                $this->set('utilisateur', ['role' => $role]);
                break;
            case 'administrateur':
                $this->set('utilisateur', ['role' => $role]);
                break;
            case 'visiteur':
                $this->set('utilisateur', ['role' => $role]);
                break;
            default:
                $this->set('utilisateur', ['role' => 'visiteur']);
                break;
        }
    }

    public function isAuthenticated(): bool
    {
        return isset($this->get('utilisateur')['user_id']);
    }

    public function isAuthorized(string $role): bool
    {
        return $this->get('utilisateur')['role'] === $role;
    }


    public function set(string $key, $value): void
    {
        if ($this->has($key)) {
            $this->remove($key);
        }
        $_SESSION[$key] = $value;
    }


    public function get(string $key): array | string | null
    {
        return $_SESSION[$key] ?? null;
    }

    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }


    // Détruit complètement la session
    public function destroy(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            //$_SESSION = [];
            /**
             * Supprime toutes les variables enregistrées dans $_SESSION
             * Mais ne détruit pas encore le fichier de session.
             */
            session_unset();
            /**
             * Détruit la session en cours côté serveur (le fichier de session est supprimé).
             * Le navigateur gardera encore le cookie PHPSESSID, 
             * mais il ne correspondra à aucune session active, 
             * donc l'utilisateur est considéré comme déconnecté.
             */
            session_destroy();
        }
        else {

        }
    }


    /**
     * This function show a message of confirmation or error.
     * It can disappear in 5 secondes if it is a flash message that has an id #flash-message
     */
    public static function showMessage($message)
    {
        if ($message) {
            $isError = str_contains(strtolower($message), 'échec') || str_contains(strtolower($message), 'erreur');
            $class = $isError ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800';

            echo '
        <div id="flash-message" class="' . $class . ' block p-4 rounded relative top-16 bottom-8 shadow-md text-center w-[70%] mx-auto left-[2%] transition-opacity duration-500">
            ' . htmlspecialchars($message) . '
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                setTimeout(() => {
                    const flash = document.getElementById("flash-message");
                    if (flash) {
                        flash.classList.add("opacity-0");
                        setTimeout(() => flash.remove(), 500);
                    }
                }, 5000);
            });
        </script>';
        }
    }
}
