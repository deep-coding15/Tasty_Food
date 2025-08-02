<?php
require_once dirname(__DIR__, 3) . '/Core/Autoloader.php';

use App\Core\Autoloader;
use App\Modeles\Utilisateurs\UtilisateurRepository;
use App\Config\Constante;
use App\Config\ConstanteServer;

Autoloader::register();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $utilisateurRepository = new UtilisateurRepository();
        $utilisateurRepository->signUp($_POST);
        //echo 'signup';
        //$utilisateurs = $utilisateurRepository->getUtilisateurs();
    } catch (\App\Core\Exceptions\UtilisateurException $exception) {
        $exception->getTrace();
    }
}

$title = "Tasty Food - CONNEXION";
ob_start();
?>
<section class="w-4/6 mx-auto signup">

    <div class="flex flex-col items-center justify-center m-12 w-full mb-24">
        <h1 class="text-center text-2xl font-medium text-gray-800 mb-6">SIGN UP</h1>
        <form action="" method="post"
            class="bg-gray-300 p-8 rounded-3xl shadow-2xl w-full max-w-md space-y-4 transition duration-500 ease-out hover:scale-105
      flex flex-col justify-center">

            <div class="flex flex-col space-y-8">
                <label for="email" class="block text-center w-full">Email :</label>
                <input type="email" name="email" id="email"
                    class="bg-gray-100 text-black rounded block w-full px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                    placeholder="Entrer votre email..." />
            </div>

            <div class="flex flex-col space-y-8">
                <label for="password" class="block text-center w-full">Password:</label>
                <input type="password" name="password" id="password" required
                    class="bg-gray-100 text-black rounded block w-full px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                    placeholder="Entrer votre password..." />
            </div>
            <button type="submit"
                class="bg-blue-600 text-white font-medium py-2 px-6 rounded transition hover:duration-700
            hover:ease-in-out hover:bg-blue-700 hover:scale-110 hover:opacity-90 block w-lg">
                ENVOYER
            </button>
        </form>
    </div>
</section>

<?php $content = ob_get_clean(); ?>

<?php  
$layout_path = ConstanteServer::base_public() . '/layout.php';
require_once $layout_path;
?>