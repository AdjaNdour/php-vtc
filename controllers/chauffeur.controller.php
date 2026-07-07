<?php
require_once __DIR__ . '/../models/chauffeur.model.php';
require_once __DIR__ . '/../models/vehicule.model.php';
require_once __DIR__ . '/../controllers/course.controller.php';
require_once __DIR__ . '/../utils/view.php';
require_once __DIR__ . '/../views/chauffeur.view.php';



$controllerChauffeur = function(): void {
    global $selectModel, $chauffeurs, $menuChauffeur, $accepterCourse, $demarrerCourse, $terminerCourse, $consulterGainsJournaliers;

    $idChauffeur = $selectModel($chauffeurs, "Sélectionnez un chauffeur");
    if ($idChauffeur === null) {
        return;
    }

    $continuer = true;
    while ($continuer) {
        $choix = $menuChauffeur();
        switch ($choix) {
            case 1:
                $accepterCourse($idChauffeur);
                break;
            case 2:
                $demarrerCourse($idChauffeur);
                break;
            case 3:
                $terminerCourse($idChauffeur);
                break;
            case 4:
                // $consulterGainsJournaliers($idChauffeur);
                break;
            case 0:
                $continuer = false;
                break;
        }
    }
};

$accepterCourse = function(int $idChauffeur): void {
    global $courses, $chauffeurs, $vehicules, $saisie, $changerStatutCourse, $envoyerNotification;

    $coursesDispo = [];
    foreach ($courses as $id => $course) {
        if ($course['statut'] === 'En recherche') {
            $coursesDispo[$id] = $course;
        }
    }

    if (empty($coursesDispo)) {
        echo "Aucune course disponible en recherche pour le moment.\n";
        return;
    }

    echo "\n=== COURSES EN ATTENTE ===\n";
    foreach ($coursesDispo as $id => $course) {
        echo "[ID Course: {$id}] Depart: {$course['depart']} | Destination: {$course['destination']} | Distance: {$course['distanceKm']} km | Prix estime: {$course['prixEstime']} FCFA\n";
    }

    $choix = $saisie("Saisissez l'ID de la course a accepter");
    if (!is_numeric($choix) || !isset($coursesDispo[(int) $choix])) {
        echo "Selection invalide.\n";
        return;
    }

    $idCourse = (int) $choix;
    
    if ($courses[$idCourse]['statut'] !== 'En recherche') {
        echo "Desole, cette course n'est plus disponible (statut: {$courses[$idCourse]['statut']}).\n";
        return;
    }

    $courses[$idCourse]['idChauffeur'] = $idChauffeur;
    $changerStatutCourse($idCourse, 'Chauffeur en route');

    $vehiculeDetails = "Non precise";
    foreach ($vehicules as $vehicule) {
        if ($vehicule['idChauffeur'] === $idChauffeur) {
            $vehiculeDetails = "{$vehicule['marque']} {$vehicule['modele']} ({$vehicule['immatriculation']})";
            break;
        }
    }

    $chauffeur = $chauffeurs[$idChauffeur];

    echo "Vous avez accepte la course ID {$idCourse}.\n";

    $msg = "Le chauffeur {$chauffeur['prenom']} {$chauffeur['nom']} ({$chauffeur['telephone']}) est en route avec le vehicule {$vehiculeDetails}.";
    $envoyerNotification($idCourse, $msg);
    echo "Notification envoyee au passager.\n";
};

$demarrerCourse = function(int $idChauffeur): void {
    global $courses, $saisie, $changerStatutCourse;

    $coursesEnRoute = [];
    foreach ($courses as $id => $course) {
        if ($course['idChauffeur'] === $idChauffeur && $course['statut'] === 'Chauffeur en route') {
            $coursesEnRoute[$id] = $course;
        }
    }

    if (empty($coursesEnRoute)) {
        echo "Aucune course en statut 'Chauffeur en route' pour vous.\n";
        return;
    }

    echo "\n=== VOS COURSES EN ATTENTE DE DEMARRAGE ===\n";
    foreach ($coursesEnRoute as $id => $course) {
        echo "[ID Course: {$id}] Depart: {$course['depart']} | Destination: {$course['destination']} | Prix estime: {$course['prixEstime']} FCFA\n";
    }

    $choix = $saisie("Saisissez l'ID de la course a demarrer");
    if (!is_numeric($choix) || !isset($coursesEnRoute[(int) $choix])) {
        echo "Selection invalide.\n";
        return;
    }

    $idCourse = (int) $choix;
    
    $changerStatutCourse($idCourse, 'En cours');
    echo "La course ID {$idCourse} est maintenant EN COURS. Bonne route !\n";
};

$terminerCourse = function(int $idChauffeur): void {
    global $courses, $saisie, $changerStatutCourse, $envoyerNotification;

    $coursesEnCours = [];
    foreach ($courses as $id => $course) {
        if ($course['idChauffeur'] === $idChauffeur && $course['statut'] === 'En cours') {
            $coursesEnCours[$id] = $course;
        }
    }

    if (empty($coursesEnCours)) {
        echo "Aucune course en cours de trajet pour vous.\n";
        return;
    }

    echo "\n=== VOS COURSES EN TRAJET ===\n";
    foreach ($coursesEnCours as $id => $course) {
        echo "[ID Course: {$id}] Depart: {$course['depart']} | Destination: {$course['destination']} | Prix estime: {$course['prixEstime']} FCFA\n";
    }

    $choix = $saisie("Saisissez l'ID de la course a terminer");
    if (!is_numeric($choix) || !isset($coursesEnCours[(int) $choix])) {
        echo "Selection invalide.\n";
        return;
    }

    $idCourse = (int) $choix;
    
    $montantDefinitif = $courses[$idCourse]['prixEstime'];
    $courses[$idCourse]['montantDefinitif'] = $montantDefinitif;
    
    $changerStatutCourse($idCourse, 'Terminee');
    
    echo "La course ID {$idCourse} est terminee. Le montant definitif est de {$montantDefinitif} FCFA.\n";

    $msgFacture = "Votre course ID {$idCourse} est terminee. Le montant definitif a payer est de {$montantDefinitif} FCFA. Veuillez proceder au paiement.";
    $envoyerNotification($idCourse, $msgFacture);
    echo "Notification de facturation envoyee au passager.\n";
};

// $consulterGainsJournaliers = function(int $idChauffeur): void {
//     global $courses;
    
//     $totalGains = 0;
//     $count = 0;
    
//     foreach ($courses as $id => $course) {
//         if ($course['idChauffeur'] === $idChauffeur && $course['statut'] === 'Payee') {
//             $totalGains += $course['montantDefinitif'] ?? $course['prixEstime'];
//             $count++;
//         }
//     }
//     echo "Total des gains : {$totalGains} FCFA\n";
// };
