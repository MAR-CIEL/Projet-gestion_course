<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "covaciel_gestion");

if ($conn->connect_error) die(json_encode(["error" => "BDD Down"]));

// Tri par ID_MESURE pour éviter les conflits d'horodatage
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