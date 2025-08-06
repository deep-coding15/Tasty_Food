<?php
namespace App\Controleurs\Client;

use App\Core\Controleur;
use App\Modeles\PlatRepository;

class MenuControleur extends Controleur
{
    public function afficherMenu()
    {
        $type = [
            'jour' => 'Menu du jour',
            'enfant' => 'Menu Enfant',
        ];
    }
}