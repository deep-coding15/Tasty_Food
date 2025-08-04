<?php
namespace App\Config;
class ConstanteServer{
    private static ?string $BASE_PATH = null;
    private static string $VUES_ADMIN = "/admin";
    private static string $VUES_CLIENT = "/client";
    private static string $BASE_IMG_PROFIL = '/app/data/Profile/Images';
    private static string $BASE_PUBLIC = '/public';
    
    private static string $BASE_VUES = '/app/vues/';

    /**
     * Summary of base_path_public
     * string c:\\xampp\\htdocs\\php\\tastyfood
     */ 
    public static function getBasePath(): string{
        if(self::$BASE_PATH === null)
            self::set_base_path();
        return self::$BASE_PATH;
    }

    /**
     * Summary of base_path_public
     * string c:\\xampp\\htdocs\\php\\tastyfood
     */ 
    private static function set_base_path(){
        self::$BASE_PATH = dirname(__DIR__, 2);
    }
    /**
     * '/php/tastyfood'
     * @return string '/php/tastyfood'
     */
    public static function base_path(): string{
        return self::getBasePath() . SuperConstante::getBaseApp();
    }

    /**
     * Summary of base_public
     * @return string /public
     */
    public static function base_public(): string{
        return self::getBasePath() . SuperConstante::getBasePublic();
    }

    /**
     * Summary of base_url_vues_admin
     * @return string /php/tastyfood/app/vues/admin
     */
    public static function base_path_vues_admin(): string{
        return self::getBasePath() . SuperConstante::getBaseVues()  . SuperConstante::getVuesAdmin();
    }

    /**
     * Summary of base_url_vues_client
     * @return string /php/tastyfood/app/vues/client
     */
    public static function base_url_vues_client(): string{
        return self::getBasePath() . SuperConstante::getBaseVues() . SuperConstante::getVuesClient();
    }

    /**
     * Summary of base_url_vues
     * @return string '/php/tastyfood/app/vues'
     */
    public static function base_url_vues(): string{
        return self::getBasePath() . SuperConstante::getBaseVues();
    }

    /**
     * Summary of base_url_img_profil
     * @return string /php/tastyfood/app/data/Profile/Images
     */
    public static function base_url_img_profil(): string{
        return self::getBasePath() . SuperConstante::getBaseImgProfil();
    }

    /**
     * Summary of base_path_public
     * @return string c:\\xampp\\htdocs\\php\\tastyfood\\public
     */
    public static function base_path_public(): string  {
        return self::getBasePath()  . SuperConstante::getBasePublic();
    }

    public static function base_path_app_core(){
        return self::getBasePath() . SuperConstante::getBaseAppCore();
    }
}