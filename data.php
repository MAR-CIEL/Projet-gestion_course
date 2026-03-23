<?php
header('Content-Type: application/json');
$fichier_txt = __DIR__ . "/course.txt";
$chrono_data = "00:00:00|--:--:--,--:--:--,--:--:--,--:--:--;--:--:--,--:--:--,--:--:--,--:--:--;--:--:--,--:--:--,--:--:--,--:--:--";
$success_chrono = false;

if (file_exists($fichier_txt)) {
    $content = trim(file_get_contents($fichier_txt));
    if (!empty($content))
    {
        $chrono_data = $content;
        $success_chrono = true;
    }
}

$host = "172.17.50.223";
$user = "candidat4";
$pass = "Azerty123#";
$db = "covaciel_gestion";

$telemetrie = [
    "vitesse" => 0,
    "tension_batterie" => 0
];

$conn = new mysqli($host, $user, $pass, $db);

if (!$conn->connect_error)
{
    $sql = "SELECT vitesse, tension_batterie FROM telemetrie ORDER BY id_mesure DESC LIMIT 1";
    $result = $conn->query($sql);
    
    if ($row = $result->fetch_assoc())
    {
        $telemetrie = [
            "vitesse" => floatval($row['vitesse']),
            "tension_batterie" => floatval($row['tension_batterie'])
        ];
    }
    $conn->close();
}

echo json_encode([
    "success" => $success_chrono,
    "chrono" => $chrono_data,
    "telemetrie" => $telemetrie
]);

exit;