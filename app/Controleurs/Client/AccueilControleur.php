<?php
namespace App\Controleurs\Client;
use App\Modeles\PlatRepository;
use App\Modeles\UtilisateurRepository;
/* require_once BASE_PATH . '/modeles/Utilisateur.php';
require_once BASE_PATH . '/modeles/Plat.php';
 */
$platRepository = new PlatRepository();
function homepage(){
    global $platRepository;
    $plats = $platRepository->getPlats();
    require_once BASE_PATH . '/vues/client/accueil.php';
}

function accompagnement(){
    global $platRepository;
    $plats = $platRepository->getPlatsByTypeName('Accompagnements');
    require_once BASE_PATH . '/vues/client/accueil.php';
}

function dessert(){
    global $platRepository;
    $plats = $platRepository->getPlatsByTypeName('Desserts');
    require_once BASE_PATH . '/vues/client/accueil.php';
}

function entree(){
    global $platRepository;
    $plats = $platRepository->getPlatsByTypeName('Entrees');
    require_once BASE_PATH . '/vues/client/accueil.php';
}

function resistance(){
    global $platRepository;
    $plats = $platRepository->getPlatsByTypeName('Plat de resistance');
    require_once BASE_PATH . '/vues/client/accueil.php';
}

function boisson(){
    global $platRepository;
    $plats = $platRepository->getPlatsByTypeName('Boissons');
    require_once BASE_PATH . '/vues/client/accueil.php';
}

