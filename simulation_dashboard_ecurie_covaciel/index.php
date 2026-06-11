<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Écurie - COVACIEL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="assets/script.js" defer></script>
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <span class="navbar-brand">COVACIEL 2026 - Supervision de Stand Distribuée - VOITURE 1</span>
    </div>
</nav>

<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white font-weight-bold">Indicateurs Véhicule (172.17.50.142)</div>
                <div class="card-body">
                    <div class="row text-center mb-3">
                        <div class="col-6 col-md-3 border-end mb-2">
                            <small class="text-muted text-uppercase">Vitesse</small>
                            <h3 id="txt-vitesse" class="mt-1">0 km/h</h3>
                        </div>
                        <div class="col-6 col-md-3 border-end mb-2">
                            <small class="text-muted text-uppercase">Batterie</small>
                            <h3 id="txt-batterie" class="mt-1">0.0 V</h3>
                        </div>
                        <div class="col-6 col-md-3 border-end mb-2">
                            <small class="text-muted text-uppercase">Conso</small>
                            <h3 id="txt-conso" class="mt-1">0.0 A</h3>
                        </div>
                        <div class="col-6 col-md-3 mb-2">
                            <small class="text-muted text-uppercase">LIDAR</small>
                            <h3 id="txt-obstacle" class="mt-1 fs-5">RAS</h3>
                        </div>
                    </div>
                    
                    <div class="row text-center border-top pt-2 mb-3">
                        <div class="col-4 border-end">
                            <small class="text-muted">Direction</small>
                            <h5 id="txt-direction" class="mt-1">0°</h5>
                        </div>
                        <div class="col-4 border-end">
                            <small class="text-muted">Accélération</small>
                            <h5 id="txt-accel" class="mt-1">0 m/s²</h5>
                        </div>
                        <div class="col-4">
                            <small class="text-muted">Distance</small>
                            <h5 id="txt-distance" class="mt-1">0 m</h5>
                        </div>
                    </div>

                    <hr>
                    <div class="row align-items-center">
                        <div class="col-md-5 text-center">
                            <span id="badge-sens" class="badge bg-success fs-6 p-2 w-100">MARCHE AVANT</span>
                        </div>
                        <div class="col-md-7">
                            <small class="text-muted">Capacité Énergie Capacité</small>
                            <div class="progress mt-1" style="height: 20px;">
                                <div id="barre-energie" class="progress-bar bg-success font-weight-bold" style="width: 0%">0%</div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-3">
                    <div class="row bg-white p-3 rounded border mx-1 shadow-sm">
                        <div class="col-md-6 border-end text-center">
                            <small class="text-uppercase text-muted fw-bold">Chronomètre Général</small>
                            <h2 id="txt-chrono" class="text-danger font-monospace mt-1 fw-bold">00:00:00</h2>
                        </div>
                        <div class="col-md-6 ps-3">
                            <small class="text-uppercase text-muted fw-bold">Positions en Piste</small>
                            <div id="txt-classement" class="font-monospace bg-dark text-light p-2 rounded small text-start mt-1">En attente...</div>
                        </div>
                        <div class="col-12 mt-3 pt-2 border-top">
                            <small class="text-muted d-block mb-1">Session Chrono - 4 Tours (Ma Voiture)</small>
                            <div class="d-flex justify-content-between font-monospace bg-light p-2 rounded text-center text-sm">
                                <div>T1: <span id="chrono-t1" class="fw-bold text-dark">--:--:--</span></div>
                                <div>T2: <span id="chrono-t2" class="fw-bold text-dark">--:--:--</span></div>
                                <div>T3: <span id="chrono-t3" class="fw-bold text-dark">--:--:--</span></div>
                                <div>T4: <span id="chrono-t4" class="fw-bold text-dark">--:--:--</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="chart-container card shadow-sm mb-4 p-2 bg-white">
                    <canvas id="chart-speed"></canvas>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-danger text-white font-weight-bold">Flux Vidéo Arbitre (MJPEG)</div>
                <div class="card-body p-0 bg-black d-flex align-items-center justify-content-center" style="min-height: 400px;">
                    <img id="video-feed" src="http://192.168.1.7:8889/cam" 
                         onerror="this.src='img/no-signal.jpg';" class="w-100 h-100 object-fit-cover">
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>