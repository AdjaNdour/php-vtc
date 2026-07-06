<?php
$notifications = [
    1 => [
        'id' => 1,
        'message' => 'Un chauffeur a ete trouve pour votre course',
        'idCourse' => 1
    ]
];

$ajouterNotification = function(array $newNotification): void {
    global $notifications;
    $id = count($notifications) > 0 ? max(array_keys($notifications)) + 1 : 1;
    $newNotification['id'] = $id;
    $notifications[$id] = $newNotification;
};
