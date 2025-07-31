<?php

namespace App\Config;
//SecureSession.php'; // ou le bon chemin
use App\Core\SecureSession;
use App\Core\Autoloader;
use App\Modeles\Utilisateurs\Role;

/**
 * Cette classe est pour les sessions de l'utilisateur qui utilise le design pattern "Singleton" pour assuere une seule session tout au long de la navigation du client
 * Pour l'initialiser, on fait appel à la méthode "getInstance()"
 */
class SessionManager
{
    private static ?SessionManager $instance = null;
    private SecureSession $session;

    //constructeur privé
    private function __construct()
    {
        $this->session = new SecureSession();
        self::initSessionUtilisateur();
    }

    //Methode publique d'accès à l'instance unique
    public static function getInstance(): SessionManager
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Empêche le clonage
    private function __clone() {}

    // Empêche le wakeup (désérialisation)
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton");
    }

    /**
     * Initialise les paramètre de session utilisateur
     */
    private function initSessionUtilisateur(string $role = 'visiteur')
    {

        if (!Role::isValid($role)) :
            $role = 'visiteur';
        endif;
        if (!$this->session->has('utilisateur')) {
            // Si l'utilisateur n'est pas connecté, on initialise une session vide
            $this->session->set('utilisateur', [
                'id'         => null,
                'nom'        => null,
                'prenom'     => null,
                'login'      => null,
                'email'      => null,
                'is_active'  => false,
                'img_profil' => null,
                'telephone'  => null,
                'role'       => $role,
            ]);
            //echo 'session utilisateur';
            //var_dump($_session->get('utilisateur')); // pour déboguer, à retirer en production
        }

        if ($this->session->get('utilisateur')['role'] !== $role) {
            $this->session->role($role);
        }
        /* $this->session->set('utilisateur', [
                'role' => $role,
            ]); */

        // Si l'utilisateur est connecté, on peut récupérer ses informations
        //return $this->session->get('utilisateur');
    }

    /**
     * Initialisation d'un message de confirmation ou d'echec de session vide
     */
    public function initSessionMessage(?string $message = null)
    {
        $this->session->set('MESSAGE', $message);
    }

    public function initSession(){
        self::initSessionUtilisateur();
        self::initSessionMessage();
    }

    public function regeneratedSessionUtilisateur(SecureSession $sessionUtilisateur)
    {
        if (isset($this->session)){
            $this->session->destroy();
        }
        return $this->session = new SecureSession();
    }
    
    public function getSession(): SecureSession{
        if($this->session === null)
            self::initSession();
        return $this->session;
    }

    public function setSession(SecureSession $secureSession) {
        $this->session = $secureSession;
    }
}
//var_dump($_utilisateur); // pour déboguer, à retirer en production

//Implicitly nullable parameters are deprecated.intelephense(P1078)