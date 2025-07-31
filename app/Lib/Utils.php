<?php

namespace App\Lib;

use App\Config\Constante;

use App\Core\Autoloader;
require_once dirname(__DIR__, 1) . '/Core/Autoloader.php';
Autoloader::register();

class Utils
{
    /* public static function redirect(string $path = __DIR__ . '/../../index.php', int $status = 200){
        //http_response_code($status); // Indique au navigateur le code retourne
        header("Location: " . $path, true, $status);
        exit;
    } */

    /**
     * Summary of redirect
     * @param string $path chemin de l'adresse a specifiée a partir du projet /tastyfood
     * @param int $status
     * @return never
     */
    public static function redirect(string $path =  '/index.php', int $status = 302): void
    {
        $pathUrl = $path;
        if (!headers_sent()) {
            header("Location: " . $pathUrl, true, $status);
            exit;
        } else {
            echo "<script>window.location.href = " . json_encode($pathUrl) . ";</script>";
        
/*             echo "<script>window.location.href = '$path';</script>";
 */            exit;
        }
    }

    public static function statusResponse(int $status = 500)
    {
        http_response_code($status);
    }

    public static function afficherErreurPHP()
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }
}
