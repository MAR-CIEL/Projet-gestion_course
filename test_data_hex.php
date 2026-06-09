<?php
// Diagnostic hex — montre exactement ce qui est écrit dans le fichier
$fichier = __DIR__ . "/course.txt";

echo "<h2>Diagnostic HEX course.txt</h2>";
echo "<b>Chemin :</b> " . htmlspecialchars($fichier) . "<br>";
echo "<b>Fichier existe :</b> " . (file_exists($fichier) ? "OUI ✅" : "NON ❌") . "<br><br>";

if (file_exists($fichier)) {
    $content = file_get_contents($fichier);
    $len = strlen($content);
    echo "<b>Longueur :</b> $len bytes<br><br>";

    // Afficher le contenu lisible
    echo "<b>Contenu lisible :</b><pre>" . htmlspecialchars($content) . "</pre>";

    // Afficher en HEX (premiers 300 caractères)
    echo "<b>HEX (premiers 300 bytes) :</b><pre>";
    for ($i = 0; $i < min(300, strlen($content)); $i++) {
        echo str_pad(dechex(ord($content[$i])), 2, '0', STR_PAD_LEFT) . " ";
        if (($i + 1) % 16 == 0) echo "\n";
    }
    echo "</pre>";

    // Chercher le séparateur après le |
    $sepPos = strpos($content, '|');
    if ($sepPos !== false) {
        echo "<b>Position du | :</b> $sepPos<br>";
        echo "<b>Après le | :</b><pre>";
        $afterSep = substr($content, $sepPos + 1, 100);
        // Afficher en ASCII et HEX côte à côte
        for ($i = 0; $i < strlen($afterSep); $i++) {
            $char = $afterSep[$i];
            $hex = str_pad(dechex(ord($char)), 2, '0', STR_PAD_LEFT);
            $ascii = (ord($char) >= 32 && ord($char) < 127) ? $char : ".";
            echo "$hex($ascii) ";
            if (($i + 1) % 16 == 0) echo "\n";
        }
        echo "</pre>";

        // Déduire les séparateurs
        $afterSep = substr($content, $sepPos + 1);
        echo "<b>Caractères uniques trouvés après le | :</b><br>";
        $chars = array_unique(str_split($afterSep));
        foreach ($chars as $c) {
            $hex = str_pad(dechex(ord($c)), 2, '0', STR_PAD_LEFT);
            $name = (ord($c) >= 32 && ord($c) < 127) ? htmlspecialchars($c) : "NON-ASCII";
            echo "Hex: $hex | Decimal: " . ord($c) . " | Caractère: $name<br>";
        }
    }
} else {
    echo "<span style='color:red'>❌ Fichier non trouvé !</span>";
}
?>
