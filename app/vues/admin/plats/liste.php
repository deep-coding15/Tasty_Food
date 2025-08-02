<?php 
namespace App\Vues\Admin\Plats;
use App\Modeles\PlatRepository;
use App\Config\Constante;
use App\Config\ConstanteServer;
use App\Core\Autoloader;
require_once dirname(__DIR__, 3) . '/Core/Autoloader.php';
Autoloader::register();

$title = 'Tasty Food - Table des Plats'; 

//include __DIR__ . "/../include/init.php";
/* global $_session;
$message = $_session->get('MESSAGE') ?? null;
$_session->remove('MESSAGE'); //on l'affiche une seule fois
 */?>

<?php 
    
    //var_dump($_utilisateur);
    //var_dump( $_session->get('utilisateur'));
    
    //if (isset($_utilisateur['role']) && $_utilisateur['role'] === 'administrateur') :

        $platRepository = new PlatRepository();
?>
    <?php ob_start(); ?>
    
    <?php /* SecureSession::getMessage($message); */ ?>

    <section class="h-[80%] flex">


        <!-- Sidebar (Nav) -->
        <section id="nav-links" class="bg-gray-800 text-white  w-fit fixed top-[1vh] bottom-[8vh] left-0 h-fit  z-10">
            <div class="flex flex-col items-center mb-2">
                <!-- <img src="" alt="Logo" class="w-8 h-8"> -->
                <p class="text-xl font-bold">Tasty Food</p>
                <p class="bg-yellow-400 text-black"><?=/* $_session->get('utilisateur')['role'] */ $i = 0;?></p>
            </div>
            <nav>
                <ul class="space-y-2">
                    <li><a href="<?=Constante::base_url() . '/src/templates/menu.php'?>" class="hover:text-yellow-400">Accueil</a></li>
                    <li><a href="#" class="hover:text-yellow-400">Ingrédients</a></li>
                    <li><a href="#" class="hover:text-yellow-400">Plats</a></li>
                    <li><a href="#" class="hover:text-yellow-400">Repas</a></li>
                    <li><a href="#" class="hover:text-yellow-400">Réservation</a></li>
                    <li><a href="#" class="hover:text-yellow-400">Livraison</a></li>
                    <li><a href="#" class="hover:text-yellow-400">Personnel</a></li>
                    <li><a href="#" class="hover:text-yellow-400">Réglages</a></li>
                    <li><a href="#" class="hover:text-yellow-400">Thèmes et images</a></li>
                    <li><a href="#" class="hover:text-yellow-400">Déconnexion</a></li>
                </ul>
            </nav>
        </section>


        
        <!-- Main Content -->
        <div id="content" class="w-[100%] mx-auto   relative mt-[5%] mb-[10%] pt-16 ml-[10%] -z-5">
            <!-- Table des plats -->
            <section class=" -mt-8 ml-8 flex-1">

                <form class="flex items-center justify-between w-full px-6 py-4" method="get">
                    <!-- Bouton Ajouter un plat -->
                    <a href="<?= Constante::base_url_vues_admin() . '/plats/ajouter_plat.php' ?>"
                        class="p-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                        Ajouter un plat !
                    </a>

                    <!-- Barre de recherche -->
                    <div class="relative flex w-full max-w-md ml-4">
                        <input
                            type="search"
                            name="search"
                            placeholder="Rechercher..."
                            class="w-full pl-10 pr-4 py-2 rounded-l-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white text-gray-700" />
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <!-- Icône de recherche -->
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                            </svg>
                        </div>
                        <button
                            type="submit"
                            class="px-4 py-2 bg-blue-500 text-white rounded-r-lg hover:bg-blue-600 transition-colors duration-200">
                            Rechercher
                        </button>
                    </div>
                </form>

                <table class="min-w-full bg-white border border-gray-300 rounded-2xl shadow-lg overflow-auto">
                    <thead class="bg-blue-500 text-white">
                        <tr>
                            <td class="px-6 py-3">Image</td>
                            <td class="px-6 py-3">Nom</td>
                            <td class="px-6 py-3">Type de Menu</td>
                            <td class="px-6 py-3">Prix du plat</td>
                            <td colspan="3" class="px-6 py-3 text-center">Actions</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        define('NB_PLATS_PAR_PAGE', 5);

                        $nb_total_plats = $platRepository->getNombrePlats();
                        $nb_pages = $platRepository->getNombrePages($nb_total_plats, NB_PLATS_PAR_PAGE);
                        /* echo $nb_pages;
                    echo $nb_total_plats; */
                        $current_page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0
                            ? (int) $_GET['page']
                            : 1;
                        //echo 'current: ' . $current_page;
                        $plats = $platRepository->getPlatsParPage(NB_PLATS_PAR_PAGE, $current_page * NB_PLATS_PAR_PAGE - NB_PLATS_PAR_PAGE);



                        if (isset($_GET['search']) && trim($_GET['search']) != null) :
                            $plats = $platRepository->searhPlatByNom($_GET['search']);
                            //echo "<script>console.log(" . json_encode($plats) . ");</script>";
                            $nb_total_plats = $platRepository->getNombrePlatsArray($plats);
                            $nb_pages = $platRepository->getNombrePages($nb_total_plats, NB_PLATS_PAR_PAGE);
                            unset($_GET['search']);
                        endif;

                        //$sousTableau = array_slice($plats, $offset, $limit);
                        //echo '<pre>' . var_dump($plats) . '</pre>';

                        $nb = 0;
                        foreach ($plats as $index => $plat) :

                            //for ($nb=NB_PLATS_PAR_PAGE * ($current_page - 1); $nb < (NB_PLATS_PAR_PAGE * $current_page); $nb++) :
                            //$plat = $plats[$nb];
                            if ($nb < NB_PLATS_PAR_PAGE * $current_page) : ?>
                                <tr class="border-t border-gray-300">
                                    <td class="px-6 py-4">
                                        <img src="<?= $plat->getImgPlat() ?>" alt="Image de <?= $plat->getNomPlat() ?>"
                                            class="w-24 h-24 object-cover rounded-t-2xl">
                                    </td>
                                    <td class="px-6 py-4"><?= $plat->getNomPlat() ?></td>
                                    <td class="px-6 py-4"><?= $plat->getTypePlat() ?></td>
                                    <td class="px-6 py-4"><?= $plat->getPrixPlat() ?> DH</td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="?id=<?= $plat->getIdPlat() ?>/#plat" class="text-blue-500 hover:text-blue-700">Détails</a>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="<?= Constante::base_url_vues_admin() . '/plats/modifier_plat.php' ?>?id=<?= $plat->getIdPlat() ?>" class="text-yellow-500 hover:text-yellow-700">Éditer</a>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="<?= Constante::base_url_vues_admin() . '/plats/supprimer_plat.php' ?>?id=<?= $plat->getIdPlat() ?>" class="text-red-500 hover:text-red-700">Supprimer</a>
                                    </td>
                                </tr>
                                <?php $nb++; ?>
                            <?php else : ?>
                                $nb = 0;
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="flex justify-end text-2xl">



                    <?php
                    // Sécurité & fallback
                    $current_page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0
                        ? (int) $_GET['page']
                        : 1;

                    // Pages précédente et suivante
                    $page_prec = max(1, $current_page - 1);
                    $page_suiv = min($nb_pages, $current_page + 1);
                    ?>


                </div>
                <!-- <div class="relative top-8 flex justify-between mx-auto right-[2%]">
                <?php if ($current_page > 1): ?>
                    <a href="?page=<?= $page_prec ?>" class="btn  bg-blue-500 p-2 my-2 -mt-8 ml-8 rounded">Précédente !</a>
                <?php else: ?>
                    <a href="?page=<?= $page_prec ?>" class="btn hidden opacity-0 bg-blue-500 p-2 my-2 -mt-8 ml-8 rounded">Précédente !</a>
                <?php endif; ?>
                <?php if ($current_page < $nb_pages): ?>
                    <a href="?page=<?= $page_suiv ?>" class="btn  bg-blue-500 p-2 my-2 -mt-8 ml-8 rounded">Suivante !</a>
                <?php else: ?>
                    <a href="?page=<?= $page_suiv ?>" class="btn hidden opacity-0 bg-blue-500 p-2 my-2 -mt-8 ml-8 rounded">Suivante !</a>
                <?php endif; ?>
                <?php if (isset($_GET['page']) && !empty($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $nb_pages) {
                    $current_page = (int)strip_tags($_GET['page']);
                } else {
                    $current_page = 1;
                }
                ?>
            </div> -->
                <div class="flex justify-center items-center gap-2 mt-10">

                    <div class="flex gap-1">
                        <!-- Précédente -->
                        <a href="?page=<?= $page_prec ?>"
                            class="px-4 py-2 rounded bg-blue-500 text-white <?= $current_page == 1 ? 'pointer-events-none opacity-50' : 'hover:bg-blue-600' ?>">
                            Précédente
                        </a>
                        <?php // Fenêtre de pages visibles autour de la page actuelle
                        $start = max(2, $current_page - 2);
                        $end = min($nb_pages - 1, $current_page + 2);
                        ?>

                        <!-- Page 1 toujours visible -->
                        <a href="?page=1"
                            class="px-3 py-1 rounded border <?= $current_page == 1 ? 'bg-blue-600 text-white' : 'bg-white text-blue-600 hover:bg-blue-100' ?>">
                            1
                        </a>

                        <!-- Ellipse avant -->
                        <?php if ($start > 2): ?>
                            <span class="px-2 text-gray-500">...</span>
                        <?php endif; ?>

                        <!-- Pages autour de la page actuelle -->
                        <?php for ($i = $start; $i <= $end; $i++): ?>
                            <a href="?page=<?= $i ?>"
                                class="px-3 py-1 rounded border <?= $i == $current_page ? 'bg-blue-600 text-white' : 'bg-white text-blue-600 hover:bg-blue-100' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>

                        <!-- Ellipse après -->
                        <?php if ($end < $nb_pages - 1): ?>
                            <span class="px-2 text-gray-500">...</span>
                        <?php endif; ?>

                        <!-- Dernière page toujours visible -->
                        <?php if ($nb_pages > 1): ?>
                            <a href="?page=<?= $nb_pages ?>"
                                class="px-3 py-1 rounded border <?= $current_page == $nb_pages ? 'bg-blue-600 text-white' : 'bg-white text-blue-600 hover:bg-blue-100' ?>">
                                <?= $nb_pages ?>
                            </a>
                        <?php endif; ?>


                        <!-- Suivante -->
                        <a href="?page=<?= $page_suiv ?>"
                            class="px-4 py-2 rounded bg-blue-500 text-white <?= $current_page == $nb_pages ? 'pointer-events-none opacity-50' : 'hover:bg-blue-600' ?>">
                            Suivante
                        </a>
                    </div>

                </div>

            </section>
        </div>
    </section>
    <?php $id_cible = isset($_GET['id']) ? $_GET['id'] : null; ?>
    <?php if ($id_cible) : ?>
        <section id="detail" class="flex flex-col items-center justify-center mb-24">
            <div id="plat" class="bg-gray-300 rounded-3xl p-8 shadow-2xl w-fit mb-24 max-w-md space-y-6 flex-grow flex flex-col items-center justify-center-safe
            hidden opacity-0 translate-y-2 transition duration-500 hover:scale-105 ease-out">
                <?php $plat = $platRepository->getPlat((int) $id_cible); ?>
                <h2 class="text-center text-xl font-bold text-gray-800"><?= $plat->getNomPlat() ?></h2>
                <img src="<?= $plat->getImgPlat() ?>" alt="" class="w-full h-96 object-cover mx-auto  rounded-t-2xl">
                <p><?= $plat->getPrixPlat(); ?> DH</p>
                <p><?= $plat->getDescription() ?></p>
                <a href="<?= Constante::base_url_vues_admin() . '/plats/modifier_plat.php' ?>?id=<?= $plat->getIdPlat() ?>" class="bg-blue-600 text-white font-medium py-2 px-4 rounded
            transition hover:duration-700 hover:ease-in-out
            hover:bg-blue-700 hover:scale-125 
            hover:opacity-90 block justify-center">
                    Editez le plat
                </a>
            </div>
        </section>
    <?php endif; ?>
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
        <script>
            setTimeout(() => {
                const flash = document.getElementById('flash-message');
                if (flash) {
                    flash.style.transition = 'opacity 0.5s ease-out';
                    flash.style.opacity = '0';
                    setTimeout(() => flash.remove(), 500); // retire du DOM après fondu
                }
            }, 5000); // 5000 ms = 5 secondes
        </script>
    <?php endif; ?>
    <?php $content = ob_get_clean(); ?>

<?php  
    $layout_path = ConstanteServer::base_public() . '/layout.php';
    require_once $layout_path;
?>