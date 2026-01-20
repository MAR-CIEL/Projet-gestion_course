<?php
$conn = new mysqli("localhost", "root", "", "covaciel_gestion");

// Requête jointe pour lier résultats et noms d'équipes
$sql = "SELECT r.*, v.nomEquipe 
        FROM resultat r 
        JOIN voiture v ON r.id_voiture = v.id_voiture 
        ORDER BY r.pointsGagnes DESC, r.tempsTotal ASC";

$result = $conn->query($sql);
$ranking = [];

while($row = $result->fetch_assoc()) {
    $ranking[] = $row;
}

echo json_encode($ranking);
$conn->close();
?>
