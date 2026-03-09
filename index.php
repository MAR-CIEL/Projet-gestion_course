<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard T4 COVACIEL 2026</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<link rel="stylesheet" href="assets/style.css">
    <script src="assets/script.js" defer></script>
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container"><span class="navbar-brand">COVACIEL 2026 - Télémétrie & Vidéo en Direct - VOITURE Groupe Hugo Oval</span></div>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">État du Véhicule (Télémétrie)</div>
                    <div class="card-body text-center">
                        <div class="telemetry-container">
                            <div class="telemetry-item fixed-column"><h6>Vitesse</h6><h2 id="txt-vitesse">0 km/h</h2></div>
                            <div class="telemetry-item fixed-column"><h6>Batterie</h6><h2 id="txt-batterie">0.0 V</h2></div>
                            <div class="telemetry-item fixed-column"><h6>Consommation</h6><h2 id="txt-conso">0.0 A</h2></div>
                            <div class="telemetry-item fixed-column"><h6>Obstacle</h6><h2 id="txt-obstacle">Nul</h2></div>
                        </div>
						<div class="telemetry-container mt-2">
							<div class="telemetry-item fixed-column"><h6>Accélération</h6><h2 id="txt-accel">0.00</h2></div>
							<div class="telemetry-item fixed-column"><h6>Distance</h6><h2 id="txt-distance">0.00 m</h2></div>
						</div>
                        <div class="row mt-4">
                            <div class="col-md-6"><h6>Direction</h6><h2 id="txt-direction">0°</h2></div>
                            <div class="col-md-6">
                                <h6>Sens</h6><span id="badge-sens" class="badge bg-success fs-5">MARCHE AVANT</span>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6>Énergie</h6>
                                <div class="progress">
                                    <div id="prog-energie" class="progress-bar bg-success" style="width: 0%;">0%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                
				<div class="card shadow-sm mb-4">
				<div class="card-header bg-secondary text-white">Evolution de la Vitesse</div>
                <canvas id="chart-speed"></canvas>
				</div>
            </div>
            <div class="col-md-6">
				<h1>Vidéo Caméra Embarquée</h1>
                <div class="card shadow-sm bg-black"><img id="video-feed" src="http://172.17.50.239:8000/video_feed" onerror="this.src='img/no-signal.jpg';"></div>
			</div>
        </div>
    </div>
</body>
</html>