<?php
namespace App\Controleurs\Client;

require_once dirname(__DIR__) . '/../Core/Autoloader.php';
use App\Core\Autoloader;
Autoloader::register();


use App\Core\Controleur;
use App\Modeles\PlatRepository;
class CarteControleur extends Controleur
{
    public function __construct() {
        
    }
    public function afficherCarte()
    {
        $page = $_GET['page'] ?? 'default';
        $repo = new PlatRepository();

        $types = [
            'accompagnement' => 'Accompagnements',
            'dessert' => 'Desserts',
            'entree' => 'Entrees',
            'resistance' => 'Plat de resistance',
            'boisson' => 'Boissons'
        ];

        $plats = isset($types[$page]) 
            ? $repo->getPlatsByTypeName($types[$page])
            : $repo->getPlats();

        $this->rendreVue('/carte', [
            'plats' => $plats,
            'page'  => $page
        ]);
    }
}