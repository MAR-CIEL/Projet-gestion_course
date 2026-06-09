<?php
// Fichier de diagnostic — à mettre dans C:/wamp64/www/projet_covaciel/
// Ouvrir dans le navigateur : http://localhost/projet_covaciel/test_data.php

$fichier = __DIR__ . "/course.txt";

echo "<h2>Diagnostic course.txt</h2>";
echo "<b>Chemin recherché :</b> " . htmlspecialchars($fichier) . "<br>";
echo "<b>Fichier existe :</b> " . (file_exists($fichier) ? "OUI ✅" : "NON ❌") . "<br>";

if (file_exists($fichier)) {
    $content = file_get_contents($fichier);
    echo "<b>Contenu brut :</b><pre>" . htmlspecialchars($content) . "</pre>";

    // Simuler le parsing JS
    $sepPos = strpos($content, '|');
    if ($sepPos !== false) {
        $chrono = substr($content, 0, $sepPos);
        $tours  = substr($content, $sepPos + 1);
        echo "<b>Chrono :</b> " . htmlspecialchars($chrono) . "<br>";
        $voitures = explode(';', $tours);
        foreach ($voitures as $i => $v) {
            echo "<b>Voiture " . ($i+1) . " :</b> " . htmlspecialchars($v) . "<br>";
        }
    } else {
        echo "<span style='color:red'>❌ Pas de | trouvé dans le fichier !</span>";
    }
} else {
    echo "<span style='color:red'>❌ Le fichier n'existe pas. Le programme C++ tourne-t-il ?</span>";
}
?>
