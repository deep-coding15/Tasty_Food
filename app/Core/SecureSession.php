<?php
namespace App\Core;
use App\Modeles\Utilisateurs\Role;
/* require_once __DIR__ . '/Role.php'; */
/* if (session_status() === PHP_SESSION_NONE) {
    session_start();
} */

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
    
    public const TIMEOUT_LAST_ACTIVITY_NUMBER_OF_MINUTES = 15;
    
    /**
     * Stocke la date/heure de création de la session.
     * Sert à savoir quand l’ID de session a été généré pour éventuellement le régénérer périodiquement
     * @var int
     */
    public const TIMEOUT_CREATED_NUMBER_OF_MINUTES = 10;
    public const MINUTES = 60;

    /**
     * @param int positionsInRole 
     *  0 - visiteur
     *  1 - client
     *  2 - personnel
     *  3 - administrateur
     */

    private array $session;

    
    // Ajoute ceci dans la classe SecureSession

    private function __construct()
    {
        $this->timeOutLastActivity = self::TIMEOUT_LAST_ACTIVITY_NUMBER_OF_MINUTES * self::MINUTES;
        $this->timeOutCreated = self::TIMEOUT_CREATED_NUMBER_OF_MINUTES * self::MINUTES;
        $this->start();
        $this->checkTimeouts();
    }
        
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }

    public function start(){
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

    public function checkTimeouts(): void
    {
        $now = time();
        // Inactivité
        if (isset($_SESSION['LAST_ACTIVITY']) && ($now - $_SESSION['LAST_ACTIVITY']) > $this->timeOutLastActivity) {
            $this->destroy();
            return;
        }
        $_SESSION['LAST_ACTIVITY'] = $now;

        // Durée de vie de la session
        if (!isset($_SESSION['CREATED'])) {
            $_SESSION['CREATED'] = $now;
        } elseif (($now - $_SESSION['CREATED']) > $this->timeOutCreated) {
            $this->regenerateId();
            $_SESSION['CREATED'] = $now;
        }
    }
    public function getUtilisateur(): ?array
    {
        return $this->get('UTILISATEUR');
    }

    public function regenerateId(): void
    {
        session_regenerate_id(true);
    }

    // (Optionnel) Ajoute un constructeur pour initialiser les timeouts
    
    public function isAuthenticated(): bool
    {
        $this->checkTimeouts();
        return isset($this->get('utilisateur')['user_id']);
    }

    public function isAuthorized(string $role): bool
    {
        return $this->get('utilisateur')['role'] === $role;
    }
    
    public function all(): array
    {
        $this->checkTimeouts();
        return $_SESSION;
    }

    public function set(string $key, $value): void
    {
        $this->checkTimeouts();
        if ($this->has($key)) {
            $this->remove($key);
        }
        $_SESSION[$key] = $value;
    }


    public function get(string $key): array | string | null
    {
        $this->checkTimeouts();
        return $_SESSION[$key] ?? null;
    }

    public function has(string $key): bool
    {
        $this->checkTimeouts();
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
