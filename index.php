<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Affichage temps et infos spectateurs</title>
</head>
<body>
    <h3>Temps</h3>
    <p>Bonjour</p>
    <?php
    $output = shell_exec("./COVACIELCourse_Chronometrage/main");
if ($output === null) {
        echo "<p style='color:red;'>Erreur : Impossible d'exécuter le programme.</p>";
    } else {
        echo "<pre>Résultat programme : " . htmlspecialchars($output) . "</pre>";
    }
    ?>
    <h3>Infos Spectateurs</h3>
</body>
</html>