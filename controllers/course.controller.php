<?php
require_once __DIR__ . '/../models/course.model.php';
require_once __DIR__ . '/../models/notification.model.php';
require_once __DIR__ . '/../models/chauffeur.model.php';
require_once __DIR__ . '/../models/passager.model.php';
require_once __DIR__ . '/../views/course.view.php';
require_once __DIR__ . '/../utils/view.php';

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

$controllerCourse = function(): void {
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
};

$consulterNotifications = function(): void {
    global $notifications;
    if (empty($notifications)) {
        echo "Aucune notification.\n";
    } else {
        echo "\n=== TOUTES LES NOTIFICATIONS ===\n";
        foreach ($notifications as $id => $notif) {
            echo "[Notification ID: {$id}] Course ID: {$notif['idCourse']} - Message: \"{$notif['message']}\"\n";
        }
    }
};
