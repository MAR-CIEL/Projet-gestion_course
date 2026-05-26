<?php
/**
 * @file api_data.php
 * @brief API REST pour l'extraction des données SQL.
 * @details Ce script se connecte à la base de données sur la VM Ubuntu et retourne 
 * la ligne la plus récente de la table télémétrie au format JSON.
 */

header('Content-Type: application/json');

/** @brief Paramètres de connexion à la VM Ubuntu */
$conn = new mysqli("172.17.50.233", "candidat4", "Azerty123#", "covaciel_gestion"); // Connexion à la BDD (adapter au déploiement)

if ($conn->connect_error) {
    die(json_encode(["error" => "Connexion VM Ubuntu impossible"]));
}

/** @section Query Extraction de la télémétrie la plus récente */
$sql = "SELECT vitesse, tension_batterie, consommation, obstacle, direction, acceleration, distance_parcourue FROM telemetrie ORDER BY id_mesure DESC LIMIT 1"; 
$result = $conn->query($sql);

if ($row = $result->fetch_assoc()) {
    echo json_encode([
        "vitesse" => floatval($row['vitesse']),
        "tension_batterie" => floatval($row['tension_batterie']),
        "consommation" => floatval($row['consommation']),
        "obstacle" => intval($row['obstacle']),
        "direction" => floatval($row['direction']),
		"acceleration" => floatval($row['acceleration']),
        "distance" => floatval($row['distance_parcourue'])
    ]);
} else {
    echo json_encode(["error" => "Aucune donnée trouvée"]);
}
$conn->close();
?>