<?php
$chauffeurs = [
    1 => ['id' => 1, 'nom' => 'Sow', 'prenom' => 'Ibrahima', 'telephone' => '781111111', 'statut' => 'Disponible', 'latitude' => 14.716, 'longitude' => -17.467]
];

$ajouterChauffeur = function(array $newChauffeur): void {
    global $chauffeurs;
    $id = count($chauffeurs) > 0 ? max(array_keys($chauffeurs)) + 1 : 1;
    $newChauffeur['id'] = $id;
    $chauffeurs[$id] = $newChauffeur;
};
