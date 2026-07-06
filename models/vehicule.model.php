<?php
$vehicules = [
    1 => ['id' => 1, 'marque' => 'Toyota', 'modele' => 'Corolla', 'immatriculation' => 'DK-1234-AA', 'idChauffeur' => 1]
];

$ajouterVehicule = function(array $newVehicule): void {
    global $vehicules;
    $id = count($vehicules) > 0 ? max(array_keys($vehicules)) + 1 : 1;
    $newVehicule['id'] = $id;
    $vehicules[$id] = $newVehicule;
};
