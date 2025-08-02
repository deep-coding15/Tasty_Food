<?php

/* require_once dirname(__DIR__, 4) . '/vendor/autoload.php';
echo dirname(__DIR__, 4) . '/vendor/autoload.php';
 */
//echo dirname(__DIR__, 3) . '/core/Autoloader.php';
require_once dirname(__DIR__, 3) . '/Core/Autoloader.php';

use App\Config\ConstanteServer;
use App\Core\Autoloader;

Autoloader::register();

use App\Modeles\Utilisateurs\UtilisateurRepository;

error_reporting(E_ALL);
ini_set('display_errors', 1);
$title = "Tasty Food - INSCRIPTION";
ob_start();


$errors = [];
$success = '';
/* $utilisateurRepository = new UtilisateurRepository();
$utilisateurs = $utilisateurRepository->getUtilisateurs();

foreach ($utilisateurs as $user) {
        echo "Nom : " . $user['nom'] . "<br>";
        echo "Email : " . $user['email'] . "<br><br>";
    } */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validation simple
    /* if (empty($nom)) {
        $errors[] = "Le nom est requis.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email invalide.";
    } */

    /* if (strlen($password) < 6) {
        $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
    } */

    // Traitement si aucun erreur
    /* if (empty($errors)) {
        // Simuler une inscription (ex: enregistrement en BDD)
        // Ici, on simule simplement un succès
        $success = "Inscription réussie ! Bienvenue, $nom.";
    } */
    try {
        $utilisateurRepository = new UtilisateurRepository();
        $login = $utilisateurRepository->logIn($_POST);
        $utilisateurs = $utilisateurRepository->getUtilisateurs();
    } catch (\App\Core\Exceptions\UtilisateurException $exception) {
        $exception->getTrace();
    }
    //$utilisateurRepository->getUtilisateurs();

    /* foreach ($utilisateurs as $user) {
        echo "Nom : " . $user['nom'] . "<br>";
        echo "Email : " . $user['email'] . "<br><br>";
    } */
}

$title = "Tasty Food - Inscription";
ob_start();
?>

<div class="backdrop-blur-md w-full bg-white/30 p-6 rounded-xl flex items-center justify-center h-[80vh] m-[10vh]
    ">
    <div class="bg-white px-8 py-2 margin-32 rounded shadow-md w-full max-w-md h-[80vh] overflow-scroll">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800"></h2>

        <?php if (!empty($errors)): ?>
            <div class="bg-red-100 text-red-700 p-4 mb-4 rounded">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li>• <?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="bg-green-100 text-green-700 p-4 mb-4 rounded">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <h1 class="text-center text-2xl font-medium text-gray-800 mb-6">SIGN UP</h1>
        
        <form action="" method="POST" class="space-y-2">
            <div>
                <label for="firstname" class="block text-sm font-medium text-gray-700">First Name:</label>
                <input type="text" name="firstname" id="firstname" value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>" class="mt-1 block w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring focus:border-blue-300">
            </div>

            <div>
                <label for="lastname" class="block text-sm font-medium text-gray-700">Last Name:</label>
                <input type="text" name="lastname" id="lastname" value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>" class="mt-1 block w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring focus:border-blue-300">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" class="mt-1 block w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring focus:border-blue-300">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" class="mt-1 block w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring focus:border-blue-300">
            </div>

            <div>
                <label for="telephone" class="block text-sm font-medium text-gray-700">Phone:</label>
                <input type="tel" name="telephone" id="telephone" class="mt-1 block w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring focus:border-blue-300">
            </div>

            <button type="submit" class="bg-blue-600 text-white font-medium py-2 px-6 rounded transition hover:duration-700
                hover:ease-in-out hover:bg-blue-700 hover:scale-110 hover:opacity-90 block w-lg">
                S'inscrire
            </button>
        </form>
    </div>
</div>
<?php ?>
<?php $content = ob_get_clean(); ?>
<?php require_once dirname(__FILE__, 5) . '/public/layout.php'; ?>
<?php  
$layout_path = ConstanteServer::base_public() . '/layout.php';
require_once $layout_path;
?>