<?php
require_once __DIR__ . '/../models/evaluation.model.php';
require_once __DIR__ . '/../utils/view.php';
require_once __DIR__ . '/../utils/validator.php';

$evaluerChauffeur = function(int $idCourse): void {
    global $courses, $ajouterEvaluation, $saisie;

    if (!isset($courses[$idCourse])) {
        echo "Course introuvable.\n";
        return;
    }

    $course = $courses[$idCourse];
    $idPassager = $course['idPassager'];
    $idChauffeur = $course['idChauffeur'];

    if (!$idChauffeur) {
        echo "Aucun chauffeur n'est associe a cette course.\n";
        return;
    }

    echo "\n=== EVALUATION DU CHAUFFEUR ===\n";
    
    $note = "";
    while (!is_numeric($note) || $note < 1 || $note > 5) {
        $note = $saisie("Saisissez une note pour le chauffeur (de 1 a 5)");
        if (!is_numeric($note) || $note < 1 || $note > 5) {
            echo "Note invalide. Veuillez entrer un nombre entre 1 et 5.\n";
        }
    }

    $commentaire = $saisie("Saisissez un commentaire (facultatif)");

    $ajouterEvaluation([
        'note' => (int) $note,
        'commentaire' => $commentaire,
        'idPassager' => $idPassager,
        'idChauffeur' => $idChauffeur,
        'idCourse' => $idCourse
    ]);

    echo "Merci pour votre evaluation !\n";
};

