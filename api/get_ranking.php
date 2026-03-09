<?php
header('Content-Type: application/json');

// Connexion à la base de données sur la VM Ubuntu
$conn = new mysqli("172.17.50.233", "candidat4", "Azerty123#", "covaciel_gestion");

if ($conn->connect_error) {
    die(json_encode(["error" => "Connexion VM impossible"]));
}

// Requête optimisée : 
// On récupère les points et temps pour le classement officiel
$sql = "SELECT r.id_resultat, r.points_gagnes, r.temps_total, v.nom_equipe 
        FROM resultat r 
        JOIN voiture v ON r.id_voiture = v.id_voiture 
        ORDER BY r.points_gagnes DESC, r.temps_total ASC";

$result = $conn->query($sql);
$ranking = [];

if ($result) {
    while($row = $result->fetch_assoc()) {
        $ranking[] = [
            "equipe" => $row['nom_equipe'],
            "points" => intval($row['points_gagnes']),
            "temps"  => $row['temps_total']
        ];
    }
}

echo json_encode($ranking);
$conn->close();
?>