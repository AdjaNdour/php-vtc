<?php

$required = function(array $donnees, array $champs): array {
    $errors = [];

    foreach ($champs as $champ) {
        if (!isset($donnees[$champ]) || trim((string) $donnees[$champ]) === '') {
            $errors[] = "Le champ {$champ} est obligatoire.";
        }
    }

    return $errors;
};

$unique = function(array $tableau, string $champ, $valeur): bool {
    foreach ($tableau as $ligne) {
        if (isset($ligne[$champ]) && $ligne[$champ] == $valeur) {
            return false;
        }
    }

    return true;
};

$existeValue = function(array $tableau, string $champ, $valeur): bool {
    foreach ($tableau as $ligne) {
        if (isset($ligne[$champ]) && $ligne[$champ] == $valeur) {
            return true;
        }
    }

    return false;
};

$errorExist = function(array $errors): bool {
    return count($errors) > 0;
};
