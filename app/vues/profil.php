<?php
namespace App\Vues\Admin\Plats;

use App\Core\Autoloader;
require_once dirname(__DIR__, 1) . '/Core/Autoloader.php';
Autoloader::register();

use App\Modeles\Utilisateurs\UtilisateurRepository;
use App\Config\Constante;
use App\Config\ConstanteServer;
use App\Config\SessionManager;
use App\Lib\Utils;

    $title = "Tasty Food - PROFIL UTILISATEUR";
    $utilisateurRepository = new UtilisateurRepository();
    
    $_utilisateur = ($utilisateurRepository->getUtilisateurBy_X('id_utilisateur', $_SESSION['ID']))[0];
    
?>
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white shadow-md rounded-lg p-8 w-full max-w-md text-center">
        <!-- Image de profil -->
        <div class="flex justify-center mb-4">
            <img class="w-24 h-24 rounded-full object-cover border-4 border-indigo-500" src="<?= Constante::base_url() . Constante::base_url_img_profil().'/default_profile_photo.jpg' ?>" alt="Avatar">
        </div>
        <!-- Nom -->
        <h2 class="text-2xl font-bold text-gray-800 mb-1">Nom : <?= htmlspecialchars($_utilisateur['nom']) ?></h2>
        <!-- Email -->
        <p class="text-gray-500 mb-4">Email : <?= htmlspecialchars($_utilisateur['email']);?></p>
        <!-- Rôle -->
        <span class="inline-block px-3 py-1 text-sm bg-indigo-100 text-indigo-700 rounded-full mb-6">
            Role : <?= htmlspecialchars($_utilisateur['role']);?>
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
$layout_path = ConstanteServer::base_public() . '/layout.php';
require_once $layout_path;
?>
<?php /* endif; */ ?>