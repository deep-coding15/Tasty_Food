<?php

namespace App\Lib\Router;

use App\Config\Route;
use App\Core\Exceptions\RouterException;

require dirname(__DIR__, 2) . '/config/routes.php';

class Router
{
    private $routes; //ensemble des routes de l'application definis dans config/routes.php dans la constante ROUTES
    private $availablePaths; //ensemble des chemins contenus dans ces routes : ex: /, about, mentions-legales
    private $requestedPath; //chemin demande par le client, on le recupere par la valeur du parametre $_GET['path']

    /**
     * Le contructeur a deux roles ici :
     * - Celui d'initialiser les attributs
     * - Celui de declencher l'analyse des routes
     */
    public function __construct()
    {
        $this->routes = Route::getRoutes();
        $this->availablePaths = array_keys(Route::getRoutes());
        $this->requestedPath = isset($_GET['path']) ? $_GET['path'] : '/';
        $this->parseRoutes(); //methode dediée pour l'analyse des routes
    }

    public function run(){
        if(!isset($this->routes[$_SERVER['REQUEST_METHOD']])){
            throw new RouterException('REQUEST_METHOD does not exist');
        }
        foreach($this->routes[$_SERVER['REQUEST_METHOD']] as $route){
            if($route->match($this->requestedPath)) {
                return $route->call();
            }
        }
        throw new RouterException('No matching routes.');
    }

    //http://monsite.com/index.php?page=carte
    private function parseRoutes()
    {
        if (isset($this->requestedPath)) {
            $segments = explode('/', trim($this->requestedPath, '/'));
            if(in_array($segments[0], $this->availablePaths)){
                $route = $this->routes[$this->requestedPath];
            }
        }
    }

    private function error($message)
    {
        http_response_code(404);
        echo "<h1>404 Not Found</h1><p>$message</p>";
        exit;
    }
}
