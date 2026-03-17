<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>COVACIEL 2026 - Informations Spectateurs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="icon" href="img/logo-lycee-branly.png" type="image/x-icon">
    <script src="assets/script.js" defer></script>
</head>

<body>
    <header class="barre-entete">
        <span class="navbar-brand">COVACIEL 2026 - Informations Spectateurs</span>
    </header>

    <main>
        <section class="camera">
            <div id="camera-1">
                <div class="card-body-p-0-bg-black">
                    <iframe id="video-feed-1" src="http://172.17.50.94:8889/cam" frameborder="0" allowfullscreen>
                    </iframe>
                </div>
                <div class="card-header">Caméra Voiture 1</div>
            </div>
            <div id="camera-2">
                <div class="card-body-p-0-bg-black">
                    <img id="video-feed-2" src="http://172.17.50.53:8080/?action=stream"
                        onerror="this.src='img/no-signal.jpg';" style="width:100%;">
                </div>
                <div class="card-header">Caméra Voiture 2</div>
            </div>
        </section>

        <div class="bas-page" id="bas-page">
            <section class="donnees-voitures">
                <h3 class="titre-voiture" id="titre-voiture">Voiture n°</h3>
                <!--Doit dépendre du numéro de la voiture-->
                <div class="bloc-voiture-haut">
                    <div class="vitesse">
                        <h4 class="titre-donnee">Vitesse</h4>
                        <h2 id="txt-vitesse">0 km/h</h2>
                    </div>
                    <div class="energie">
                        <h4 class="titre-donnee">Énergie</h4>
                        <div class="progress">
                            <div id="barre-energie" class="progress-bar bg-success" style="width: 100%">100%</div>
                        </div>
                    </div>
                </div>

                <div class="temps-tours">
                    <div class="numero-tours">
                        <span>Tour 1</span>
                        <span>Tour 2</span>
                        <span>Tour 3</span>
                        <span>Tour 4</span>
                    </div>
                    <div class="donnees-tours">
                        <span>--:--:--</span>
                        <span>--:--:--</span>
                        <span>--:--:--</span>
                        <span>--:--:--</span>
                    </div>
                </div>
                <div class="card-header">Données Voitures</div>
            </section>

            <section class="donnees-course">
                <div class="bloc-course-haut">
                    <div class="chrono">
                        <h3 class="titre-donnee">Chrono</h3>
                        <h2 id="txt-chrono">En attente...</h2>
                    </div>
                    <div class="classement">
                        <h3 class="titre-donnee">Classement</h3>
                        <p>1-----------<br />2-----------<br />3-----------</p>
                    </div>
                </div>
                <div class="card-header">Données Course</div>
            </section>
        </div>
    </main>
</body>

</html>