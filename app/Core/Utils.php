<?php
namespace App\Core;
require_once dirname(__FILE__).'Autoloader.php';
use App\Config\Constante;
class Utils
{
    /* public static function redirect(string $path = __DIR__ . '/../../index.php', int $status = 200){
        //http_response_code($status); // Indique au navigateur le code retourne
        header("Location: " . $path, true, $status);
        exit;
    } */

    /**
     * Summary of redirect
     * @param string $path chemin de l'adresse a specifiée a partir du projet
     * @param int $status
     * @return never
     */
    public static function redirect(string $path = Constante::base_url() . '/index.php', int $status = 302): void
    {
        if (!headers_sent()) {
            header("Location: " . $path, true, $status);
            exit;
        } else {
            echo "<script>window.location.href = '$path';</script>";
            exit;
        }
    }

    public static function statusResponse(int $status = 500)
    {
        http_response_code($status);
    }

    public static function showErrors()
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }
}
