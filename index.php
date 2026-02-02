<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard COVACIEL - Candidat 4</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="assets/script.js" defer></script>
</head>
<body>
<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <span class="navbar-brand">COVACIEL 2026 - Télémétrie & Vidéo</span>
    </div>
</nav>

<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">État du Véhicule (Télémétrie)</div>
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col-md-3">
                            <h6>Vitesse</h6>
                            <h2 id="txt-vitesse">0 km/h</h2>
                        </div>
                        <div class="col-md-3">
                            <h6>Batterie</h6>
                            <h2 id="txt-batterie">0.0 V</h2>
                        </div>
                        <div class="col-md-3">
                            <h6>Consommation</h6>
                            <h2 id="txt-conso">0.0 A</h2>
                        </div>
                        <div class="col-md-3">
                            <h6>Obstacle</h6>
                            <h2 id="txt-obstacle">Néant</h2>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Sens de marche</h6>
                            <span id="badge-sens" class="badge bg-secondary">Inconnu</span>
                        </div>
                        <div class="col-md-6">
                            <h6>Énergie</h6>
                            <div class="progress">
                                <div id="barre-energie" class="progress-bar bg-success" style="width: 100%">100%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <canvas id="chart-speed" height="150"></canvas>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">Caméra Embarquée</div>
                <div class="card-body p-0 bg-black">
                    <img id="video-feed" src="http://192.168.1.50:8080/stream.mjpg" 
                         onerror="this.src='img/no-signal.jpg';" style="width:100%;">
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>