<?php
$paiements = [
    1 => [
        'id' => 1,
        'montant' => 3500,
        'datePaiement' => '05-07-2026',
        'mode' => 'Carte bancaire',
        'statut' => 'Valide',
        'idCourse' => 1
    ]
];

$ajouterPaiement = function(array $newPaiement): void {
    global $paiements;
    $id = count($paiements) > 0 ? max(array_keys($paiements)) + 1 : 1;
    $newPaiement['id'] = $id;
    $paiements[$id] = $newPaiement;
};
