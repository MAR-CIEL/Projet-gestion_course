<?php
/**
 * @file api_data.php
 * @brief API REST pour l'extraction distribuée des métriques SQL
 * @details Cible le serveur WAMP centralisé à l'adresse 172.17.50.142
 * @author Candidat 4
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Précision CORS pour le travail collaboratif

/** @brief Paramètres d'accès réseau durcis */
$db_host = "172.17.50.142"; 
$db_user = "user_telemetrie";
$db_pass = "Azerty123#";
$db_name = "covaciel_gestion";

// Instanciation de la connexion
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die(json_encode(["error" => "Serveur MySQL à l'adresse 172.17.50.142 injoignable"]));
}

/** @brief Sélection de la dernière mesure physique enregistrée par l'injecteur Python */
$sql = "SELECT vitesse, tension_batterie, consommation, obstacle, sens, direction, acceleration, distance_parcourue 
        FROM telemetrie 
        ORDER BY id_mesure DESC LIMIT 1"; 

$result = $conn->query($sql);

if ($row = $result->fetch_assoc()) {
    echo json_encode([
        "vitesse"            => floatval($row['vitesse']),
        "tension_batterie"   => floatval($row['tension_batterie']),
        "consommation"       => floatval($row['consommation']),
        "obstacle"           => intval($row['obstacle']),
        "sens"               => $row['sens'],
        "direction"          => floatval($row['direction']),
        "acceleration"       => floatval($row['acceleration']),
        "distance"           => floatval($row['distance_parcourue'])
    ]);
} else {
    echo json_encode(["error" => "Table de télémétrie vide. Lancez le script Python."]);
}

$conn->close();
?>