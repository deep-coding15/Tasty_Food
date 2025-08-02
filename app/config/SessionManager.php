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
    private ?SecureSession $session = null;

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
    public function initSessionUtilisateur(string $role = 'visiteur', array  $user = [])
    {
        $this->session ??= new SecureSession();
        if (!Role::isValid($role)) :
            $role = 'visiteur';
        endif;

        $this->sessionUtilisateur($role);

    }

    public function regeneratedSessionUtilisateur(string $role = 'visiteur')
    {
        if (isset($this->session)) {
            $this->session->destroy();
        }
        $this->session = new SecureSession();
        if (!Role::isValid($role)) :
            $role = 'visiteur';
        endif;
        if (!$this->session->has('utilisateur')) {
            $this->sessionUtilisateur($role);
        }
    }

    /**
     * Summary of regeneratedSessionUtilisateurByUsersArray
     * @param array $users = [
     *  - id
     *  - nom
     *  - prenom
     *  - login
     *  - email
     *  - is_active
     *  - img_profil
     *  - telephone
     *  - role
     * ] in order please
     * @param string $role
     * @return void
     */
    public function regeneratedSessionUtilisateurByUsersArray(array $users, string $role = 'visiteur'){
        if(is_null($users))
            $this->initSessionUtilisateur($role);
        $this->getSession()->set('utilisateur', $users);
        
        if(!is_null($this->getSession()->get('password')))
            $this->getSession()->remove('password');
        
        // Si le rôle a changé, on le met à jour explicitement
        if ($this->session->get('utilisateur')['role'] !== $role) {
            $this->session->role($role);
        }
    }

    private function sessionUtilisateur(string $role = 'visiteur', ?SecureSession $session = null): void
    {
        //Si $session est null, alors assigne $this->session à $session.
        $session ??= $this->session;

        if (!$session && !$this->session) {
            throw new \RuntimeException("Session manquante.");
        }
        $utilisateur = [
            'id'         => $session->get('id'),
            'nom'        => $session->get('nom'),
            'prenom'     => $session->get('prenom'),
            'login'      => $session->get('login'),
            'email'      => $session->get('email'),
            'is_active'  => false,
            'img_profil' => $session->get('img_profil'),
            'telephone'  => $session->get('telephone'), // corrigé ici
            'role'       => $role,
        ];

        $this->session->set('utilisateur', $utilisateur);

        // Si le rôle a changé, on le met à jour explicitement
        if ($this->session->get('utilisateur')['role'] !== $role) {
            $this->session->role($role);
        }
    }


    /**
     * Initialisation d'un message de confirmation ou d'echec de session vide
     */
    public function initSessionMessage(?string $message = null)
    {
        $this->session->set('MESSAGE', $message);
    }

    public function initSession()
    {
        self::initSessionUtilisateur();
        self::initSessionMessage();
    }

    

    public function getSession(): SecureSession
    {
        if ($this->session === null)
            self::initSession();
        return $this->session;
    }

    public function setSession(SecureSession $secureSession)
    {
        $this->session = $secureSession;
    }
}