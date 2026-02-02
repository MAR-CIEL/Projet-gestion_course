<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *"); // Autorise le JS à lire ce fichier

// Génère des données réalistes mais aléatoires
echo json_encode([
    "vitesse" => rand(10, 50),
    "tension_batterie" => round(6.0 + (rand(0, 120) / 100), 2),
    "consommation" => round(rand(5, 25) / 10, 2),
    "obstacle" => rand(0, 1),
    "sens" => (rand(0, 1) > 0.5) ? "AV" : "AR"
]);
?>