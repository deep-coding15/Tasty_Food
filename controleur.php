<?php
use App\Modeles\PlatRepository;

function homepage() {
    $posts = getplats();

    require('templates/homepage.php');
}