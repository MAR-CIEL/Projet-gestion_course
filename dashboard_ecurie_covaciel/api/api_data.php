<?php
/**
 * @file api_data.php
 * @brief API REST pour l'extraction des données SQL de télémétrie.
 * @details Se connecte à la base de données (VM ou WAMP central) et retourne 
 * la ligne la plus récente de la table télémétrie au format JSON.
 * @author Candidat 4 - Télémétrie & UX
 */

header('Content-Type: application/json');

/** @brief Paramètres de connexion réseau (Par défaut sur la VM Ubuntu de l'arbitre) */
$db_host = "172.17.50.142"; 
$db_user = "user_telemetrie";
$db_pass = "Azerty123#";
$db_name = "covaciel_gestion";

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connexion SGBD impossible"]));
}

/** @section Query Extraction optimisée par tri décroissant de l'index de clé primaire */
$sql = "SELECT vitesse, tension_batterie, consommation, obstacle, direction, acceleration, distance_parcourue 
        FROM telemetrie 
        ORDER BY id_mesure DESC LIMIT 1"; 
$result = $conn->query($sql);

if ($row = $result->fetch_assoc()) {
    echo json_encode([
        // Transtypage (cast) strict pour l'interpréteur JavaScript client
        "vitesse"          => floatval($row['vitesse']),
        "tension_batterie" => floatval($row['tension_batterie']),
        "consommation"     => floatval($row['consommation']),
        "obstacle"         => intval($row['obstacle']),
        "direction"        => floatval($row['direction']), 
        "acceleration"     => floatval($row['acceleration']),
        "distance"         => floatval($row['distance_parcourue'])
    ]);
} else {
    echo json_encode(["error" => "Aucune mesure disponible"]);
}

$conn->close();
?>