<?php
$passagers = [
    1 => ['id' => 1, 'nom' => 'Diop', 'prenom' => 'Fatou', 'telephone' => '771111111']
];

$ajouterPassager = function(array $newPassager): void {
    global $passagers;
    $id = count($passagers) > 0 ? max(array_keys($passagers)) + 1 : 1;
    $newPassager['id'] = $id;
    $passagers[$id] = $newPassager;
};
