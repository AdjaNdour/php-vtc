<?php
$courses = [
    1 => [
        'id' => 1,
        'depart' => 'Plateau, Dakar',
        'destination' => 'Almadies, Dakar',
        'distanceKm' => 12,
        'prixEstime' => 3500,
        'montantDefinitif' => null,
        'statut' => 'En recherche',
        'idPassager' => 1,
        'idChauffeur' => null
    ]
];

$ajouterCourse = function(array $newCourse): void {
    global $courses;
    $id = count($courses) > 0 ? max(array_keys($courses)) + 1 : 1;
    $newCourse['id'] = $id;
    $courses[$id] = $newCourse;
};
