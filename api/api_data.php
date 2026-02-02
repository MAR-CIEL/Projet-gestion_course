<?php
header('Content-Type: application/json');
// Connexion à la VM Ubuntu depuis WAMP
$conn = new mysqli("172.17.50.238", "candidat4", "Azerty123#", "covaciel_gestion");

if ($conn->connect_error) {
    die(json_encode(["error" => "Connexion VM Ubuntu impossible"]));
}

// Récupération de la dernière mesure triée par ID
$sql = "SELECT vitesse, tension_batterie, consommation, obstacle, sens 
        FROM telemetrie 
        ORDER BY id_mesure DESC LIMIT 1"; 

$result = $conn->query($sql);

if ($row = $result->fetch_assoc()) {
    echo json_encode([
        "vitesse" => floatval($row['vitesse']),
        "tension_batterie" => floatval($row['tension_batterie']),
        "consommation" => floatval($row['consommation']),
        "obstacle" => intval($row['obstacle']),
        "sens" => $row['sens']
    ]);
} else {
    echo json_encode(["error" => "Aucune donnée trouvée"]);
}
$conn->close();
?>