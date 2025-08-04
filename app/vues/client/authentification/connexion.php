<?php

use App\Lib\Utils;
require_once dirname(__DIR__, 3) . '/Core/Autoloader.php';

use App\Core\Autoloader;
use App\Modeles\Utilisateurs\UtilisateurRepository;
use App\Config\Constante;
use App\Config\ConstanteServer;
use App\Config\SessionManager;
use App\Core\Exceptions\UtilisateurException;

Autoloader::register();
echo '<pre>';
$_sessionManager = SessionManager::getInstance();
//$_sessionManager->getSession()->start();
//$_sessionManager->getSession()->destroy();
//$_sessionManager->regenerateSession();
echo '</pre>';
//$_session = $_sessionManager->getSession();
//$_utilisateur = $_session->get('utilisateur');
    
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        //echo 'je suis la';
        //session_start();
        $utilisateurRepository = new UtilisateurRepository();
        //echo 'je suis la';
        $resultBool = $utilisateurRepository->signUp($_POST);
        /* echo 'je suis la';
        echo 'je suis la';
        var_dump($resultBool); */
        //echo 'signup';
        //$utilisateurs = $utilisateurRepository->getUtilisateurs();
        /* $_session = $_sessionManager->getSession();
        $_utilisateur = $_session->get('utilisateur');
         *//* echo 'session connexion';
        var_dump($_SESSION);
         *//* echo 'user in connexion 35';
        var_dump($_utilisateur);
         */
        (new Utils())->redirect('/app/vues/client/menu.php');
    } catch (UtilisateurException $exception) {
        $exception->getTrace();
    }
    //echo 'je suis la';
}

$title = "Tasty Food - CONNEXION";
ob_start();
?>
<section class="w-4/6 mx-auto signup">
    <?php /* echo 'session Manager : <pre>'; var_dump($_sessionManager); echo '</pre>'; */?>
    <?php /*  echo 'session : <pre>'; var_dump($_session); echo '</pre>'; */?>
    <?php /* echo 'utilisateur : <pre>'; var_dump($_utilisateur); echo '</pre>'; */?>
    
    
    <div class="flex flex-col items-center justify-center m-12 w-full mb-24">
        <h1 class="text-center text-2xl font-medium text-gray-800 mb-6">SIGN UP</h1>
        <form action="" method="POST"
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