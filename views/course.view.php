<?php
require_once __DIR__ . '/../utils/view.php';

$afficherDetailCourse = function(array $course, ?array $passager, ?array $chauffeur): void {
    $pNom = $passager ? "{$passager['prenom']} {$passager['nom']}" : "Inconnu";
    $cNom = $chauffeur ? "{$chauffeur['prenom']} {$chauffeur['nom']}" : "Non assigne";
    echo "\n--- Course ID: {$course['id']} ---\n";
    echo "Depart: {$course['depart']}\n";
    echo "Destination: {$course['destination']}\n";
    echo "Distance: {$course['distanceKm']} km\n";
    echo "Prix estime: {$course['prixEstime']} FCFA\n";
    echo "Montant definitif: " . ($course['montantDefinitif'] ?? 'Non defini') . " FCFA\n";
    echo "Statut: {$course['statut']}\n";
    echo "Passager: {$pNom}\n";
    echo "Chauffeur: {$cNom}\n";
};
