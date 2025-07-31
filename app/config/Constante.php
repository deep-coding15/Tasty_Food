<?php
namespace App\Config;
class Constante{
    private static string $BASE_URL = '/php/tastyfood';
    private static ?string $BASE_PATH = null;
    private static string $VUES_ADMIN = "/app/vues/admin";
    private static string $VUES_CLIENT = "/app/vues/client";
    private static string $BASE_IMG_PROFIL = '/app/data/Profile/Images';
    private static string $BASE_PUBLIC = '/public';
    
    /**
     * '/php/tastyfood'
     * @return string '/php/tastyfood'
     */
    public static function base_url(): string{
        return self::$BASE_URL;
    }

    /**
     * Summary of base_path
     * @return string __DIR__ . '/..'
     */
    public static function base_path(): string{
        if(self::$BASE_PATH === null)
            self::set_base_path();
        return self::$BASE_PATH;
    }

    public static function set_base_path(){
        self::$BASE_PATH = dirname(__DIR__, 2);
    }

    /**
     * Summary of base_url_vues_admin
     * @return string /php/tastyfood/app/vues/admin
     */
    public static function base_url_vues_admin(): string{
        return self::$BASE_URL . self::$VUES_ADMIN;
    }

    /**
     * Summary of base_url_vues_client
     * @return string /php/tastyfood/app/vues/client
     */
    public static function base_url_vues_client(): string{
        return self::$BASE_URL . self::$VUES_CLIENT;
    }

    /**
     * Summary of base_url_img_profil
     * @return string /php/tastyfood/app/data/Profile/Images
     */
    public static function base_url_img_profil(): string{
        return self::base_url() . self::$BASE_IMG_PROFIL;
    }

    /**
     * Summary of base_url_public
     * @return string  /php/tastyfood/public
     */
    public static function base_url_public(): string  {
        return self::base_url() . self::$BASE_PUBLIC;
    }
    
    /**
     * Summary of base_path_public
     * @return string c:\\xampp\\htdocs\\php\\tastyfood\\public
     */
    public static function base_path_public(): string  {
        return self::base_path() . self::$BASE_PUBLIC;
    }
}
    /* define('BASE_URL', '/php/tastyfood');
    define('BASE_PATH', __DIR__ . '/..');
     *///define('BASE_IMG_PROFIL', BASE_URL . '/data/Profile/Images/');