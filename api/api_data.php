<?php
// Paramètres de connexion directs (Logique conservée)
$host = "localhost"; 
$user = "root"; 
$pass = ""; 
$db = "covaciel_gestion";

$conn = new mysqli($host, $user, $pass, $db);

// Définir le header pour indiquer que c'est du JSON
header('Content-Type: application/json');

if ($conn->connect_error) {
    die(json_encode(["error" => "Échec connexion BDD"]));
}

// Récupération de la dernière donnée insérée (ID 14 & 15)
// Ajout de 'consommation', 'obstacle' et 'sens' pour conformité CDC
$id_voiture = 1; 
$sql = "SELECT vitesse, tension_batterie, consommation, obstacle, sens 
        FROM telemetrie 
        WHERE id_voiture = $id_voiture 
        ORDER BY horodatage DESC LIMIT 1";

$result = $conn->query($sql);

if ($row = $result->fetch_assoc()) {
    // On s'assure que les types sont corrects pour le JavaScript
    echo json_encode([
        "vitesse" => floatval($row['vitesse']),
        "tension_batterie" => floatval($row['tension_batterie']),
        "consommation" => floatval($row['consommation']),
        "obstacle" => intval($row['obstacle']),
        "sens" => $row['sens'] // Retourne "AV" ou "AR"
    ]);
} else {
    // Valeurs par défaut si la table est vide
    echo json_encode([
        "vitesse" => 0,
        "tension_batterie" => 0,
        "consommation" => 0,
        "obstacle" => 0,
        "sens" => "AV"
    ]);
}

$conn->close();
?>