<?php
require_once __DIR__ . '/../utils/view.php';

$menuChauffeur = function(): int {
    global $saisie;
    do {
        echo "\n----------- ESPACE CHAUFFEUR -----------\n";
        echo "1. Consulter et accepter une course\n";
        echo "2. Demarrer une course (prise en charge)\n";
        echo "3. Terminer une course (arrivee)\n";
        echo "4. Consulter mes gains journaliers\n";
        echo "0. Retour au menu principal\n";
        $choix = $saisie("Votre choix");
    } while (!is_numeric($choix) || $choix < 0 || $choix > 4);
    return (int) $choix;
};
