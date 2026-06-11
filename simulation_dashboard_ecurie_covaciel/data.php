<?php
/**
 * @file data.php
 * @brief Passerelle API pour le flux de chronométrage.
 * @details Lit le fichier course.txt mis à jour par le simulateur et le transmet en JSON.
 */
header('Content-Type: application/json');

$fichier_txt = __DIR__ . "/course.txt";

if (file_exists($fichier_txt)) {
    $content = trim(file_get_contents($fichier_txt));
    echo json_encode([
        "chrono" => $content ? $content : "00:00:00|1|1-2-3",
        "success" => true
    ]);
} else {
    echo json_encode([
        "chrono" => "00:00:00|1|1-2-3", // Valeur de secours pour éviter le blocage IHM
        "success" => false
    ]);
}
?>