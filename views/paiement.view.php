<?php
require_once __DIR__ . '/../utils/view.php';

$choisirModePaiement = function(): string {
    global $saisie;
    echo "\n=== CHOIX DU MODE DE PAIEMENT ===\n";
    echo "1. Carte bancaire\n";
    echo "2. Mobile Money (Wave / Orange Money)\n";
    echo "3. Especes\n";
    
    do {
        $choix = $saisie("Choisissez un mode (1-3)");
    } while ($choix !== '1' && $choix !== '2' && $choix !== '3');
    
    switch ($choix) {
        case '1':
            return 'Carte bancaire';
        case '2':
            return 'Mobile Money';
        case '3':
            return 'Especes';
    }
    return 'Especes';
};
