/**
 * @file script.js
 * @brief Gestionnaire de mise à jour dynamique de l'IHM (JavaScript/AJAX).
 * @details Gère le rafraîchissement asynchrone des indicateurs (LIDAR, vitesse, batterie), 
 * consomme l'API de chronométrage du T3, applique les alertes de l'Article 6 et actualise le graphique.
 * @author Candidat 4 - Télémétrie & UX
 * @date 2026
 */

document.addEventListener("DOMContentLoaded", function() {
    
    /** @brief Historique local des temps de course pour la voiture 1 */
    const mesTours = ["--:--:--", "--:--:--", "--:--:--", "--:--:--"];

    /** @brief Initialisation du composant graphique Chart.js pour le suivi de vitesse */	
    const ctx = document.getElementById('chart-speed').getContext('2d');
    const speedChart = new Chart(ctx, {
        type: 'line',
        data: { 
            labels: [], 
            datasets: [{ 
                label: 'Vitesse (km/h)', 
                data: [], 
                borderColor: '#007bff', 
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.3 
            }] 
        },
        options: { 
            responsive: true, 
            animation: false, // Économie CPU
            scales: { y: { beginAtZero: true, max: 20 } } 
        }
    });
	
    /** @brief Récupère la télémétrie filtrée via l'API REST PHP */    
    async function updateDashboard() {
        try {
            let response = await fetch('api/api_data.php?t=' + Date.now()); 
            const data = await response.json();
            if (data && !data.error) renderData(data, speedChart);
        } catch (e) { 
            console.error("Erreur API Télémétrie", e); 
        }
    }

    /** @brief Récupère le statut de course du chronomètre du T3 (course.txt) */
    async function updateChronometre() {
        try {
            let response = await fetch('data.php?t=' + Date.now()); 
            const data = await response.json();
            if (data && data.success && data.chrono) {
                renderChrono(data.chrono);
            }
        } catch (e) { 
            console.error("Erreur API Chrono T3", e); 
        }
    }
	
    /** @brief Mappe les indicateurs dans le DOM et exécute les calculs métiers UX */
    function renderData(data, chart) {
        document.getElementById('txt-vitesse').innerText = data.vitesse + " km/h";
        document.getElementById('txt-batterie').innerText = data.tension_batterie + " V";
        document.getElementById('txt-conso').innerText = data.consommation + " A";
        document.getElementById('txt-direction').innerText = data.direction + "°";

        // --- UX METIER : AFFICHAGE ADAPTATIF DE L'OBSTACLE REEL (LIDAR) ---
        const obstacleElement = document.getElementById('txt-obstacle');
        if (data.obstacle === 1) {
            obstacleElement.innerText = "OBSTACLE !!!";
            obstacleElement.style.color = "#e74c3c"; // Texte rouge d'alerte
            obstacleElement.style.fontWeight = "bold";
        } else {
            obstacleElement.innerText = "RAS";
            obstacleElement.style.color = "#2ecc71"; // Texte vert nominal
            obstacleElement.style.fontWeight = "normal";
        }

        /** @section Article_6 Alerte d'immobilisation règlementaire */
        const vitesseTxt = document.getElementById('txt-vitesse');
        if (data.vitesse === 0) {
            vitesseTxt.classList.add('critical-alert'); // Active l'animation CSS de clignotement
        } else {
            vitesseTxt.classList.remove('critical-alert');
        }

        /** @section Logic_Sens Déduction visuelle du sens de marche */
        const sensBadge = document.getElementById('badge-sens');
        if (Math.abs(data.direction) > 90) {
            sensBadge.innerText = "MARCHE ARRIÈRE";
            sensBadge.className = "badge bg-warning text-dark fs-5";
        } else {
            sensBadge.innerText = "MARCHE AVANT";
            sensBadge.className = "badge bg-success fs-5";
        }

        /** @section Logic_Energy Calcul linéaire de la jauge d'énergie utile [6.0V - 7.2V] */
        const tension = parseFloat(data.tension_batterie);
        let pct = Math.round(((tension - 6.0) / 1.2) * 100);
        pct = Math.max(0, Math.min(100, pct)); 

        const prog = document.getElementById('prog-energie');
        prog.style.width = pct + "%";
        prog.innerText = pct + "%"; 
        prog.className = (pct < 20) ? "progress-bar bg-danger" : "progress-bar bg-success";

        /** @section Fenetre_Glissante Ajout du point au graphique et nettoyage RAM */
        const now = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        chart.data.labels.push(now);
        chart.data.datasets[0].data.push(data.vitesse);
        
        if (chart.data.labels.length > 15) { 
            chart.data.labels.shift(); 
            chart.data.datasets[0].data.shift(); 
        }
        chart.update('none'); 
    }

    /** @brief Analyse et extrait les données de la trame du T3 */
    function renderChrono(chronoTrame) {
        const fragments = chronoTrame.split('|');
        if (fragments.length >= 3) {
            const tempsTotal = fragments[0];
            const numeroTour = parseInt(fragments[1]);
            const classementIds = fragments[2].split('-');

            document.getElementById("txt-chrono").innerText = tempsTotal;

            // Historisation du tour pour la Voiture 1
            if (numeroTour >= 1 && numeroTour <= 4) {
                mesTours[numeroTour - 1] = tempsTotal;
                document.getElementById(`chrono-t${numeroTour}`).innerText = tempsTotal;
            }

            // Reconstruction dynamique du classement général sur la piste
            let classementHTML = "";
            classementIds.forEach((id, index) => {
                if (index === 0 && id === "1") {
                    classementHTML += `<span class="text-success fw-bold">P${index + 1} : Voiture n°${id} 🏆</span><br>`;
                } else {
                    classementHTML += `P${index + 1} : Voiture n°${id}<br>`;
                }
            });
            document.getElementById("txt-classement").innerHTML = classementHTML;
        }
    }

    // Boucles de mise à jour asynchrones cadencées à 1 Hz
    setInterval(updateDashboard, 1000);
    setInterval(updateChronometre, 1000); 
});