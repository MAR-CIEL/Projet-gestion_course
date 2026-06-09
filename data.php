<?php
header('Content-Type: application/json; charset=utf-8');
error_reporting(0);
ini_set('display_errors', 0);

$fichier_txt = __DIR__ . "/course.txt";
$chrono_data = "00:00:00|--:--:--,--:--:--,--:--:--,--:--:--;--:--:--,--:--:--,--:--:--,--:--:--;--:--:--,--:--:--,--:--:--,--:--:--";
$success_chrono = false;

if (file_exists($fichier_txt)) {
    $content = trim(file_get_contents($fichier_txt));
    if (!empty($content)) {
        $chrono_data = $content;
        $success_chrono = true;
    }
}

$host = "172.17.50.233";
$user = "candidat4";
$pass = "Azerty123#";
$db   = "covaciel_gestion";

$telemetrie = ["vitesse" => 0, "tension_batterie" => 0];

mysqli_report(MYSQLI_REPORT_STRICT);
try {
    $conn = mysqli_init();
    $conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 1);
    if (@$conn->real_connect($host, $user, $pass, $db)) {
        $result = $conn->query("SELECT vitesse, tension_batterie FROM telemetrie ORDER BY id_mesure DESC LIMIT 1");
        if ($result && $row = $result->fetch_assoc()) {
            $telemetrie = ["vitesse" => floatval($row['vitesse']), "tension_batterie" => floatval($row['tension_batterie'])];
        }
        $conn->close();
    }
} catch (Exception $e) {}

echo json_encode(["success" => $success_chrono, "chrono" => $chrono_data, "telemetrie" => $telemetrie], JSON_UNESCAPED_UNICODE);
exit;
