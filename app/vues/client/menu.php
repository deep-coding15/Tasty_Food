<?php
namespace App\Vues\Client;
use App\Modeles\PlatRepository;

$title = 'Tasty Food - Accueil'; ?>
<?php
require_once __DIR__ . '/../models/plats.php';
require_once __DIR__ . '/../../config/config.php';
$platRepository = new PlatRepository();
?>

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
    <div class="w-7/9 h-px bg-blue-600"></div>
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
                default:
                    $plats = $platRepository->getPlats();
                    break;
            }
            //$plats = $platRepository->getPlats();
            foreach ($plats as $plat):
                ?>
                <div
                    class="max-w-sm max-h-full pb-8 bg-white rounded-2xl shadow-lg transition hover:scale-105 hover:shadow-xl duration-300">
                    <img src="<?php echo $plat->getImgPlat() ?>" alt="<?= 'image du plat' . $plat->getNomPlat(); ?>"
                        class="w-full h-48 object-cover rounded-t-2xl">
                    <div class="p-6 space-y-4 min-h-fit">
                        <p class="text-xl font-bold text-gray-800"><?= $plat->getNomPlat() ?></p>
                        <p><?= $plat->getPrixPlat() ?> DH</p>
                        <!-- J'ai envie d'utiliser un display:hidden et avec un toggle le rendre visible -->
                        <div class="flex items-center justify-between h-full">
                            <span class="text-lg font-semibold text-green-600 md:flex-1"><?= $plat->getPrixPlat() ?>
                                DH</span>
                            <a href="?page=<?= $page ?>&id=<?= $plat->getIdPlat() ?>#plat"
                                class="bg-blue-600 text-white text-sm font-medium md:flex-1 px-4 py-2 rounded-lg transition hover:bg-blue-700 hover:scale-105">
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
            <div id="plat" class="bg-gray-300 p-8 rounded-3xl shadow-2xl w-fit max-w-md space-y-6 flex-grow flex flex-col items-center justify-center-safe
                    hidden opacity-0 translate-y-2 transition duration-500 hover:scale-105 ease-out">
                <?php $plat = $platRepository->getPlat((int) $id_cible); ?>
                <h2 class="text-center text-xl font-bold text-gray-800"><?= $plat->getNomPlat() ?></h2>
                <img src="<?= $plat->getImgPlat() ?>" alt="" class="w-full h-96 object-cover mx-auto  rounded-t-2xl">
                <p><?= $plat->getPrixPlat(); ?> DH</p>
                <p><?= $plat->getDescription() ?></p>
                <a href="#passez-commande" class="bg-blue-600 text-white font-medium py-2 px-4 rounded
                    transition hover:duration-700 hover:ease-in-out
                    hover:bg-blue-700 hover:scale-125 
                    hover:opacity-90 block justify-center"
                >
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
        if (show_plat) {
            show_plat.classList.remove("hidden");

            // Pour déclencher l'animation après l'affichage
            setTimeout(() => {
                show_plat.classList.remove("opacity-0", "translate-y-2");
            }, 10); // petit délai pour permettre l'application initiale
        }
    </script>
<?php endif; ?>


<?php $content = ob_get_clean(); ?>

<?php require 'layout.php'; ?>