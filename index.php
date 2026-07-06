<?php

require_once __DIR__ . '/controllers/passager.controller.php';
require_once __DIR__ . '/controllers/chauffeur.controller.php';
require_once __DIR__ . '/controllers/course.controller.php';
require_once __DIR__ . '/controllers/paiement.controller.php';
require_once __DIR__ . '/controllers/evaluation.controller.php';
require_once __DIR__ . '/utils/view.php';
require_once __DIR__ . '/views/course.view.php';

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
            global $courses, $passagers, $chauffeurs, $afficherDetailCourse;
            if (empty($courses)) {
                echo "Aucune course enregistree.\n";
            } else {
                echo "\n=== TOUTES LES COURSES ===\n";
                foreach ($courses as $id => $course) {
                    $passager = $passagers[$course['idPassager']] ?? null;
                    $chauffeur = $course['idChauffeur'] ? ($chauffeurs[$course['idChauffeur']] ?? null) : null;
                    $afficherDetailCourse($course, $passager, $chauffeur);
                }
            }
            break;
        case '4':
            global $notifications;
            if (empty($notifications)) {
                echo "Aucune notification.\n";
            } else {
                echo "\n=== TOUTES LES NOTIFICATIONS ===\n";
                foreach ($notifications as $id => $notif) {
                    echo "[Notification ID: {$id}] Course ID: {$notif['idCourse']} - Message: \"{$notif['message']}\"\n";
                }
            }
            break;
        case '0':
            $continuer = false;
            echo "Au revoir !\n";
            break;
        default:
            echo "Choix invalide.\n";
    }
}
