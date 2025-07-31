<?php
//les namespaces n'ont pas leurs places dans les fichiers frontaux
session_start();

// Autoloader manuel si pas de Composer
/**
 * Permet de charger automatiquement les classes sans devoir faire plein de require_once
 */
spl_autoload_register(function ($class) {
    //dossier racine de toutes les classes
    $baseDir = dirname(__FILE__,2) . '/app/';
    //echo 'base dir: ' . $baseDir;
    $class = str_replace('App\\', '', $class);
    //echo 'class : ' . $class;
    $path = $baseDir . str_replace('\\', '/', $class) . '.php';
    //echo 'path : ' . $path;
    if (file_exists($path)) {
        require_once $path;
    }
});

use App\Config;
use App\Controleurs\Admin;

// Vérification si l'utilisateur est admin
if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['role'] !== 'admin') {
    header('Location: /index.php?page=connexion');
    exit;
}

$page = $_GET['page'] ?? 'dashboard';
//$page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING) ?? 'carte';

use App\Controleurs\Admin\TableauDeBordControleur;
use App\Controleurs\Admin\PlatControleur;

$page = $_GET['page'] ?? 'carte';




$routes = [
    'dashboard' => [
        TableauDeBordControleur::class, 'afficherDashboard'
    ],
    'panier' => [
        PlatControleur::class, 'gererPlats'
    ],
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

