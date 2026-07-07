<?php

require_once __DIR__ . '/controllers/passager.controller.php';
require_once __DIR__ . '/controllers/chauffeur.controller.php';
require_once __DIR__ . '/controllers/course.controller.php';

$continuer = true;

while ($continuer) {
    echo "\n==================================================\n";
    echo "=== SYSTEME DE RESERVATION VTC (vtc-reservation) ===\n";
    echo "==================================================\n";
    echo "1. Espace Passager\n";
    echo "2. Espace Chauffeur\n";
    echo "3. Voir toutes les courses\n";
    echo "4. Voir toutes les notifications\n";
    echo "0. Quitter\n";
    
    global $saisie;
    $choix = $saisie("Votre choix");

    switch ($choix) {
        case '1':
            global $controllerPassager;
            $controllerPassager();
            break;
        case '2':
            global $controllerChauffeur;
            $controllerChauffeur();
            break;
        case '3':
            global $controllerCourse;
            $controllerCourse();
            break;
        case '4':
            global $consulterNotifications;
            $consulterNotifications();
            break;
        case '0':
            $continuer = false;
            echo "Au revoir !\n";
            break;
        default:
            echo "Choix invalide.\n";
    }
}
