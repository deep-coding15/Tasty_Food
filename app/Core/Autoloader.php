<?php

namespace App\Core;

class Autoloader
{
    public static function register()
    {
        spl_autoload_register([self::class, 'autoload']);
    }

    public static function autoload($class)
    {
        // Racine des classes (modifie 'app/' si besoin)
        $baseDir = dirname(__DIR__, 2) . '/app/';

        // Nettoyage du namespace principal (App\)
        $class = str_replace('App\\', '', $class);

        // Conversion du namespace en chemin de fichier
        $path = $baseDir . str_replace('\\', '/', $class) . '.php';

        // Debug facultatif :
        // echo "Chargement : $path<br>";

        if (file_exists($path)) {
            require_once $path;
        } else {
            // Pour le debug, tu peux afficher un message ou loguer l'erreur
            // echo "Fichier introuvable : $path<br>";
        }
    }
}
