<?php
namespace App\Config;
class Constante extends SuperConstante{
    
    //private static string $WEB_PAGE_URL = '/localhost';
    
    /**
     * Summary of get_web_page_url
     * @return string /localhost
     */
    /* public static function get_web_page_url(){
        return self::$WEB_PAGE_URL;
    } */
    public static function base_url(): string{
        
        return  SuperConstante::getBaseApp();
    }

    /**
     * Summary of base_public
     * @return string /public
     */
    public static function base_public(): string{
        return  SuperConstante::getBasePublic();
    }

    /**
     * Summary of base_url_vues_admin
     * @return string /php/tastyfood/app/vues/admin
     */
    public static function base_url_vues_admin(): string{
        return   SuperConstante::getBaseApp() .  SuperConstante::getBaseVues() . SuperConstante::getVuesAdmin();
    }

    /**
     * Summary of 
     * @return string /php/tastyfood/app/vues/client
     */
    public static function base_url_vues_client(): string{
        return SuperConstante::getBaseApp() . 
            SuperConstante::getBaseVues() . SuperConstante::getVuesClient();
    }

    /**
     * Summary of base_url_vues
     * @return string '/php/tastyfood/app/vues'
     */
    public static function base_url_vues(): string{
        return  SuperConstante::getBaseApp();
    }

    /**
     * Summary of base_url_img_profil
     * @return string /php/tastyfood/app/data/Profile/Images
     */
    public static function base_url_img_profil(): string{
        return  SuperConstante::getBaseImgProfil();
    }

    /**
     * Summary of base_url_public
     * @return string  /php/tastyfood/public
     */
    public static function base_url_public(): string  {
        return  SuperConstante::getBasePublic();
    } 
}