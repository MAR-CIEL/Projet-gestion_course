<?php
require('phpMQTT.php');

/**
 * @file course.php
 * @brief Fichier IHM pour le commissiare de course (drapeaux, disqualification)
 * @author Marchant Alexandre
 * @date 01/06/2026
 * @details Ce fichier sert au visuel de l'IHM, le code php sert a réagir avec les bouton et a intégrer le MQTT ainsi qu'a réagir avec les Raspberry pi en MQTT
 */
$server = '172.17.50.149';     
$port = 1883;
$client_id = 'php-gui-client-final-';
$xbee_port = 'COM7';

/**
 * @brief Fonction qui permet de gérer le message et de l'envoyer sur le fichier xbee.py ou il sera transformer en trame
 * @param string trame qui est sous forme de message (START ou STOP)
 */
function envoyerXBee($trame) {
    $python = 'python';
    $script = 'C:\\wamp64\\www\\site-test\\xbee.py';
    $cmd = "$python \"$script\" " . escapeshellarg($trame) . " 2>&1";
    shell_exec($cmd);
}

/**
 * @brief Structure de controle (if) qui permet de gérer les bouton, de publier les message ainsi que les trames sur les bon topîcs et les trames pour l'xbee
 */
if (isset($_POST['action'])) {
    $mqtt = new bluerhinos\phpMQTT($server, $port, $client_id);
    
    if ($mqtt->connect(true, NULL, '', '')) {
        if ($_POST['action'] == 'START') {
            $mqtt->publish('covaciel/START', '$GO;', 0, false);
			envoyerXBee('START');
            $message = "Course démarrée";
        } 
        elseif ($_POST['action'] == 'STOP') {
            $mqtt->publish('covaciel/STOP', '$STOP;', 0, false);
			envoyerXBee('STOP');
            $message = "Course arrêtée";
        }
		elseif ($_POST['action'] == 'DANGER') {
            $mqtt->publish('covaciel/DANGER', '$DANGER', 0, false);
            $message = "Danger sur la piste";
        }
		elseif ($_POST['action'] == 'DEPASSEMENT') {
            $mqtt->publish('covaciel/DEPASSEMENT', '$DEPASSEMENT', 0, false);
            $message = "Dépassement en cours";
        }
		elseif ($_POST['action'] == 'DISQUALIFICATION') {
			$ecurie = isset ($_POST['ecurie']) ?$_POST['ecurie'] : 'Inconnue';
            $mqtt->publish('covaciel/DISQUALIFICATION', '$DISQUALIFICATION|'. $ecurie, 0, false);
            $message = "Disqualification de : " . $ecurie;
        }
		elseif ($_POST['action'] == 'PISTEDA') {
            $mqtt->publish('covaciel/PISTEDA', '$PISTEDA', 0, false);
            $message = "Piste dangereuse";
        }
        $mqtt->close();
    } else {
        $message = "Erreur : Impossible de joindre le Broker";
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Course - CoVaciel</title>
    <link rel="stylesheet" href="style.css">
	<style>
		.btn2 { padding: 10px; color: white; border: none; font-weight: bold; cursor: pointer; width: 300px; font-size: 18px; flex: 1; border-radius: 5px; transition: 0.2s;}
		.btn2:hover {filter: brightness(1.1); transform: translateY(-2px);}
		.alert2 { padding: 15px; margin-bottom: 10px; font-weight: bold; cursor: pointer; justify-content: center; align-items: center; width: 100%;}
		.disqu { margin-top: 15px; background: #d9d9d9; padding: 20; height: 60px; width : 100%; box-sizing:border-box; border : none; }
	</style>
</head>
<body>
    <nav class="sidebar">
        <ul class="nav-links">
            <li><a href="index.html">Accueil</a></li>
            <li><a href="ecurie.html">Écurie</a></li>
            <li class="active">Course</li>
            <li><a href="historique.html">Historique</a></li>
        </ul>
    </nav>

    <main class="content">
        <div class="dashboard-grid">
            <div class="card">
                <h3>Contrôle de la course</h3>
				<form method="post">
					<div class="btn-group">
						<button type="submit" name="action" value="START" class="btn2 btn-start">🟢 DÉPART</button>
						<button type="submit" name="action" value="STOP" class="btn2 btn-stop">🏁 ARRÊT</button>
					</div>
				</form>
                <div class="status-box" id="race-status">
					<?php echo isset($message) ? $message : "État : En attente..."; ?>
				</div>
				
            </div>

            <div class="card">
                <h3>Classement en direct</h3>
                <table id="live-leaderboard">
                    <thead>
                        <tr>
                            <th>Pos</th>
                            <th>Écurie</th>
                            <th>Tours</th>
                        </tr>
						<tr>
                            <th>1</th>
                            <th>Mclaren</th>
                            <th>3/4</th>
                        </tr>
						<tr>
                            <th>2</th>
                            <th>Redbull</th>
                            <th>3/4</th>
                        </tr>
						<tr>
                            <th>3</th>
                            <th>Mercedes</th>
                            <th>1/4</th>
                        </tr>
                    </thead>
                    <tbody id="classement-body">
                        </tbody>
                </table>
            </div>

            <div class="card wide">
                <h3>Arbitrage</h3>
				<form method="post">
					<button type="submit" name="action" value="DANGER" class="btn2 alert2 danger">🚨 DANGER SUR PISTE</button>
					<button type="submit" name="action" value="DEPASSEMENT" class="btn2 alert2 overtaking">🏎️ DÉPASSEMENT EN COURS</button>
					<button type="submit" name="action" value="PISTEDA" class="btn2 alert2 btn-pisteda">🔴 PISTE DANGEREUSE</button>
				</form>
            </div>
			<div class="card wide">
                <h3>Disqualification</h3>
				<form method="post">
					<input type="texte" name="ecurie" placeholder="ID de l'écurie a disqualifier" class="disqu">
					<button type="submit" name="action" value="DISQUALIFICATION" class="btn2 alert2 btn-disqualify">🏴 󠁧󠁢󠁥󠁮󠁿DISQUALIFIER UNE VOITURE</button>
				</form>
            </div>
        </div>
    </main>
</body>
</html>