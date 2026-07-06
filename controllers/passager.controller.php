<?php
require_once __DIR__ . '/../models/passager.model.php';
require_once __DIR__ . '/../controllers/course.controller.php';
require_once __DIR__ . '/../utils/view.php';
require_once __DIR__ . '/../views/passager.view.php';
require_once __DIR__ . '/../views/paiement.view.php';
require_once __DIR__ . '/../controllers/paiement.controller.php';

$commanderCourse = function(int $idPassager): void {
    global $saisie, $estimerPrix, $ajouterCourse, $envoyerNotification, $courses;

    $depart = $saisie("Saisissez l'adresse de depart");
    while (trim($depart) === "") {
        echo "L'adresse de depart ne peut pas etre vide.\n";
        $depart = $saisie("Saisissez l'adresse de depart");
    }

    $destination = $saisie("Saisissez la destination");
    while (trim($destination) === "") {
        echo "La destination ne peut pas etre vide.\n";
        $destination = $saisie("Saisissez la destination");
    }

    echo "Simulation d'un appel au systeme GPS...\n";
    $distance = round(rand(20, 250) / 10, 1); // 2.0 to 25.0 km
    echo "Distance calculee : {$distance} km\n";

    $prixEstime = $estimerPrix($distance);
    echo "Prix estime pour cette course : {$prixEstime} FCFA\n";

    $confirmation = $saisie("Confirmez-vous la commande ? (oui/non)");
    if (strtolower($confirmation) !== 'oui' && strtolower($confirmation) !== 'o') {
        echo "Commande annulee.\n";
        return;
    }

    $newCourse = [
        'depart' => $depart,
        'destination' => $destination,
        'distanceKm' => $distance,
        'prixEstime' => $prixEstime,
        'montantDefinitif' => null,
        'statut' => 'En recherche',
        'idPassager' => $idPassager,
        'idChauffeur' => null
    ];

    $ajouterCourse($newCourse);
    
    // Get the newly created course ID
    $idCourse = max(array_keys($courses));

    echo "Course enregistree avec succes ! ID de la course : {$idCourse}\n";

    $message = "Une nouvelle course de \"{$depart}\" a \"{$destination}\" est disponible.";
    $envoyerNotification($idCourse, $message);
    echo "Notification envoyee aux chauffeurs a proximite.\n";
};

$consulterHistorique = function(int $idPassager): void {
    global $courses, $chauffeurs;
    
    echo "\n=== HISTORIQUE DES COURSES ===\n";
    $hasCourses = false;
    foreach ($courses as $id => $course) {
        if ($course['idPassager'] === $idPassager) {
            $hasCourses = true;
            $chauffeurNom = "Aucun";
            if ($course['idChauffeur']) {
                $ch = $chauffeurs[$course['idChauffeur']] ?? null;
                if ($ch) {
                    $chauffeurNom = $ch['prenom'] . " " . $ch['nom'];
                }
            }
            $montantText = $course['montantDefinitif'] ? $course['montantDefinitif'] . " FCFA" : "Estime: " . $course['prixEstime'] . " FCFA";
            echo "[Course ID: {$id}] Depart: {$course['depart']} | Destination: {$course['destination']} | Distance: {$course['distanceKm']} km | Prix: {$montantText} | Statut: {$course['statut']} | Chauffeur: {$chauffeurNom}\n";
        }
    }
    if (!$hasCourses) {
        echo "Aucune course trouvee pour ce passager.\n";
    }
};

$controllerPassager = function(): void {
    global $selectModel, $passagers, $menuPassager, $commanderCourse, $consulterHistorique, $payerCourse, $choisirModePaiement, $courses, $saisie;

    $idPassager = $selectModel($passagers, "Sélectionnez un passager");
    if ($idPassager === null) {
        return;
    }

    $continuer = true;
    while ($continuer) {
        $choix = $menuPassager();
        switch ($choix) {
            case 1:
                $commanderCourse($idPassager);
                break;
            case 2:
                $consulterHistorique($idPassager);
                break;
            case 3:
                $coursesTerminees = [];
                foreach ($courses as $id => $course) {
                    if ($course['idPassager'] === $idPassager && $course['statut'] === 'Terminee') {
                        $coursesTerminees[$id] = $course;
                    }
                }
                if (empty($coursesTerminees)) {
                    echo "Aucune course terminee en attente de paiement.\n";
                    break;
                }
                echo "\n=== COURSES EN ATTENTE DE PAIEMENT ===\n";
                foreach ($coursesTerminees as $id => $course) {
                    echo "[ID Course: {$id}] De: {$course['depart']} | A: {$course['destination']} | Montant: {$course['prixEstime']} FCFA\n";
                }
                $idCourse = $saisie("Saisissez l'ID de la course a payer");
                if (!is_numeric($idCourse) || !isset($coursesTerminees[(int) $idCourse])) {
                    echo "Selection invalide.\n";
                    break;
                }
                $mode = $choisirModePaiement();
                $payerCourse((int) $idCourse, $mode);
                break;
            case 0:
                $continuer = false;
                break;
        }
    }
};
