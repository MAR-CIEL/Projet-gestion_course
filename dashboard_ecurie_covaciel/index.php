<?php
/**
 * @file index.php
 * @brief Interface Utilisateur (IHM) du Dashboard Télémétrie, Vidéo & Chronométrage.
 * @details Cette page Web constitue le poste de contrôle complet pour un véhicule spécifique. 
 * Elle affiche en temps réel les données reçues de la BDD MySQL (vitesse, batterie, obstacles, accélération, distance)
 * ainsi que le flux vidéo en direct et les données de chronométrage du T3 (course.txt).
 * @author Candidat 4 - Responsable Télémétrie & UX (Intégration complète des indicateurs)
 * @version 3.2
 * @date 2026
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard T4 - Télémétrie & Chrono</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="assets/style.css">
    <script src="assets/script.js" defer></script>
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <span class="navbar-brand">COVACIEL 2026 - Télémétrie, Vidéo & Chrono en Direct - VOITURE 1</span>
        </div>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">Indicateurs Télémétriques (Temps Réel)</div>
                    <div class="card-body">
                        <div class="telemetry-container">
                            <div class="fixed-column">
                                <h6>Vitesse</h6><h2 id="txt-vitesse">0 km/h</h2>
                            </div>
                            <div class="fixed-column">
                                <h6>Batterie</h6><h2 id="txt-batterie">0 V</h2>
                            </div>
                            <div class="fixed-column">
                                <h6>Consommation</h6><h2 id="txt-conso">0 A</h2>
                            </div>
                            <div class="fixed-column">
                                <h6>Obstacle (LIDAR)</h6><h2 id="txt-obstacle">NUL</h2>
                            </div>
                            <div class="fixed-column">
                                <h6>Direction</h6><h2 id="txt-direction">0°</h2>
                            </div>
                            <div class="fixed-column">
                                <h6>Accélération</h6><h2 id="txt-accel">0 m/s²</h2>
                            </div>
                            <div class="fixed-column">
                                <h6>Distance</h6><h2 id="txt-distance">0 m</h2>
                            </div>
                            <div class="fixed-column">
                                <h6>Sens</h6><span id="badge-sens" class="badge bg-success fs-6 mt-1">MARCHE AVANT</span>
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
                        
                        <hr>
						
						<!-- Chronométrage et classement en direct non implémenté -->
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <h6>Chrono Général</h6>
                                <h2 id="txt-chrono" class="text-danger font-monospace">00:00:00</h2>
                            </div>
                            <div class="col-md-6">
                                <h6>Classement Piste</h6>
                                <div id="txt-classement" class="font-monospace bg-dark text-light p-2 rounded small text-start">En attente...</div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6>Temps par Tours (Ma Voiture)</h6>
                                <div class="d-flex justify-content-between font-monospace text-muted bg-white p-2 rounded border">
                                    <div>T1: <span id="chrono-t1" class="text-dark fw-bold">--:--:--</span></div>
                                    <div>T2: <span id="chrono-t2" class="text-dark fw-bold">--:--:--</span></div>
                                    <div>T3: <span id="chrono-t3" class="text-dark fw-bold">--:--:--</span></div>
                                    <div>T4: <span id="chrono-t4" class="text-dark fw-bold">--:--:--</span></div>
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
                <div class="card shadow-sm bg-black">
                    <iframe id="video-feed" 
                            src="http://192.168.1.4:8889/cam" 
                            frameborder="0" 
                            allowfullscreen 
                            onerror="this.onerror=null; this.src='img/no-signal.jpg';">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</body>
</html>