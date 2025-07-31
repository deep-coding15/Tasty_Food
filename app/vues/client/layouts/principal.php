<?php
include __DIR__ . '/../include/init.php';

$_session->set('ROLE', "client");

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  require_once __DIR__ . '/../../config/config.php';
  /* ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL); */
  ?>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?= BASE_URL . '/src/style.css' ?>">
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

  <title>
    <?= $title ?>
    <?= $baseUrl = BASE_URL .'/src/templates'; ?>
  </title>
</head>


<body class="flex flex-col space-y-4 scroll-smooth h-full">
  <header class="bg-white shadow-md fixed top-0 inset-x-0 z-50 h-14 w-full">
    <!-- bg-gray-800 text-white py-3 h-fit fixed bottom-0 left-0 w-full shadow-inner border-t border-gray-700 col-span-2 z-100">
 -->
    <!-- max-w-7xl -->
    <div class="mx-auto px-4 py-4 flex items-center justify-between">
      <nav class="space-x-6 hidden md:flex">
        <a href="index.php?page=default" class="text-gray-600 hover:text-blue-600 flex-1">Accueil</a>
        <a href=<?=BASE_URL."/src/templates/menu.php"?> class="text-gray-600 hover:text-blue-600 flex-1">Menu</a>
        <a href=<?=BASE_URL."/src/templates/reservation.php"?> class="text-gray-600 hover:text-blue-600 flex-1">Réservation</a>
        <a href=<?=BASE_URL."/src/templates/livraison.html"?> class="text-gray-600 hover:text-blue-600 flex-1">Livraison</a>
        <a href=<?=BASE_URL."/src/templates/a_propos.php"?> class="text-gray-600 hover:text-blue-600 flex-1">A propos</a>
        <a href=<?=BASE_URL."/src/templates/contact.php"?> class="text-gray-600 hover:text-blue-600 flex-1">Contact</a>
        <a href=<?=BASE_URL."/src/templates/connexion.php"?> id="connexion" class="text-gray-600 hover:text-blue-600 flex-1" >Connexion</a>
        <a href=<?=BASE_URL."/src/templates/profil.php"?> id="connexion" class="block text-gray-700 hover:text-blue-600 flex-1">Profil</a>
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
      <a href="index.php?page=default" class="block text-gray-700 hover:text-blue-600">Accueil</a>
      <a href=<?=BASE_URL."/src/templates/menu.php"?> class="block text-gray-700 hover:text-blue-600">Menu</a>
      <a href=<?=BASE_URL."/src/templates/reservation.php"?> class="block text-gray-700 hover:text-blue-600">Réservation</a>
      <a href=<?=BASE_URL."/src/templates/livraison.php"?> class="block text-gray-700 hover:text-blue-600">Livraison</a>
      <a href=<?=BASE_URL."/src/templates/a_propos.php"?> class="text-gray-600 hover:text-blue-600 flex-1">A propos</a>
      <a href=<?=BASE_URL."/src/templates/contact.php"?> class="block text-gray-700 hover:text-blue-600">Contact</a>
      <a id="connexion" <?=BASE_URL."/src/templates/connexion.php"?> class="block text-gray-700 hover:text-blue-600">Connexion</a>
      <a <?=BASE_URL."/src/templates/profile.php"?> class="block text-gray-700 hover:text-blue-600">Profil</a>
    </div>

  </header>

  <!-- <main class="pb-48 p-8 col-span-2 row-span-1 overflow-auto flex flex-col">
 p/*  $content */ ?>
  </main> -->
   <!--  -->
  <main class="col-span-2 row-span-1 overflow-auto flex flex-col">
    <?=$content?> 
  </main>
  
  <!-- <footer class="bg-gray-800 text-white py-3 h-fit fixed bottom-0 left-0
     w-full "> -->
  <footer class="bg-gray-800 text-white -py-2 h-[12vh] fixed bottom-0 left-0 w-full shadow-inner border-t border-gray-700 col-span-2 z-100
      ">
    <div class="max-w-6xl mx-auto px-4 py-auto relative top-1/2 -translate-y-1/2 transform">
      <div class="flex flex-row md:flex-row justify-between items-center space-y-4 md:space-y-0">

        <!-- Logo ou titre -->
        <div class="text-lg font-semibold">Tasty Food</div>

        <!-- Copyright -->
        <div class="flex items-center justify-center text-center mt-6 text-sm text-gray-400 my-auto">
          © 2025 Merveille Tsafack. Tous droits réservés.
        </div>

        <!-- Liens -->
        <div class="flex space-x-6 text-sm">
          <a href="index.php" class="hover:text-gray-300 transition">Accueil</a>
          <a href=<?=BASE_URL."/src/templates/a_propos.php"?> class="hover:text-gray-300 transition">À propos</a>
          <a href=<?=BASE_URL."/src/templates/contact.php"?> class="hover:text-gray-300 transition">Contact</a>
          <a href=<?=BASE_URL."/src/templates/faq.php"?> class="hover:text-gray-300 transition">FAQ</a>
        </div>

        
        
      
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