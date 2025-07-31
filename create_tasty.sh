#!/bin/bash

# Créer tous les dossiers nécessaires
mkdir -p tastyfood/app/config 
#Le dossier config/ contient des fichiers de configuration partagés par toute l’application.
#Ta session fait partie de la configuration globale (comme une base de données ou un routeur).
mkdir -p tastyfood/app/controleurs/Admin
mkdir -p tastyfood/app/controleurs/Client
mkdir -p tastyfood/app/modeles
mkdir -p tastyfood/app/vues/admin/authentification
mkdir -p tastyfood/app/vues/admin/ingredients
mkdir -p tastyfood/app/vues/admin/plats
mkdir -p tastyfood/app/vues/admin/menus
mkdir -p tastyfood/app/vues/admin/reservations
mkdir -p tastyfood/app/vues/admin/livraisons
mkdir -p tastyfood/app/vues/admin/themes
mkdir -p tastyfood/app/vues/client/authentification
mkdir -p tastyfood/app/vues/client/layouts
mkdir -p tastyfood/app/core
mkdir -p tastyfood/app/core/exceptions
mkdir -p tastyfood/public/assets/css
mkdir -p tastyfood/public/assets/js
mkdir -p tastyfood/public/assets/images
mkdir -p tastyfood/public/admin

# Fichiers de config
touch tastyfood/app/config/configuration.php

#controleurs
#Le contrôleur fait le lien entre le modèle et la vue.
#C’est lui qui reçoit la requête utilisateur (ex: "je veux voir le panier") et décide quoi faire.
#Il récupère les données nécessaires via le modèle, puis les transmet à la vue pour affichage.
#Il ne contient pas de logique métier, mais peut inclure des conditions pour déterminer quelle vue afficher.

# Fichiers controleurs admin
touch tastyfood/app/controleurs/Admin/AuthentificationControleur.php
touch tastyfood/app/controleurs/Admin/TableauDeBordControleur.php
touch tastyfood/app/controleurs/Admin/IngredientControleur.php
touch tastyfood/app/controleurs/Admin/PlatControleur.php
touch tastyfood/app/controleurs/Admin/MenuControleur.php
touch tastyfood/app/controleurs/Admin/ReservationControleur.php
touch tastyfood/app/controleurs/Admin/LivraisonControleur.php
touch tastyfood/app/controleurs/Admin/ThemeControleur.php

# Fichiers controleurs client
touch tastyfood/app/controleurs/Client/AuthentificationControleur.php
touch tastyfood/app/controleurs/Client/AccueilControleur.php
touch tastyfood/app/controleurs/Client/CarteControleur.php
touch tastyfood/app/controleurs/Client/ReservationControleur.php
touch tastyfood/app/controleurs/Client/LivraisonControleur.php
touch tastyfood/app/controleurs/Client/PanierControleur.php

# Fichiers modèles
#Le modèle gère les données, la logique métier, et les interactions avec la base de données.
#Il ne génère pas d’affichage.
#Il est responsable de la récupération, de la validation et de la manipulation des données.
#Il communique directement avec la base de données (via PDO, MySQLi, etc.).
touch tastyfood/app/modeles/Utilisateur.php
touch tastyfood/app/modeles/Ingredient.php
touch tastyfood/app/modeles/Plat.php
touch tastyfood/app/modeles/Menu.php
touch tastyfood/app/modeles/Repas.php
touch tastyfood/app/modeles/Reservation.php
touch tastyfood/app/modeles/Livraison.php
touch tastyfood/app/modeles/Theme.php
touch tastyfood/app/modeles/Panier.php

#vues 
#Les vues sont responsables de l’affichage des données à l’utilisateur.
#Elles reçoivent les données du contrôleur et les présentent à l’utilisateur.
#Elles ne contiennent pas de logique métier, mais peuvent inclure des boucles et des conditions pour afficher les données de manière dynamique.
#Elles sont généralement des fichiers HTML, PHP, ou des templates.
#Elles ne gèrent pas directement les interactions avec la base de données.
#Elles sont utilisées pour afficher les informations à l’utilisateur final, comme les pages web,
#les formulaires, les listes, etc.

# Vues admin
touch tastyfood/app/vues/admin/authentification/connexion.php
touch tastyfood/app/vues/admin/tableau_de_bord.php
touch tastyfood/app/vues/admin/ingredients/liste.php
touch tastyfood/app/vues/admin/ingredients/creer.php
touch tastyfood/app/vues/admin/ingredients/modifier.php
touch tastyfood/app/vues/admin/plats/liste.php
touch tastyfood/app/vues/admin/plats/creer.php
touch tastyfood/app/vues/admin/plats/modifier.php
touch tastyfood/app/vues/admin/menus/liste.php
touch tastyfood/app/vues/admin/menus/creer.php
touch tastyfood/app/vues/admin/menus/modifier.php
touch tastyfood/app/vues/admin/reservations/liste.php
touch tastyfood/app/vues/admin/reservations/details.php
touch tastyfood/app/vues/admin/livraisons/liste.php
touch tastyfood/app/vues/admin/livraisons/details.php
touch tastyfood/app/vues/admin/themes/liste.php
touch tastyfood/app/vues/admin/themes/modifier.php

# Vues client
touch tastyfood/app/vues/client/authentification/connexion.php
touch tastyfood/app/vues/client/authentification/inscription.php
touch tastyfood/app/vues/client/layouts/principal.php
touch tastyfood/app/vues/client/accueil.php
touch tastyfood/app/vues/client/carte.php
touch tastyfood/app/vues/client/reservation.php
touch tastyfood/app/vues/client/livraison.php
touch tastyfood/app/vues/client/panier.php
touch tastyfood/app/vues/client/paiement.php

# Fichiers core
touch tastyfood/app/core/Application.php
touch tastyfood/app/core/Controleur.php
touch tastyfood/app/core/BaseDeDonnees.php
touch tastyfood/app/core/Modele.php

# Public
touch tastyfood/public/admin/index.php
touch tastyfood/public/index.php

# Racine
touch tastyfood/.htaccess
touch tastyfood/index.php

echo "✅ Arborescence tastyfood/ créée avec succès."


