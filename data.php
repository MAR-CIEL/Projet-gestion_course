<?php
header('Content-Type: application/json');

// Chemin vers ton exécutable de chronométrage
$chemin_exe = __DIR__ . "/../CoVACIELCourse_Chronometrage/x64/Debug/CoVACIELCourse_Chronometrage.exe";

// On ajoute 2>&1 pour capturer les erreurs éventuelles du programme
$command = '"' . $chemin_exe . '" 2>&1';
$output = shell_exec($command);

// On nettoie la sortie (trim) et on l'envoie au format JSON
echo json_encode([
    "chrono" => $output ? trim($output) : "0min:0s:0ms",
    "vitesse" => 0, // Inutilisé mais présent pour éviter des erreurs dans ton script.js
    "tension_batterie" => 0,
    "error" => ($output === null)
]);