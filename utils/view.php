<?php

$saisie = function(string $label): string {
    echo $label . " : ";
    $valeur = fgets(STDIN);
    return trim((string) $valeur);
};

$showError = function(array $errors): void {
    foreach ($errors as $error) {
        echo "Erreur : " . $error . "\n";
    }
};

$selectModel = function(array $tableau, string $label = "Elements disponibles"): ?int {
    global $saisie;

    if (empty($tableau)) {
        echo "Aucun element disponible." . "\n";
        return null;
    }

    echo $label . " :" . "\n";

    foreach ($tableau as $index => $ligne) {
        $formatted = array_map(function($val) {
            if (is_bool($val)) {
                return $val ? 'Oui' : 'Non';
            }
            if (is_null($val)) {
                return 'Aucun';
            }
            return (string) $val;
        }, $ligne);
        $texte = implode(' | ', $formatted);
        echo "[{$index}] {$texte}" . "\n";
    }

    $choix = $saisie("Choisissez un index");

    if (!is_numeric($choix) || !isset($tableau[(int) $choix])) {
        echo "Selection invalide." . "\n";
        return null;
    }

    return (int) $choix;
};
