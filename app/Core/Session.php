<?php 
class Session{
    
public function start(){
        if (session_status() === PHP_SESSION_NONE) {
            session_start([
                // Empêche l'accès au cookie de session via JavaScript (protège contre les attaques XSS)
                'cookie_httponly' => true,

                // Refuse toute session avec un identifiant invalide ou deviné (protège contre les attaques de session fixation)
                'use_strict_mode' => true,

                // Envoie le cookie de session uniquement via HTTPS si le site est en HTTPS (protège contre l'interception du cookie)
                'cookie_secure' => isset($_SERVER['HTTPS']),
            ]);
        }
    }
}