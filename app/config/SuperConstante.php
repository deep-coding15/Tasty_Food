<?php

namespace App\Config;

class SuperConstante
{
    protected static string $BASE_APP = '/php/tastyfood';
    protected static ?string $BASE_PATH = null;
    protected static string $VUES_ADMIN = "/admin";
    protected static string $VUES_CLIENT = "/client";
    protected static string $BASE_IMG_PROFIL = '/app/data/Profile/Images';
    protected static string $BASE_PUBLIC = '/public';

    protected static string $BASE_VUES = '/app/vues';
    protected static string $BASE_APP_CORE = '/app/Core';

    public const DB_HOST = 'localhost';
    public const DB_NAME = 'restaurant_tasty_food';
    public const DB_USER = 'root';
    public const DB_PASSWORD = '';

    /**
     * Summary of getBaseUrl
     * @return string '/php/tastyfood
     */
    public static function getBaseApp(): string
    {
        return self::$BASE_APP;
    }

    
    /**
     * Summary of getVuesAdmin
     * @return string '/admin'
     */
    public static function getVuesAdmin(): string
    {
        return self::$VUES_ADMIN;
    }

    /**
     * Summary of getVuesClient
     * @return string '/client'
     */
    public static function getVuesClient(): string
    {
        return self::$VUES_CLIENT;
    }

    /**
     * Summary of getBaseImgProfil
     * @return string '/app/data/Profile/Images'
     */
    public static function getBaseImgProfil(): string
    {
        return self::$BASE_IMG_PROFIL;
    }

    /**
     * Summary of getBasePublic
     * @return string '/public'
     */
    public static function getBasePublic(): string
    {
        return self::$BASE_PUBLIC;
    }

    /**
     * Summary of getBaseVues
     * @return string '/app/vues/'
     */
    public static function getBaseVues(): string
    {
        return self::$BASE_VUES;
    }

    public static function getBaseAppCore(){
        return self::$BASE_APP_CORE;
    }
}
