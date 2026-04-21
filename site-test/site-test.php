<?php
require('phpMQTT.php');

$server = '172.17.50.149';     
$port = 1883;
$client_id = 'php-gui-client';

if (isset($_POST['action'])) {
    $mqtt = new bluerhinos\phpMQTT($server, $port, $client_id);
    
    if ($mqtt->connect(true, NULL, '', '')) {
        if ($_POST['action'] == 'START') {
            $mqtt->publish('covaciel/START', '$Go', 0, false);
            $message = "Course démarrée";
        } 
        elseif ($_POST['action'] == 'STOP') {
            $mqtt->publish('covaciel/STOP', '$Stop', 0, false);
            $message = "Course arrêtée";
        }
        $mqtt->close();
    } else {
        $message = "Erreur : Impossible de joindre le Broker";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>IHM test</title>
    <style>
        .btn { padding: 20px; color: white; border: none; font-weight: bold; cursor: pointer; border-radius: 20px; width: 300px; font-size: 18px; }
        .green { background: #27ae60; } .red { background: #c0392b; }
        .container { text-align: center; margin-top: 50px; font-family: Arial; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gestion de Course</h1>
        <?php if(isset($message)) echo "<p><strong>$message</strong></p>"; ?>
        
        <form method="post">
            <button type="submit" name="action" value="START" class="btn green">DÉMARRAGE (START)</button>
            <button type="submit" name="action" value="STOP" class="btn red">ARRÊT (STOP)</button>
        </form>
    </div>
</body>
</html>