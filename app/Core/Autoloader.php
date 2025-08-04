<?php

namespace App\Core;

/**
 * Cette classe permet de charger automatiquement les classes PHP de l’application en respectant la structure des namespaces.
 */
class Autoloader
{
    /**
     * Enregistre la méthode d’autoload auprès de PHP.
     * À appeler une seule fois au début de l’application (souvent dans le fichier d’entrée, par exemple index.php).
     * exemple d'utilisation : <?php \App\Core\Autoloader::register();
     * @return void
     */
    public static function register()
    {
        spl_autoload_register([self::class, 'autoload']);
    }

    /**
     * Méthode appelée automatiquement par PHP lorsqu’une classe non chargée est utilisée.
     * Elle convertit le namespace de la classe en chemin de fichier, puis inclut ce fichier si trouvé.

     * $class : Nom complet de la classe à charger (avec namespace).
     * Le chemin de base est fixé à app (modifie-le si besoin).
     * Le namespace principal App est retiré pour correspondre à l’arborescence des fichiers.
     * Exemple de fonctionnement :

     * Classe : App\Modeles\Utilisateurs\Role
     * Chemin cherché : Role.php
     * Résumé d’utilisation :

     * 1. Appelle Autoloader::register() au démarrage.
     * 2. Les classes du namespace App seront automatiquement chargées lors de leur première utilisation, si leur fichier existe dans app.
     * @param mixed $class
     * @return void
     */
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
