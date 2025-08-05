<?php

namespace App\Vues\Client;

use App\Config\Constante;
use App\Config\ConstanteServer;

require_once dirname(__DIR__, 3) . '/vendor/autoload.php';

/* use App\Core\Autoloader;

require_once __DIR__ . '/../../Core/Autoloader.php';
Autoloader::register(); */

use App\Modeles\PlatRepository;

$platRepository = new PlatRepository();
//require_once __DIR__ ."/../connexion/Session.php";
$title = 'Tasty Food - Accueil'; ?>
<!-- 
require_once __DIR__ . '/../models/plats.php';
require_once __DIR__ . '/../../config/config.php';
$platRepository = new PlatRepository();
?> -->

<?php ob_start(); ?>

<?php
$page_selectionne = $_GET['page'] ?? null;
$pages = [
    'accompagnement',
    'dessert',
    'entree',
    'resistance',
    'boisson',
];
?>

<section id="links" class="relative top-2 mt-16">
    <nav>
        <ul class="flex flex-row justify-between w-full">

            <?php
            switch ($page_selectionne) {
                case 'accompagnement':
                    $type = 'Accompagnements';
                    break;
                case 'dessert':
                    $type = 'Desserts';
                    break;
                case 'entree':
                    $type = 'Entrees';
                    break;
                case 'resistance':
                    $type = 'Plat de résistance';
                    break;
                case 'boisson':
                    $type = 'Boissons';
                    break;
                case 'default':
                default:
                    $type = 'Default';
                    break;
            }
            foreach ($pages as $page) {
                $est_actif = ($page == $page_selectionne);
                echo '
                        <li class="rounded-lg cursor-pointer">
        <a href="?page=' . $page . '" class="block px-6 py-3 rounded-lg transition ' .
                    ($est_actif
                        ? 'bg-blue-600 text-white font-semibold shadow'
                        : 'bg-gray-100 text-gray-700 hover:bg-gray-200') .
                    '">'
                    . htmlspecialchars(ucfirst($page)) .
                    '</a>
    </li>
                    ';
            }
            ?>
        </ul>
    </nav>
    <div class="w-8/9 h-[2px] bg-blue-600 rounded-2xl"></div>
</section>

<?php
$page = $_GET['page'] ?? 'default'; // valeur par défaut
?>

<div class="grid grid-cols-1 gap-4 relative top-8 bottom-96 mb-16">
    <section class="mb-16 mt-1">
        <h2 class="text-3xl font-bold text-center mt-[-12] mb-4">
            Des plats faits maison, avec amour, livrés chez vous.
        </h2>

        <div class="carts grid grid-cols-3 gap-12">
            <?php
            switch ($page) {
                case 'accompagnement':
                    $plats = $platRepository->getPlatsByTypeName('Accompagnements');
                    break;
                case 'dessert':
                    $plats = $platRepository->getPlatsByTypeName('Desserts');
                    break;
                case 'entree':
                    $plats = $platRepository->getPlatsByTypeName('Entrees');
                    break;
                case 'resistance':
                    $plats = $platRepository->getPlatsByTypeName('Plat de resistance');
                    break;
                case 'boisson':
                    $plats = $platRepository->getPlatsByTypeName('Boissons');
                    break;
                case 'default':
                    $plats = $platRepository->getPlats();
                    break;
                default:
                    $plats = $platRepository->getPlats();
                    break;
            }
            //var_dump($plats);
            //$plats = $platRepository->getPlats();
            ?>
        </div>

        <div id="carts" class="carts grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php foreach ($plats as $plat): ?>
                <div class="max-w-sm max-h-full pb-2 bg-white rounded-2xl shadow-lg hover:scale-105 transition max-w-screen-sm mx-auto">
                    <img src="<?= htmlspecialchars($plat->getImgPlat()) ?>"
                        alt="<?= 'image du plat ' . htmlspecialchars($plat->getNomPlat()) ?>"
                        class="w-full h-48 object-cover rounded-t-2xl">
                    <div class="p-6 space-y-4 min-h-fit">
                        <p class="text-xl font-bold text-gray-800"><?= htmlspecialchars($plat->getNomPlat()) ?></p>

                        <div class="flex flex-col sm:flex-row items-center justify-between space-y-2 sm:space-y-0">
                            <span class="text-lg font-semibold text-green-600 px-4 py-2">
                                <?= number_format($plat->getPrixPlat(), 2) ?> DH
                            </span>
                            <a href="?page=<?= urlencode($page) ?>&id=<?= $plat->getIdPlat() ?>#plat"
                                class="bg-blue-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition hover:bg-blue-700">
                                See profile
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>



    </section>


    <?php
    $id_cible = isset($_GET['id']) ? $_GET['id'] : null;
    if ($id_cible):
    ?>

        <section class="flex flex-col items-center justify-center mt-4 mb-24">
            <div id="plat" class="bg-gray-300 p-2 rounded-3xl shadow-2xl w-fit max-w-md space-y-6 flex-grow flex flex-col items-center justify-center-safe
        hidden opacity-0 translate-y-2 transition duration-500 hover:scale-105 ease-out">

                <?php $plat = $platRepository->getPlat((int) $id_cible); ?>

                <h2 class="text-center text-xl font-bold text-gray-800">
                    <?= $plat->getNomPlat() ?>
                </h2>

                <img src="<?= $plat->getImgPlat() ?>" alt="<?= $plat->getNomPlat() ?>"
                    class="w-full max-w-[250px] max-h-[200px] object-cover mx-auto rounded-2xl shadow-md">

                <p class="text-lg font-semibold"><?= $plat->getPrixPlat(); ?> DH</p>
                <p class="text-sm text-gray-700"><?= $plat->getDescription() ?></p>

                <a href="#passez-commande" class="bg-blue-600 text-white font-medium py-2 px-4 rounded
            transition hover:duration-700 hover:ease-in-out
            hover:bg-blue-700 hover:scale-125 
            hover:opacity-90 block text-center">
                    Ajouter au Panier
                </a>
            </div>
        </section>

    <?php endif; ?>
</div>


<?php if ($id_cible > 0): ?>
    <script>
        const id = <?= (int) ($id_cible) ?>;
        const show_plat = document.getElementById("plat");
        const carts = document.getElementById("carts");
        if (show_plat) {
            show_plat.classList.remove("hidden");
            carts.classList.remove()
            // Pour déclencher l'animation après l'affichage
            setTimeout(() => {
                show_plat.classList.remove("opacity-0", "translate-y-2");
            }, 10); // petit délai pour permettre l'application initiale
        }
    </script>
<?php endif; ?>


<?php $content = ob_get_clean(); ?>

<?php  
$layout_path = ConstanteServer::base_public() . '/layout.php';
require_once $layout_path;
?>