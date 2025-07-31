<?php

namespace App\Config;

use App\Controleurs\MainControlleur;

/**
 * Ce tableau associatif representera l'ensemble des routes de l'application
 * 'controller' : le nom de classe de contrôleur à instancier ainsi que son namespace
 * 'method' : la méthode à appeler depuis l'objet ainsi créé
 * @var array
 */
const ROUTES = [
    '/' => [
        'controller' => MainControlleur::class,
        'method' => 'home',
    ],
    '/contact' => [
        'controller' => MainControlleur::class,
        'method' => 'contact',
    ],
    'carte' => [
        'controller' => 'CarteControleur',
        'method' => 'voirCarte'
    ],
    'contact' => [
        'controller' => 'ContactControleur',
        'method' => 'formulaire'
    ],
    'admin/plats' => [
        'controller' => 'Admin\PlatControleur',
        'method' => 'index'
    ],

];


class Routes
{
    private $path;
    private $callable;
    private $matches = [];
    private $params = [];

    public function __construct($path, $callable)
    {
        $this->path = trim($path, '/'); //on retire les / inutiles
        $this->callable = $callable;
    }

    /**
     * Permettre de capturer l'url avec les paramètres
     * get('/posts/:slug-:id') par exemple get('/posts/article-super-42')
     * @param mixed $url
     * @return bool
     */
    public function match($url)
    {
        $url = trim($url, '/');
        $path = preg_replace('#:([\w]+)#', '([^/])+', $this->path);
        $regex = '#^$path$#i';
        if (!preg_match($regex, $url, $matches)) {
            return false;
        }
        array_shift($matches);
        $this->matches = $matches; // On sauvegarde les paramètre dans l'instance pour plus tard
        return true;
    }

    public function call()
    {
        return call_user_func_array($this->callable, $this->matches);
    }

    public function get($path, $callable)
    {
        $route = new self($path, $callable);
        $this->routes["GET"][] = $route;
        return $route;
    }

    public static function getRoutes(): array
    {
        return ROUTES;
    }
}
