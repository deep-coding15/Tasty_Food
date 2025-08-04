<?php

namespace App\Config;

use App\Core\SecureSession;
use App\Modeles\Utilisateurs\Role;

class SessionManager
{
    // ─── Propriétés ─────────────────────────────────────────────────────────
    private static ?SessionManager $instance = null;
    private SecureSession $session;

    // ─── Constantes ─────────────────────────────────────────────────────────
    public const TIMEOUT_LAST_ACTIVITY_MINUTES = 15;
    public const TIMEOUT_CREATED_MINUTES = 10;

    // ─── Constructeur privé ─────────────────────────────────────────────────
    private function __construct()
    {
        $this->session = SecureSession::getInstance();
        if (!$this->session->has('utilisateur')) {
            //$this->initSession();
        }
    }

    private function __clone() {}
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton");
    }

    // ─── Singleton ──────────────────────────────────────────────────────────
    public static function getInstance(): SessionManager
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // ─── Session utilisateur ────────────────────────────────────────────────

    public function initSession(string $role = 'visiteur', array $user = []): void
{
    // Assurer que le rôle est valide, sinon défaut à 'visiteur'
    $role = Role::isValid($role) ? $role : 'visiteur';

    // Valeurs par défaut pour un utilisateur
    $defaults = [
        'id_utilisateur' => null,
        'nom'            => '',
        'prenom'         => '',
        'login'          => '',
        'email'          => '',
        'is_active'      => false,
        'img_profil'     => null,
        'telephone'      => '',
        'role'           => $role,
    ];

    // Fusion sécurisée entre les valeurs par défaut et celles fournies
    $utilisateur = array_merge($defaults, array_intersect_key($user, $defaults));

    $_SESSION['UTILISATEUR'] = $utilisateur;
    // Initialisation de la session
    //$this->session->set('UTILISATEUR', $utilisateur);
    $this->session->set('CREATED', time());
    $this->session->set('LAST_ACTIVITY', time());
}


    public function regenerateSession(array $user = [], string $role = 'visiteur'): void
    {
        $this->session->destroy();
        $this->session = SecureSession::getInstance();
        $this->initSession($role, $user);
    }

    public function getSession(): SecureSession
    {
        return $this->session;
    }

    public function setMessage(?string $message = null): void
    {
        $this->session->set('MESSAGE', $message);
    }

    public function checkSecurityTimeout(): void
    {
        $lastActivity = $this->session->get('LAST_ACTIVITY');
        $created = $this->session->get('CREATED');
        $now = time();

        if ($lastActivity !== null && ($now - $lastActivity) > self::TIMEOUT_LAST_ACTIVITY_MINUTES * 60) {
            $this->session->destroy();
            return;
        }

        $this->session->set('LAST_ACTIVITY', $now);

        if ($created === null) {
            $this->session->set('CREATED', $now);
        } elseif (($now - $created) > self::TIMEOUT_CREATED_MINUTES * 60) {
            session_regenerate_id(true);
            $this->session->set('CREATED', $now);
        }
    }
}
