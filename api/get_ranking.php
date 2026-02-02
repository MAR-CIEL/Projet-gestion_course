<?php
// Connexion à la base de données sur la VM Ubuntu
$conn = new mysqli("172.17.50.238", "candidat4", "Azerty123#", "covaciel_gestion");

if ($conn->connect_error) {
    die(json_encode(["error" => "Connexion VM impossible"]));
}

// Requête jointe pour lier les résultats et les noms d'équipes
$sql = "SELECT r.*, v.nom_equipe 
        FROM resultat r 
        JOIN voiture v ON r.id_voiture = v.id_voiture 
        ORDER BY r.points_gagnes DESC, r.temps_total ASC";

$result = $conn->query($sql);
$ranking = [];

while($row = $result->fetch_assoc()) {
    $ranking[] = $row;
}

echo json_encode($ranking);
$conn->close();
?>