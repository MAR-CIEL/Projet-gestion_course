<?php
header('Content-Type: application/json');
// Connexion à la base de données distante sur la VM Linux
$conn = new mysqli("172.17.50.238", "candidat4", "Azerty123#", "covaciel_gestion"); //

if ($conn->connect_error) {
    die(json_encode(["error" => "Échec connexion VM Ubuntu"]));
}

// Récupération de la mesure la plus récente
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
    echo json_encode(["error" => "Aucune donnée"]);
}
$conn->close();
?>