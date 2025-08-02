<?php

use App\Config\SessionManager;
use App\Config\Constante;
use App\Config\ConstanteServer;

require_once ConstanteServer::base_path_app_core() . '/Autoloader.php';

use App\Core\Autoloader;

Autoloader::register();

$_instance = SessionManager::getInstance();
$_sessionManager = $_instance->getSession();

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  /* require_once __DIR__ . '/../../config/config.php';
   *//* ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL); */
  ?>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?= Constante::base_public() . '/assets/css/output.css' ?>">
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

  <title>
    <?= $title ?>
    <?= $baseUrl = Constante::base_url_vues_client() . '/app/vues'; ?>
  </title>
</head>


<body class="flex flex-col space-y-4 scroll-smooth h-full">
  <header class="bg-white shadow-md fixed top-0 inset-x-0 z-50 h-14 w-full">
    <!-- bg-gray-800 text-white py-3 h-fit fixed bottom-0 left-0 w-full shadow-inner border-t border-gray-700 col-span-2 z-100">
 -->
    <!-- max-w-7xl -->
    <div class="mx-auto px-4 py-4 flex items-center justify-between">
      <nav class="space-x-6 hidden md:flex">
        <a href="<?= Constante::base_url_vues_client() ?>/accueil.php" class="text-gray-600 hover:text-blue-600 flex-1">Accueil</a>
        <a href="<?= Constante::base_url_vues_client() ?>/menu.php" class="text-gray-600 hover:text-blue-600 flex-1">Menu</a>
        <a href="<?= Constante::base_url_vues_client() ?>/reservation.php" class="text-gray-600 hover:text-blue-600 flex-1">Reservation</a>
        <a href="<?= Constante::base_url_vues_client() ?>/livraison.php" class="text-gray-600 hover:text-blue-600 flex-1">Livraison</a>
        <a href="<?= Constante::base_url_vues_client() ?>/a_propos.php" class="text-gray-600 hover:text-blue-600 flex-1">A propos</a>
        <a href="<?= Constante::base_url_vues_client() ?>/contact.php" class="text-gray-600 hover:text-blue-600 flex-1">Contact</a>
        <a href="<?= Constante::base_url_vues_client() ?>/authentification/connexion.php" class="text-gray-600 hover:text-blue-600 flex-1 border border-black rounded text-green-500" id="connexion">Connexion</a>
        <a href="<?= Constante::base_url_vues() ?>/app/vues/profil.php" class="text-gray-600 hover:text-blue-600 flex-1">Profil</a>
      </nav>
      <!-- bouton hamburger visible sur mobile (petits écrans)-->
      <button id="menu-toggle" class="md:hidden text-gray-600 focus:outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
    </div>

    <!-- uniquement visible sur mobile -->
    <div id="mobile-menu" class="md:hidden hidden px-4 pb-4 space-y-2">
      <a href="<?= Constante::base_url_vues_client() ?>/accueil.php" class="text-gray-600 hover:text-blue-600 flex-1">Accueil</a>
      <a href="<?= Constante::base_url_vues_client() ?>/menu.php" class="text-gray-600 hover:text-blue-600 flex-1">Menu</a>
      <a href="<?= Constante::base_url_vues_client() ?>/reservation.php" class="text-gray-600 hover:text-blue-600 flex-1">Reservation</a>
      <a href="<?= Constante::base_url_vues_client() ?>/livraison.php" class="text-gray-600 hover:text-blue-600 flex-1">Livraison</a>
      <a href="<?= Constante::base_url_vues_client() ?>/a_propos.php" class="text-gray-600 hover:text-blue-600 flex-1">A propos</a>
      <a href="<?= Constante::base_url_vues_client() ?>/contact.php" class="text-gray-600 hover:text-blue-600 flex-1">Contact</a>
      <a href="<?= Constante::base_url_vues_client() ?>/authentification/connexion.php" class="text-gray-600 hover:text-blue-600 flex-1 border border-black rounded text-green-500" id="connexion">Connexion</a>
      <a href="<?= Constante::base_url_vues() ?>/app/vues/profil.php" class="text-gray-600 hover:text-blue-600 flex-1">Profil</a>
    </div>

  </header>


  <!-- <main class="pb-48 p-8 col-span-2 row-span-1 overflow-auto flex flex-col">
 p/*  $content */ ?>
  </main> -->
  <!--  -->
  <main class="col-span-2 row-span-1 overflow-auto flex flex-col">
    <?=
    $content
    ?>
  </main>


  <footer class="bg-gray-800 text-white py-4 h-auto md:h-[8vh] fixed bottom-0 left-0 w-full shadow-inner border-t border-gray-700 z-50">
    <div class="max-w-6xl mx-auto px-4 flex flex-col md:flex-row items-center justify-between text-sm gap-y-4 h-full">

      <!-- Bloc 1 : Logo -->
      <div class="w-full md:w-1/3 text-center md:text-left font-semibold">
        Tasty Food
      </div>

      <!-- Bloc 2 : Copyright -->
      <div class="w-full md:w-1/3 text-center text-gray-400">
        © 2025 Merveille Tsafack. Tous droits réservés.
      </div>

      <!-- Bloc 3 : Liens -->
      <div class="w-full md:w-1/3 text-center md:text-right space-x-6">
        <a href="<?= Constante::base_url_vues_client() ?>/accueil.php" class="hover:text-gray-300 transition">Accueil</a>
        <a href="<?= Constante::base_url_vues_client() ?>/a_propos.php" class="hover:text-gray-300 transition">À propos</a>
        <a href="<?= Constante::base_url_vues_client() ?>/contact.php" class="hover:text-gray-300 transition">Contact</a>
        <a href="<?= Constante::base_url_vues_client() ?>/faq.php" class="hover:text-gray-300 transition">FAQ</a>
      </div>

    </div>
  </footer>

  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    // Toggle le menu mobile
    const toggleButton = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');

    toggleButton.addEventListener('click', () => {
      mobileMenu.classList.toggle('hidden');
    });
  </script>
</body>



</html>