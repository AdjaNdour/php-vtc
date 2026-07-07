<?php
require_once __DIR__ . '/../models/paiement.model.php';
require_once __DIR__ . '/../models/course.model.php';
require_once __DIR__ . '/../models/passager.model.php';
require_once __DIR__ . '/../models/chauffeur.model.php';

$payerCourse = function(int $idCourse, string $modePaiement): void {
    global $courses, $ajouterPaiement, $changerStatutCourse, $genererRecu, $evaluerChauffeur;

    if (!isset($courses[$idCourse])) {
        echo "Course introuvable.\n";
        return;
    }

    $course = $courses[$idCourse];
    if ($course['statut'] !== 'Terminee') {
        echo "La course n'est pas terminee. Statut actuel: {$course['statut']}.\n";
        return;
    }

    $montant = $course['montantDefinitif'] ?? $course['prixEstime'];

    echo "\nSimulation de prelevement bancaire...\n";
    echo "Traitement de la transaction de {$montant} FCFA via {$modePaiement}...\n";
    echo "Paiement valide avec succes par la banque.\n";

    $ajouterPaiement([
        'montant' => $montant,
        'datePaiement' => date('d-m-Y'),
        'mode' => $modePaiement,
        'statut' => 'Valide',
        'idCourse' => $idCourse
    ]);

    $changerStatutCourse($idCourse, 'Payee');

    $genererRecu($idCourse);

    // Call $evaluerChauffeur if it is declared
    if (isset($evaluerChauffeur)) {
        $evaluerChauffeur($idCourse);
    }
};

$genererRecu = function(int $idCourse): void {
    global $courses, $paiements, $passagers, $chauffeurs;
    
    $paiement = null;
    foreach ($paiements as $p) {
        if ($p['idCourse'] === $idCourse) {
            $paiement = $p;
            break;
        }
    }
    
    if (!$paiement) {
        echo "Aucun paiement trouve pour cette course.\n";
        return;
    }
    
    $course = $courses[$idCourse];
    $passager = $passagers[$course['idPassager']] ?? ['nom' => 'Inconnu', 'prenom' => ''];
    $chauffeur = $course['idChauffeur'] ? ($chauffeurs[$course['idChauffeur']] ?? ['nom' => 'Inconnu', 'prenom' => '']) : ['nom' => 'Non assigne', 'prenom' => ''];

    echo "\n=========================================\n";
    echo "            RECU DE PAIEMENT VTC         \n";
    echo "=========================================\n";
    echo "Recu N : " . $paiement['id'] . "\n";
    echo "Date : " . $paiement['datePaiement'] . "\n";
    echo "Passager : " . $passager['prenom'] . " " . $passager['nom'] . "\n";
    echo "Chauffeur : " . $chauffeur['prenom'] . " " . $chauffeur['nom'] . "\n";
    echo "Trajet : de \"" . $course['depart'] . "\" a \"" . $course['destination'] . "\"\n";
    echo "Distance : " . $course['distanceKm'] . " km\n";
    echo "Mode de paiement : " . $paiement['mode'] . "\n";
    echo "Montant paye : " . $paiement['montant'] . " FCFA\n";
    echo "Statut : " . $paiement['statut'] . "\n";
    echo "=========================================\n\n";
};

