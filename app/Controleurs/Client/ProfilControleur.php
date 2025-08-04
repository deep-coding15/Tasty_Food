<?php

namespace App\Controleurs\Client;

use App\Config\SessionManager;
use App\Core\Autoloader;
use App\Core\Controleur;
use App\Modeles\PlatRepository;

require_once dirname(__DIR__) . '../Core/Autoloader.php';
Autoloader::register();
class ProfilControleur extends Controleur
{
    public function __construct() {}
    public function afficherProfil()
    {
        $utilisateur = SessionManager::getInstance()->getSession()->get('utilisateur');
        
        // Affiche la vue du profil en passant les données utilisateur
        $this->rendreVue('client/profil', [
            'utilisateur' => $utilisateur
        ]);
    }
}
