<?php
header('Content-Type: application/json');

// On définit le chemin absolu vers le fichier
// __DIR__ est le dossier où se trouve data.php
$fichier_txt = __DIR__ . "/course.txt";

if (file_exists($fichier_txt)) {
    $content = trim(file_get_contents($fichier_txt));
    echo json_encode([
        "chrono" => $content ? $content : "00:00:00|1|1-2-3",
        "success" => true
    ]);
} else {
    echo json_encode([
        "chrono" => "00:00:00|0|0-0-0",
        "success" => false,
        "error" => "Fichier introuvable à : " . $fichier_txt
    ]);
}
exit;