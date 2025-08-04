<?php
//les namespaces n'ont pas leurs places dans les fichiers frontaux

require_once __DIR__ . '/../app/Core/Autoloader.php';
use App\Core\Autoloader;

Autoloader::register();
use App\Config\SessionManager;
$_sessionManager = SessionManager::getInstance();


use App\Config;
use App\Controleurs\Client\CarteControleur;
use App\Controleurs\Client\PanierControleur;
use App\Controleurs\Client\ProfilControleur;

$page = $_GET['page'] ?? 'carte';
$page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING) ?? 'carte';
//CarteControleur



$routes = [
    'carte' => [
        CarteControleur::class, 'afficherCarte'
    ],
    'panier' => [
        PanierControleur::class, 'afficherPanier'
    ],
    'profil' => [ 
        ProfilControleur::class, 'afficherProfil'
    ]
];

/**
 * Routeur simple pour les pages client.
 *
 * Ce script agit comme un contrôleur frontal (front controller).
 * Il récupère le paramètre 'page' passé dans l'URL (ex: ?page=carte)
 * et appelle dynamiquement le contrôleur et la méthode associés.
 *
 * Exemple de routes gérées :
 * - ?page=carte  => CarteControleur::afficherCarte()
 * - ?page=panier => PanierControleur::afficherPanier()
 *
 * Si la page demandée n'existe pas dans les routes définies,
 * une erreur 404 est affichée.
 *
 * Ce mécanisme permet d'éviter un switch ou if/else répétitif,
 * en rendant le code plus évolutif et plus lisible.
 */

if (array_key_exists($page, $routes)) {
    [$class, $method] = $routes[$page];
    $ctrl = new $class();
    $ctrl->$method();
} else {
    http_response_code(404);
    echo 'Page client inconnue';
}

