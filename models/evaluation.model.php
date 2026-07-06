<?php
$evaluations = [
    1 => [
        'id' => 1,
        'note' => 5,
        'commentaire' => 'Trajet rapide et agreable',
        'idPassager' => 1,
        'idChauffeur' => 1,
        'idCourse' => 1
    ]
];

$ajouterEvaluation = function(array $newEvaluation): void {
    global $evaluations;
    $id = count($evaluations) > 0 ? max(array_keys($evaluations)) + 1 : 1;
    $newEvaluation['id'] = $id;
    $evaluations[$id] = $newEvaluation;
};
