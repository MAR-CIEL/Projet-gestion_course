/**
 * @file script.js
 * @brief Gestionnaire d'IHM asynchrone asynchrone (Télémétrie Distribuée & Chrono).
 * @author Candidat 4
 * @date 2026
 */

document.addEventListener("DOMContentLoaded", function() {
    
    /** @brief Tableau local de persistance des temps au tour de notre écurie */
    const mesTours = ["--:--:--", "--:--:--", "--:--:--", "--:--:--"];

    // Initialisation du composant graphique Chart.js
    const ctx = document.getElementById('chart-speed').getContext('2d');
    const speedChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Vitesse instantanée (km/h)',
                data: [],
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.05)',
                fill: true,
                tension: 0.25
            }]
        },
        options: { 
            responsive: true, 
			maintainAspectRatio: false, // Permet au graphique de remplir son conteneur CSS
            animation: false, // Optimisation thread IHM
            scales: { y: { beginAtZero: true, max: 45 } } 
        }
    });

    /** @brief Flux 1 : Lecture asynchrone des données SQL */
    async function updateDashboard() {
        try {
            let response = await fetch('api/api_data.php?t=' + Date.now()); 
            if (!response.ok) throw new Error("HTTP Error");
            const data = await response.json();
            if (data && !data.error) {
                renderTelemetry(data, speedChart);
            }
        } catch (error) {
            console.error("Erreur passerelle API Télémétrie :", error);
        }
    }

    /** @brief Flux 2 : Lecture de la trame de course du T3 */
    async function updateChronometre() {
        try {
            let response = await fetch('data.php?t=' + Date.now()); 
            if (!response.ok) throw new Error("HTTP Error");
            const data = await response.json();
            if (data && data.success && data.chrono) {
                renderChrono(data.chrono);
            }
        } catch (error) {
            console.error("Erreur passerelle API Chronométrie :", error);
        }
    }

    /** @brief Injection et traitement logique des grandeurs physiques */
    function renderTelemetry(data, chart) {
        document.getElementById('txt-vitesse').innerText = data.vitesse + " km/h";
        document.getElementById('txt-batterie').innerText = data.tension_batterie + " V";
        document.getElementById('txt-conso').innerText = data.consommation + " A";
        document.getElementById('txt-direction').innerText = data.direction + "°";
        document.getElementById('txt-accel').innerText = data.acceleration + " m/s²";
        document.getElementById('txt-distance').innerText = data.distance + " m";

        // Traitement de l'affichage adaptatif de l'alerte LIDAR
        const obstElem = document.getElementById('txt-obstacle');
        if (data.obstacle === 1) {
            obstElem.innerText = "OBSTACLE !!!";
            obstElem.className = "mt-1 fs-5 text-danger fw-bold";
        } else {
            obstElem.innerText = "RAS";
            obstElem.className = "mt-1 fs-5 text-success";
        }

        // Alerte Règlementaire d'Immobilisation (Article 6)
        const vElem = document.getElementById('txt-vitesse');
        if (parseFloat(data.vitesse) === 0) {
            vElem.classList.add('critical-alert');
        } else {
            vElem.classList.remove('critical-alert');
        }

        // Badge de sens de marche basé sur le retour SQL
        const sensBadge = document.getElementById('badge-sens');
        if (data.sens === "AV") {
            sensBadge.innerText = "MARCHE AVANT";
            sensBadge.className = "badge bg-success fs-6 p-2 w-100";
        } else {
            sensBadge.innerText = "MARCHE ARRIÈRE";
            sensBadge.className = "badge bg-warning text-dark fs-6 p-2 w-100";
        }

        // Énergie capacitive utile (6.0V à 7.2V)
        let p = Math.max(0, Math.min(100, ((parseFloat(data.tension_batterie) - 6) / 1.2) * 100));
        const bar = document.getElementById('barre-energie');
        bar.style.width = p + "%";
        bar.innerText = Math.round(p) + "%";
        bar.className = (p < 20) ? "progress-bar bg-danger font-weight-bold" : "progress-bar bg-success font-weight-bold";

        // Actualisation de la fenêtre glissante du graphique (15 points)
        const now = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        chart.data.labels.push(now);
        chart.data.datasets[0].data.push(data.vitesse);
        if (chart.data.labels.length > 15) {
            chart.data.labels.shift();
            chart.data.datasets[0].data.shift();
        }
        chart.update('none');
    }

    /** @brief Décodage de la trame de course du T3 */
    function renderChrono(trame) {
        const fragments = trame.split('|');
        if (fragments.length >= 3) {
            const tempsCourse = fragments[0];
            const tourMaxGlobal = parseInt(fragments[1]);
            const classementIds = fragments[2].split('-');

            // 1. Chrono global
            document.getElementById("txt-chrono").innerText = tempsCourse;

            // 2. Historisation des 4 tours de notre voiture (ID 1)
            if (tourMaxGlobal >= 1 && tourMaxGlobal <= 4) {
                mesTours[tourMaxGlobal - 1] = tempsCourse;
                document.getElementById(`chrono-t${tourMaxGlobal}`).innerText = tempsCourse;
            }

            // 3. Rendu HTML textuel dynamique du classement
            let html = "";
            classementIds.forEach((id, index) => {
                if (index === 0) {
                    html += `<span class="text-warning fw-bold">🥇 P1 : Voiture n°${id}</span><br>`;
                } else {
                    html += `P${index + 1} : Voiture n°${id}<br>`;
                }
            });
            document.getElementById("txt-classement").innerHTML = html;
        }
    }

    // Amorçage des horloges de cadencement asynchrones (1Hz)
    setInterval(updateDashboard, 1000);
    setInterval(updateChronometre, 1000);
});