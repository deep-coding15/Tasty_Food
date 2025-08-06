<?php

namespace App\Vues\Admin\Menus;

use App\Config\ConstanteServer;
use App\Core\Autoloader;
use App\Modeles\MenuRepository;

require_once dirname(__DIR__, 3) . '/Core/Autoloader.php';
Autoloader::register();

$title = 'Tasty Food - Table des menus';
?>
<?php
$menuRepository = new MenuRepository();
$menus = $menuRepository->getMenus();

ob_start();
?>

<script src="https://cdn.tailwindcss.com"></script>

<section class="h-[80%] flex">
    <!-- Main Content -->
    <div id="content" class="w-[100%] mx-auto relative mb-[10%] -z-5">
        <!-- Colonnes des menus -->
        <? //todo: ajouter l'image du menu comme backgrouns derriere chaque carte de menu
        ?>
        <section class="flex items-center justify-center min-h-screen py-10">
            <div class="bg-gray-800 rounded-xl shadow-lg p-6 w-full max-w-4xl">
                <!-- Title -->
                <div class="text-center mb-6">
                    <h1 class="text-4xl font-bold tracking-wide">FOOD MENU</h1>
                    <p class="text-orange-400 text-lg mt-1">Tasty Food Restaurant</p>
                </div>

                <!-- Menu Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <!-- Main Course -->
                    <?php foreach ($menus as $key => $menu) :
                        //echo $menu->getIdMenu();
                        $platResult = $menuRepository->getPlatsByMenuId($menu->getIdMenu()); 
                        $plats = $platResult[0];?>
                        <div>
                            <h2 class="bg-orange-500 text-white px-4 py-2 text-xl font-semibold rounded-t-lg"><?php echo $menu->getNomMenu() ?></h2>
                            <ul class="bg-gray-700 p-4 rounded-b-lg space-y-2 text-sm">
                                <?php foreach ($plats as $plat) : ?>
                                    <li class="flex justify-between">
                                        <span><?= $plat->getNomPlat() ?></span>
                                        <span><?= $plat->getPrixPlat() ?> DH</span>
                                    </li>
                                <?php endforeach; ?>
                                <li class="flex justify-between bg-amber-400"><span>Prix Total : </span><span><?= $platResult[1] ?> DH</span></li>                           
                            </ul>
                        </div>
                        <?php endforeach; ?>
                        
                        

                        <!-- Footer -->
                        <div class="flex flex-col md:flex-row justify-between items-center mt-8 border-t border-gray-600 pt-4 text-sm text-gray-300">
                            <div class="flex items-center gap-2 mb-2 md:mb-0">
                                <span class="text-orange-400">📞</span>
                                <span>123-456-7890</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-orange-400">📍</span>
                                <span>123 Anywhere St., Any City</span>
                            </div>
                        </div>
                </div>
        </section>
    </div>
</section>
<?php $content = ob_get_clean();
$layout_path = ConstanteServer::base_public() . '/admin/layout.php';
require_once $layout_path;
?>