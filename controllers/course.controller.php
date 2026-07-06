<?php
require_once __DIR__ . '/../models/course.model.php';
require_once __DIR__ . '/../models/notification.model.php';
require_once __DIR__ . '/../models/chauffeur.model.php';

$estimerPrix = function(float $distanceKm): float {
    // 300 FCFA per KM with a base fare of 500 FCFA
    return 500 + ($distanceKm * 300);
};

$rechercherChauffeurProche = function(): ?int {
    global $chauffeurs;
    foreach ($chauffeurs as $id => $chauffeur) {
        if (isset($chauffeur['statut']) && $chauffeur['statut'] === 'Disponible') {
            return $id;
        }
    }
    return null;
};

$changerStatutCourse = function(int $idCourse, string $statut): void {
    global $courses;
    if (isset($courses[$idCourse])) {
        $courses[$idCourse]['statut'] = $statut;
    }
};

$envoyerNotification = function(int $idCourse, string $message): void {
    global $ajouterNotification;
    $ajouterNotification([
        'message' => $message,
        'idCourse' => $idCourse
    ]);
};
