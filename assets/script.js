// Configuration du graphique de vitesse (Technicien 4 - ID 15)
const ctx = document.getElementById('chart-speed').getContext('2d');
const speedChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [],
        datasets: [{
            label: 'Vitesse Temps Réel (km/h)',
            data: [],
            borderColor: '#007bff',
            backgroundColor: 'rgba(0, 123, 255, 0.1)',
            fill: true,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        scales: { y: { beginAtZero: true } }
    }
});

/**
 * Fonction principale de rafraîchissement des données (ID 15)
 * Récupère les données de api_data.php
 */
async function updateDashboard() {
    try {
        const response = await fetch('api/api_data.php');
        const data = await response.json();

        if (data && !data.error) {
            // --- 1. MISE À JOUR DES TEXTES (Conformité CDC) ---
            const speedElement = document.getElementById('txt-vitesse');
            const batteryElement = document.getElementById('txt-batterie');
            const consoElement = document.getElementById('txt-conso');
            const obstacleElement = document.getElementById('txt-obstacle');
            const sensBadge = document.getElementById('badge-sens');
            const energyBar = document.getElementById('barre-energie');
            
            // Valeurs de base
            speedElement.innerText = data.vitesse + " km/h";
            batteryElement.innerText = data.tension_batterie + " V";
            
            // Nouveaux indicateurs (Tâche ID 15 élargie)
            consoElement.innerText = data.consommation + " A";
            obstacleElement.innerText = (parseInt(data.obstacle) === 1) ? "DÉTECTÉ" : "Néant";
            
            // Gestion du sens de marche
            if(data.sens === "AV") {
                sensBadge.innerText = "MARCHE AVANT";
                sensBadge.className = "badge bg-success";
            } else if(data.sens === "AR") {
                sensBadge.innerText = "MARCHE ARRIÈRE";
                sensBadge.className = "badge bg-warning text-dark";
            }

            // Calcul de l'énergie restante (Échelle 6.0V à 7.2V)
            let pourcentage = ((parseFloat(data.tension_batterie) - 6) / (7.2 - 6)) * 100;
            pourcentage = Math.max(0, Math.min(100, pourcentage)); // Sécurité entre 0 et 100
            energyBar.style.width = pourcentage + "%";
            energyBar.innerText = Math.round(pourcentage) + "%";
            
            // Changement de couleur de la barre selon le niveau
            energyBar.className = (pourcentage < 20) ? "progress-bar bg-danger" : "progress-bar bg-success";

            // --- 2. LOGIQUE D'ALERTE RÈGLEMENTAIRE ---
            if (parseFloat(data.vitesse) === 0) {
                speedElement.classList.add('critical-alert');
            } else {
                speedElement.classList.remove('critical-alert');
            }

            // --- 3. MISE À JOUR DU GRAPHIQUE ---
            const now = new Date().toLocaleTimeString();
            speedChart.data.labels.push(now);
            speedChart.data.datasets[0].data.push(data.vitesse);

            if (speedChart.data.labels.length > 15) {
                speedChart.data.labels.shift();
                speedChart.data.datasets[0].data.shift();
            }
            speedChart.update();
        }
    } catch (error) {
        console.error("Erreur lors de la récupération des données :", error);
    }
}

// Lancer la mise à jour toutes les secondes (1000ms)
setInterval(updateDashboard, 1000);