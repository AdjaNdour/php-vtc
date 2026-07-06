<?php
require_once __DIR__ . '/../utils/view.php';

$menuPassager = function(): int {
    global $saisie;
    do {
        echo "\n----------- ESPACE PASSAGER -----------\n";
        echo "1. Commander une course\n";
        echo "2. Consulter mon historique de courses\n";
        echo "3. Payer une course terminee\n";
        echo "0. Retour au menu principal\n";
        $choix = $saisie("Votre choix");
    } while (!is_numeric($choix) || $choix < 0 || $choix > 3);
    return (int) $choix;
};
