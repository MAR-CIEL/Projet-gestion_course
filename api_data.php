<?php
header('Content-Type: application/json');
$conn = new mysqli("172.17.50.233", "candidat4", "Azerty123#", "covaciel_gestion");

if ($conn->connect_error) {
    die(json_encode(["error" => "Connexion VM Ubuntu impossible"]));
}

$sql = "SELECT vitesse, consommation FROM telemetrie ORDER BY id_mesure DESC LIMIT 1"; 
$result = $conn->query($sql);

if ($row = $result->fetch_assoc()) {
    echo json_encode([
        "vitesse" => floatval($row['vitesse']),
        "consommation" => floatval($row['consommation']),
    ]);
} else {
    echo json_encode(["error" => "Aucune donnée trouvée"]);
}
$conn->close();
?>