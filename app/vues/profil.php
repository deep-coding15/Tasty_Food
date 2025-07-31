<?php
namespace App\Vues\Admin\Plats;

use App\Core\Autoloader;
require_once dirname(__DIR__, 1) . '/Core/Autoloader.php';
Autoloader::register();

use App\Modeles\Utilisateurs\UtilisateurRepository;
use App\Config\Constante;
use App\Lib\Utils;

    $title = "Tasty Food - PROFIL UTILISATEUR";
    $utilisateurRepository = new UtilisateurRepository();
    //$utilisateur = $utilisateurRepository->
    global $_session;
    /* if(!$_session->has('utilisateur')) :
/*         (new Utils())->redirect(__DIR__ .'/../../erreur403.php');
 *    endif;
    if($_session->has('utilisateur')) :
         */
    ob_start();
?>
<div class="min-h-screen flex flex-col items-center justify-center max-w-screen-sm">
    <h1 class="text-center text-xl font-bold text-gray-800">Profil Utilisateur</h1>

    
    <br>
    <div class="bg-white shadow-md rounded-lg p-8 w-full max-w-md text-center">
        <!-- Image de profil -->
        <div class="flex justify-center mb-4">
            <img class="w-24 h-24 rounded-full object-cover border-4 border-indigo-500" src="<?= Constante::base_url_img_profil().'default_profile_photo.jpg' /* . $_session->get('utilisateur')['img_profil'] */?>" alt="Avatar">
        </div>

        <!-- Nom -->
        <h2 class="text-2xl font-bold text-gray-800 mb-1"><?= /* $_session->get('utilisateur')['prenom'], $_session->get('utilisateur')['nom']  */ $i = 0;?></h2>

        <!-- Email -->
        <p class="text-gray-500 mb-4"><?= /* $_session->get('utilisateur')['email'] */ $i = 0;?></p>

        <!-- Rôle -->
        <span class="inline-block px-3 py-1 text-sm bg-indigo-100 text-indigo-700 rounded-full mb-6">
            <?= /* $_session->get('utilisateur')['role'] */ $i = 0;?>
        </span>

        <!-- Bouton modifier -->
        <div>
            <a href="modifier-profil.php" class="inline-block bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 transition">
                Modifier le profil
            </a>
        </div>
    </div>
</div>
<?php $content = ob_get_clean() ?>

<?php  
$layout_path = Constante::base_path_public() . '/layout.php';
require_once $layout_path;
?>
<?php /* endif; */ ?>