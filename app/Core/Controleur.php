<?php
namespace App\Core;

class Controleur
{
    /**
     * Rend une vue avec des variables
     *
     * @param string $cheminVue ex: 'client/carte'
     * @param array $donnees Variables à extraire dans la vue
     */
    protected function rendreVue(string $cheminVue, array $donnees = [])
    {
        extract($donnees); // transforme ['plats' => ...] en $plats
        require_once __DIR__ . '/../vues/' . $cheminVue . '.php';
    }

    /**
     * Redirige vers une autre URL
     *
     * @param string $url
     */
    protected function rediriger(string $url)
    {
        header('Location: ' . $url);
        exit;
    }

    /**
     * Vérifie si un utilisateur est connecté (exemple simple)
     */
    protected function verifierConnexion()
    {
        if (!isset($_SESSION['utilisateur'])) {
            $this->rediriger('?page=connexion');
        }
    }
}
